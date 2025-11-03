<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
}
