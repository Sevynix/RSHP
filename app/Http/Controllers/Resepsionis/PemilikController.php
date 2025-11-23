<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemilikController extends Controller
{
    public function index()
    {
        // Check authorization
        if (session('user_role') != 4) {
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        $pemilik = DB::table('pemilik')
            ->leftJoin('user', 'pemilik.iduser', '=', 'user.iduser')
            ->select('pemilik.*', 'user.nama', 'user.email')
            ->get();

        return view('resepsionis.datapemilik', compact('pemilik'));
    }

    public function create()
    {
        // Check authorization
        if (session('user_role') != 4) {
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        $existingUsers = DB::table('user')
            ->whereNotIn('iduser', function ($query) {
                $query->select('iduser')->from('pemilik');
            })
            ->select('user.iduser', 'user.nama', 'user.email')
            ->orderBy('user.nama')
            ->get();

        return view('resepsionis.addpemilik', compact('existingUsers'));
    }

    public function storeNew(Request $request)
    {
        // Check authorization
        if (session('user_role') != 4) {
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        $validated = $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|min:6|confirmed',
            'alamat' => 'nullable',
            'no_wa' => 'nullable|regex:/^08[0-9]{8,11}$/'
        ]);

        DB::beginTransaction();
        try {
            // Create user
            $userId = DB::table('user')->insertGetId([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'password' => password_hash($validated['password'], PASSWORD_DEFAULT)
            ]);

            // Create pemilik
            DB::table('pemilik')->insert([
                'iduser' => $userId,
                'alamat' => $validated['alamat'],
                'no_wa' => $validated['no_wa']
            ]);

            // Assign role (5 = pemilik role)
            DB::table('role_user')->insert([
                'iduser' => $userId,
                'idrole' => 5
            ]);

            DB::commit();
            return redirect()->route('resepsionis.pemilik.index')->with('success', 'Data pemilik berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan data: ' . $e->getMessage())->withInput();
        }
    }

    public function storeExisting(Request $request)
    {
        // Check authorization
        if (session('user_role') != 4) {
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        $validated = $request->validate([
            'iduser' => 'required|exists:user,iduser',
            'alamat' => 'nullable',
            'no_wa' => 'nullable|regex:/^08[0-9]{8,11}$/'
        ]);

        DB::beginTransaction();
        try {
            // Create pemilik
            DB::table('pemilik')->insert([
                'iduser' => $validated['iduser'],
                'alamat' => $validated['alamat'],
                'no_wa' => $validated['no_wa']
            ]);

            // Assign role (5 = pemilik role)
            DB::table('role_user')->insert([
                'iduser' => $validated['iduser'],
                'idrole' => 5
            ]);

            DB::commit();
            return redirect()->route('resepsionis.pemilik.index')->with('success', 'Data pemilik berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan data: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        // Check authorization
        if (session('user_role') != 4) {
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        DB::beginTransaction();
        try {
            $pemilik = DB::table('pemilik')->where('idpemilik', $id)->first();
            
            if (!$pemilik) {
                return back()->with('error', 'Data pemilik tidak ditemukan.');
            }

            // Delete role_user
            DB::table('role_user')->where('iduser', $pemilik->iduser)->delete();
            
            // Delete pemilik
            DB::table('pemilik')->where('idpemilik', $id)->delete();
            
            // Delete user
            DB::table('user')->where('iduser', $pemilik->iduser)->delete();

            DB::commit();
            return redirect()->route('resepsionis.pemilik.index')->with('success', 'Data pemilik berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
