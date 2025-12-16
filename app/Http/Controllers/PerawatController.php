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
        $perawats = Perawat::with('user')
            ->whereHas('user')
            ->get();
        return view('admin.perawat.index', compact('perawats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Exclude users who are already perawat (not soft deleted)
        $availableUsers = User::whereNotIn('iduser', function ($query) {
            $query->select('id_user')
                ->from('perawat')
                ->whereNull('deleted_at');
        })
        ->select('iduser', 'nama', 'email')
        ->orderBy('nama')
        ->get();

        return view('admin.perawat.create', compact('availableUsers'));
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
     * Store perawat from existing user.
     */
    public function storeExisting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'iduser' => 'required|exists:user,iduser',
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
            // Check if user already has an active perawat record
            $existingPerawat = Perawat::where('id_user', $request->iduser)->first();
            if ($existingPerawat) {
                return redirect()->back()
                    ->with('error', 'User ini sudah menjadi perawat');
            }

            // Check if there's a soft deleted perawat record
            $deletedPerawat = Perawat::withTrashed()->where('id_user', $request->iduser)->onlyTrashed()->first();
            
            if ($deletedPerawat) {
                // Restore the soft deleted record and update the data
                $deletedPerawat->restore();
                $deletedPerawat->update([
                    'alamat' => $request->alamat,
                    'no_hp' => $request->no_hp,
                    'pendidikan' => $request->pendidikan,
                    'jenis_kelamin' => $request->jenis_kelamin,
                ]);
                $message = 'berhasil dikembalikan sebagai perawat';
            } else {
                // Get or create perawat role
                $perawatRole = Role::where('nama_role', 'perawat')->first();
                
                if ($perawatRole) {
                    // Attach role to user if not already attached
                    $user = User::find($request->iduser);
                    if (!$user->roles()->where('role_user.idrole', $perawatRole->idrole)->exists()) {
                        $user->roles()->attach($perawatRole->idrole, ['status' => '1']);
                    }
                }

                // Create new perawat record
                Perawat::create([
                    'id_user' => $request->iduser,
                    'alamat' => $request->alamat,
                    'no_hp' => $request->no_hp,
                    'pendidikan' => $request->pendidikan,
                    'jenis_kelamin' => $request->jenis_kelamin,
                ]);
                $message = 'berhasil dijadikan perawat';
            }

            $user = User::find($request->iduser);

            DB::commit();
            return redirect()->route('admin.perawat.index')
                ->with('success', "User '{$user->nama}' {$message}");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menjadikan user sebagai perawat: ' . $e->getMessage())
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
        try {
            $perawat = Perawat::with('user')->findOrFail($id);
            $userName = $perawat->user->nama;

            // Only soft delete the perawat profile, keep the user
            $perawat->delete();

            return redirect()->route('admin.perawat.index')
                ->with('success', "Perawat '{$userName}' berhasil dihapus (user tetap ada)");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data perawat: ' . $e->getMessage());
        }
    }
}
