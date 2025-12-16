<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class DokterController extends Controller
{
    public function dashboard()
    {
        try {
            $user = Auth::user();
            
            $roleUser = DB::table('role_user')
                ->where('iduser', $user->iduser)
                ->where('idrole', 2)
                ->first();
            
            if (!$roleUser) {
                return redirect()->route('login')->with('error', 'Role dokter tidak ditemukan.');
            }
            
            $todayRecords = DB::table('rekam_medis as rm')
                ->join('temu_dokter as td', 'rm.idreservasi_dokter', '=', 'td.idreservasi_dokter')
                ->where('td.idrole_user', $roleUser->idrole_user)
                ->whereDate('rm.created_at', now()->toDateString())
                ->count();
            
            $monthlyRecords = DB::table('rekam_medis as rm')
                ->join('temu_dokter as td', 'rm.idreservasi_dokter', '=', 'td.idreservasi_dokter')
                ->where('td.idrole_user', $roleUser->idrole_user)
                ->whereMonth('rm.created_at', now()->month)
                ->whereYear('rm.created_at', now()->year)
                ->count();
            
            $pendingReservations = DB::table('temu_dokter')
                ->where('idrole_user', $roleUser->idrole_user)
                ->where('status', 1)
                ->whereDate('waktu_daftar', now()->toDateString())
                ->count();
            
            $todayPets = DB::table('temu_dokter')
                ->where('idrole_user', $roleUser->idrole_user)
                ->whereDate('waktu_daftar', now()->toDateString())
                ->distinct('idpet')
                ->count('idpet');
            
            $recentRecords = DB::table('rekam_medis as rm')
                ->join('temu_dokter as td', 'rm.idreservasi_dokter', '=', 'td.idreservasi_dokter')
                ->join('pet as p', 'td.idpet', '=', 'p.idpet')
                ->join('pemilik as pm', 'p.idpemilik', '=', 'pm.idpemilik')
                ->join('user as u', 'pm.iduser', '=', 'u.iduser')
                ->join('jenis_hewan as jh', 'p.idjenis_hewan', '=', 'jh.idjenis_hewan')
                ->leftJoin('user as doc', 'rm.dokter_pemeriksa', '=', 'doc.iduser')
                ->where('td.idrole_user', $roleUser->idrole_user)
                ->select(
                    'rm.idrekam_medis',
                    'p.nama as nama_pet',
                    'u.nama as nama_pemilik',
                    'jh.nama_jenis as jenis_hewan',
                    'rm.diagnosa',
                    'doc.nama as nama_dokter_pemeriksa',
                    'rm.created_at'
                )
                ->orderBy('rm.created_at', 'desc')
                ->limit(10)
                ->get();
            
            $todayReservations = DB::table('temu_dokter as td')
                ->join('pet as p', 'td.idpet', '=', 'p.idpet')
                ->join('pemilik as pm', 'p.idpemilik', '=', 'pm.idpemilik')
                ->join('user as u', 'pm.iduser', '=', 'u.iduser')
                ->where('td.idrole_user', $roleUser->idrole_user)
                ->whereDate('td.waktu_daftar', now()->toDateString())
                ->select(
                    'td.idreservasi_dokter',
                    'td.no_urut',
                    'p.nama as nama_pet',
                    'u.nama as nama_pemilik',
                    'td.waktu_daftar',
                    'td.status'
                )
                ->orderBy('td.no_urut', 'asc')
                ->get();
            
            $stats = [
                'today_records' => $todayRecords,
                'monthly_records' => $monthlyRecords,
                'pending_reservations' => $pendingReservations,
                'today_pets' => $todayPets
            ];
            
        } catch (\Exception $e) {
            $stats = [
                'today_records' => 0,
                'monthly_records' => 0,
                'pending_reservations' => 0,
                'today_pets' => 0
            ];
            $recentRecords = [];
            $todayReservations = [];
        }
        
        return view('dokter.dashboard', compact('stats', 'recentRecords', 'todayReservations', 'user'));
    }

    public function profil()
    {
        if (session('user_role') != 2) {
            abort(403);
        }

        $user = Auth::user();
        $dokter = $user->dokter;
        
        if (!$dokter) {
            return redirect()->route('dokter.dashboard')->with('error', 'Data profil dokter tidak ditemukan.');
        }
        
        return view('dokter.profil', compact('user', 'dokter'));
    }

    public function editProfil()
    {
        if (session('user_role') != 2) {
            abort(403);
        }

        $user = Auth::user();
        $dokter = $user->dokter;
        
        if (!$dokter) {
            return redirect()->route('dokter.dashboard')->with('error', 'Data profil dokter tidak ditemukan.');
        }
        
        return view('dokter.edit_profil', compact('user', 'dokter'));
    }

    public function updateProfil(Request $request)
    {
        if (session('user_role') != 2) {
            abort(403);
        }

        $user = Auth::user();
        $dokter = $user->dokter;
        
        if (!$dokter) {
            return redirect()->route('dokter.dashboard')->with('error', 'Data profil dokter tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email,' . $user->iduser . ',iduser',
            'alamat' => 'required|string|max:100',
            'no_hp' => 'required|string|max:45',
            'bidang_dokter' => 'required|string|max:100',
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

            DB::table('user')->where('iduser', $user->iduser)->update($userData);

            // Update dokter profile
            $dokter->update([
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'bidang_dokter' => $request->bidang_dokter,
            ]);

            DB::commit();
            return redirect()->route('dokter.profil')
                ->with('success', 'Profil berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal mengupdate profil: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function dataPasien()
    {
        if (session('user_role') != 2) {
            abort(403);
        }

        $pasien = DB::table('pet')
            ->join('pemilik', 'pet.idpemilik', '=', 'pemilik.idpemilik')
            ->join('user', 'pemilik.iduser', '=', 'user.iduser')
            ->join('ras_hewan', 'pet.idras_hewan', '=', 'ras_hewan.idras_hewan')
            ->join('jenis_hewan', 'ras_hewan.idjenis_hewan', '=', 'jenis_hewan.idjenis_hewan')
            ->whereNull('pet.deleted_at')
            ->whereNull('pemilik.deleted_at')
            ->select(
                'pet.*',
                'user.nama as nama_pemilik',
                'user.email as email_pemilik',
                'pemilik.no_wa',
                'jenis_hewan.nama_jenis_hewan as jenis_hewan',
                'ras_hewan.nama_ras as ras_hewan'
            )
            ->orderBy('pet.idpet', 'desc')
            ->paginate(15);

        return view('dokter.data_pasien', compact('pasien'));
    }
}
