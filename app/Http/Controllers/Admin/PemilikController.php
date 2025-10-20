<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemilik;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class PemilikController extends Controller
{
    public function index()
    {
        if (!session('is_admin')) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $pemiliks = Pemilik::with('user')
            ->orderBy('idpemilik', 'desc')
            ->get();

        return view('admin.pemilik.index', compact('pemiliks'));
    }

    public function create()
    {
        if (!session('is_admin')) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $availableUsers = User::whereNotIn('iduser', function ($query) {
            $query->select('iduser')->from('pemilik');
        })->orderBy('nama')->get();

        return view('admin.pemilik.create', compact('availableUsers'));
    }

    public function storeNew(Request $request)
    {
        if (!session('is_admin')) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email|max:100',
            'password' => ['required', 'confirmed', Password::min(6)],
            'no_wa' => 'nullable|regex:/^08[0-9]{8,11}$/|max:15',
            'alamat' => 'nullable|string|max:255',
        ], [
            'nama.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password harus diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.min' => 'Password minimal 6 karakter',
            'no_wa.regex' => 'Format nomor WhatsApp tidak valid. Gunakan format: 08xxxxxxxxxx',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            Pemilik::create([
                'iduser' => $user->iduser,
                'no_wa' => $validated['no_wa'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('admin.pemilik.index')
                ->with('success', 'User dan pemilik baru berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating new pemilik: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal membuat user dan pemilik: ' . $e->getMessage());
        }
    }

    public function storeExisting(Request $request)
    {
        if (!session('is_admin')) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $validated = $request->validate([
            'iduser' => 'required|exists:user,iduser',
            'no_wa' => 'nullable|regex:/^08[0-9]{8,11}$/|max:15',
            'alamat' => 'nullable|string|max:255',
        ], [
            'iduser.required' => 'User harus dipilih',
            'iduser.exists' => 'User tidak ditemukan',
            'no_wa.regex' => 'Format nomor WhatsApp tidak valid. Gunakan format: 08xxxxxxxxxx',
        ]);

        try {
            $existingPemilik = Pemilik::where('iduser', $validated['iduser'])->first();
            if ($existingPemilik) {
                return redirect()->back()
                    ->with('error', 'User ini sudah menjadi pemilik');
            }

            Pemilik::create([
                'iduser' => $validated['iduser'],
                'no_wa' => $validated['no_wa'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
            ]);

            $user = User::find($validated['iduser']);

            return redirect()->route('admin.pemilik.index')
                ->with('success', "User '{$user->nama}' berhasil dijadikan pemilik");
        } catch (\Exception $e) {
            Log::error('Error creating pemilik from existing user: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menjadikan user sebagai pemilik: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        if (!session('is_admin')) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        try {
            $pemilik = Pemilik::with('user')->findOrFail($id);
            $userName = $pemilik->user->nama;

            $hasPets = DB::table('pet')->where('idpemilik', $id)->exists();
            if ($hasPets) {
                return redirect()->route('admin.pemilik.index')
                    ->with('error', 'Pemilik tidak dapat dihapus karena masih memiliki pet');
            }

            $pemilik->delete();

            return redirect()->route('admin.pemilik.index')
                ->with('success', "Pemilik '{$userName}' berhasil dihapus (user tetap ada)");
        } catch (\Exception $e) {
            Log::error('Error deleting pemilik: ' . $e->getMessage());

            return redirect()->route('admin.pemilik.index')
                ->with('error', 'Gagal menghapus pemilik: ' . $e->getMessage());
        }
    }
}
