<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class PerawatController extends Controller
{
    public function dashboard()
    {
        try {
            $user = Auth::user();
            
            $todayRecords = DB::table('rekam_medis')
                ->whereDate('created_at', now()->toDateString())
                ->count();
            
            $monthlyRecords = DB::table('rekam_medis')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            
            $pendingReservations = DB::table('temu_dokter')
                ->where('status', 1)
                ->whereDate('waktu_daftar', now()->toDateString())
                ->whereNull('deleted_at')
                ->count();
            
            $recentRecords = DB::table('rekam_medis as rm')
                ->join('temu_dokter as td', 'rm.idreservasi_dokter', '=', 'td.idreservasi_dokter')
                ->join('pet as p', 'td.idpet', '=', 'p.idpet')
                ->join('pemilik as pm', 'p.idpemilik', '=', 'pm.idpemilik')
                ->join('user as u', 'pm.iduser', '=', 'u.iduser')
                ->whereNull('td.deleted_at')
                ->whereNull('p.deleted_at')
                ->whereNull('pm.deleted_at')
                ->select(
                    'rm.idrekam_medis',
                    'p.nama as nama_pet',
                    'u.nama as nama_pemilik',
                    'rm.diagnosa',
                    'rm.created_at'
                )
                ->orderBy('rm.created_at', 'desc')
                ->limit(10)
                ->get();
            
            $todayReservations = DB::table('temu_dokter as td')
                ->join('pet as p', 'td.idpet', '=', 'p.idpet')
                ->join('pemilik as pm', 'p.idpemilik', '=', 'pm.idpemilik')
                ->join('user as u', 'pm.iduser', '=', 'u.iduser')
                ->join('role_user as ru', 'td.idrole_user', '=', 'ru.idrole_user')
                ->join('user as doc', 'ru.iduser', '=', 'doc.iduser')
                ->whereDate('td.waktu_daftar', now()->toDateString())
                ->whereNull('td.deleted_at')
                ->whereNull('p.deleted_at')
                ->whereNull('pm.deleted_at')
                ->select(
                    'td.idreservasi_dokter',
                    'td.no_urut',
                    'p.nama as nama_pet',
                    'u.nama as nama_pemilik',
                    'doc.nama as nama_dokter',
                    'td.waktu_daftar',
                    'td.status'
                )
                ->orderBy('td.no_urut', 'asc')
                ->get();
            
            $stats = [
                'today_records' => $todayRecords,
                'monthly_records' => $monthlyRecords,
                'pending_reservations' => $pendingReservations
            ];
            
        } catch (\Exception $e) {
            $stats = [
                'today_records' => 0,
                'monthly_records' => 0,
                'pending_reservations' => 0
            ];
            $recentRecords = [];
            $todayReservations = [];
        }
        
        return view('perawat.dashboard', compact('stats', 'recentRecords', 'todayReservations', 'user'));
    }

    public function profil()
    {
        if (session('user_role') != 3) {
            abort(403);
        }

        $user = Auth::user();
        $perawat = $user->perawat;
        
        if (!$perawat) {
            return redirect()->route('perawat.dashboard')->with('error', 'Data profil perawat tidak ditemukan.');
        }
        
        return view('perawat.profil', compact('user', 'perawat'));
    }

    public function editProfil()
    {
        if (session('user_role') != 3) {
            abort(403);
        }

        $user = Auth::user();
        $perawat = $user->perawat;
        
        if (!$perawat) {
            return redirect()->route('perawat.dashboard')->with('error', 'Data profil perawat tidak ditemukan.');
        }
        
        return view('perawat.edit_profil', compact('user', 'perawat'));
    }

    public function updateProfil(Request $request)
    {
        if (session('user_role') != 3) {
            abort(403);
        }

        $user = Auth::user();
        $perawat = $user->perawat;
        
        if (!$perawat) {
            return redirect()->route('perawat.dashboard')->with('error', 'Data profil perawat tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email,' . $user->iduser . ',iduser',
            'alamat' => 'required|string|max:100',
            'no_hp' => 'required|string|max:45',
            'pendidikan' => 'required|string|max:100',
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

            // Update perawat profile
            $perawat->update([
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'pendidikan' => $request->pendidikan,
            ]);

            DB::commit();
            return redirect()->route('perawat.profil')
                ->with('success', 'Profil berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal mengupdate profil: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display list of patients (pets)
     */
    public function dataPasien()
    {
        if (session('user_role') != 3) {
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

        return view('perawat.data_pasien', compact('pasien'));
    }
}
