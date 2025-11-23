<?php

namespace App\Http\Controllers;

use App\Models\Perawat;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PerawatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perawats = Perawat::with('user')->get();
        return view('admin.perawat.index', compact('perawats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.perawat.create');
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
            'pendidikan' => 'required|string|max:100',
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

            // Get perawat role
            $perawatRole = Role::where('nama_role', 'perawat')->first();
            
            if ($perawatRole) {
                // Attach role to user
                $user->roles()->attach($perawatRole->idrole, ['status' => '1']);
            }

            // Create perawat profile
            Perawat::create([
                'id_user' => $user->iduser,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'pendidikan' => $request->pendidikan,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);

            DB::commit();
            return redirect()->route('admin.perawat.index')
                ->with('success', 'Data perawat berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan data perawat: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $perawat = Perawat::with('user')->findOrFail($id);
        return view('admin.perawat.show', compact('perawat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $perawat = Perawat::with('user')->findOrFail($id);
        return view('admin.perawat.edit', compact('perawat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $perawat = Perawat::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email,' . $perawat->id_user . ',iduser',
            'alamat' => 'required|string|max:100',
            'no_hp' => 'required|string|max:45',
            'pendidikan' => 'required|string|max:100',
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

            $perawat->user->update($userData);

            // Update perawat profile
            $perawat->update([
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'pendidikan' => $request->pendidikan,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);

            DB::commit();
            return redirect()->route('admin.perawat.index')
                ->with('success', 'Data perawat berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal mengupdate data perawat: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $perawat = Perawat::findOrFail($id);
            $user = $perawat->user;

            // Delete perawat profile (cascade will handle this)
            $perawat->delete();

            // Delete user (this will cascade to role_user)
            $user->delete();

            DB::commit();
            return redirect()->route('admin.perawat.index')
                ->with('success', 'Data perawat berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus data perawat: ' . $e->getMessage());
        }
    }
}
