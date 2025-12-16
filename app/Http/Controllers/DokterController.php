<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DokterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dokters = Dokter::with('user')
            ->whereHas('user')
            ->get();
        return view('admin.dokter.index', compact('dokters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Exclude users who are already dokter (not soft deleted)
        $availableUsers = User::whereNotIn('iduser', function ($query) {
            $query->select('id_user')
                ->from('dokter')
                ->whereNull('deleted_at');
        })
        ->select('iduser', 'nama', 'email')
        ->orderBy('nama')
        ->get();

        return view('admin.dokter.create', compact('availableUsers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|string|min:6',
            'alamat' => 'required|string|max:100',
            'no_hp' => 'required|string|max:45',
            'bidang_dokter' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Create user
            $user = User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Get dokter role
            $dokterRole = Role::where('nama_role', 'dokter')->first();
            
            if ($dokterRole) {
                // Attach role to user
                $user->roles()->attach($dokterRole->idrole, ['status' => '1']);
            }

            // Create dokter profile
            Dokter::create([
                'id_user' => $user->iduser,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'bidang_dokter' => $request->bidang_dokter,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);

            DB::commit();
            return redirect()->route('admin.dokter.index')
                ->with('success', 'Data dokter berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan data dokter: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Store dokter from existing user.
     */
    public function storeExisting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'iduser' => 'required|exists:user,iduser',
            'alamat' => 'required|string|max:100',
            'no_hp' => 'required|string|max:45',
            'bidang_dokter' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Check if user already has an active dokter record
            $existingDokter = Dokter::where('id_user', $request->iduser)->first();
            if ($existingDokter) {
                return redirect()->back()
                    ->with('error', 'User ini sudah menjadi dokter');
            }

            // Check if there's a soft deleted dokter record
            $deletedDokter = Dokter::withTrashed()->where('id_user', $request->iduser)->onlyTrashed()->first();
            
            if ($deletedDokter) {
                // Restore the soft deleted record and update the data
                $deletedDokter->restore();
                $deletedDokter->update([
                    'alamat' => $request->alamat,
                    'no_hp' => $request->no_hp,
                    'bidang_dokter' => $request->bidang_dokter,
                    'jenis_kelamin' => $request->jenis_kelamin,
                ]);
                $message = 'berhasil dikembalikan sebagai dokter';
            } else {
                // Get or create dokter role
                $dokterRole = Role::where('nama_role', 'dokter')->first();
                
                if ($dokterRole) {
                    // Attach role to user if not already attached
                    $user = User::find($request->iduser);
                    if (!$user->roles()->where('role_user.idrole', $dokterRole->idrole)->exists()) {
                        $user->roles()->attach($dokterRole->idrole, ['status' => '1']);
                    }
                }

                // Create new dokter record
                Dokter::create([
                    'id_user' => $request->iduser,
                    'alamat' => $request->alamat,
                    'no_hp' => $request->no_hp,
                    'bidang_dokter' => $request->bidang_dokter,
                    'jenis_kelamin' => $request->jenis_kelamin,
                ]);
                $message = 'berhasil dijadikan dokter';
            }

            $user = User::find($request->iduser);

            DB::commit();
            return redirect()->route('admin.dokter.index')
                ->with('success', "User '{$user->nama}' {$message}");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menjadikan user sebagai dokter: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dokter = Dokter::with('user')->findOrFail($id);
        return view('admin.dokter.show', compact('dokter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dokter = Dokter::with('user')->findOrFail($id);
        return view('admin.dokter.edit', compact('dokter'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dokter = Dokter::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email,' . $dokter->id_user . ',iduser',
            'alamat' => 'required|string|max:100',
            'no_hp' => 'required|string|max:45',
            'bidang_dokter' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'password' => 'nullable|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Update user
            $userData = [
                'nama' => $request->nama,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $dokter->user->update($userData);

            // Update dokter profile
            $dokter->update([
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'bidang_dokter' => $request->bidang_dokter,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);

            DB::commit();
            return redirect()->route('admin.dokter.index')
                ->with('success', 'Data dokter berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal mengupdate data dokter: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $dokter = Dokter::with('user')->findOrFail($id);
            $userName = $dokter->user->nama;

            // Only soft delete the dokter profile, keep the user
            $dokter->delete();

            return redirect()->route('admin.dokter.index')
                ->with('success', "Dokter '{$userName}' berhasil dihapus (user tetap ada)");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data dokter: ' . $e->getMessage());
        }
    }
}
