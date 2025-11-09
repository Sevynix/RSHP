<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        if (!session('is_admin')) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $users = User::orderBy('iduser', 'desc')->get();
        
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        if (!session('is_admin')) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        if (!session('is_admin')) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email|max:100',
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'nama.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password harus diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        if (!session('is_admin')) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $user = User::findOrFail($id);
        
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        if (!session('is_admin')) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
        ], [
            'nama.required' => 'Nama harus diisi',
        ]);

        $user->update([
            'nama' => $validated['nama'],
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate');
    }

    public function destroy($id)
    {
        if (!session('is_admin')) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $user = User::findOrFail($id);

        $isPemilik = DB::table('pemilik')->where('iduser', $id)->exists();
        if ($isPemilik) {
            return redirect()->route('admin.users.index')
                ->with('error', 'User tidak dapat dihapus karena masih menjadi pemilik');
        }

        $hasRoles = DB::table('role_user')->where('iduser', $id)->exists();
        if ($hasRoles) {
            DB::table('role_user')->where('iduser', $id)->delete();
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }

    public function resetPassword($id)
    {
        if (!session('is_admin')) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $user = User::findOrFail($id);
        
        return view('admin.users.reset-password', compact('user'));
    }

    public function resetPasswordConfirm($id)
    {
        if (!session('is_admin')) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $user = User::findOrFail($id);

        $user->update([
            'password' => Hash::make('123456'),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Password user {$user->nama} berhasil direset menjadi '123456'");
    }
}
