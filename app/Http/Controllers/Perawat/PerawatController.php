<?php

namespace App\Http\Controllers\Perawat;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
                ->count();
            
            $recentRecords = DB::table('rekam_medis as rm')
                ->join('temu_dokter as td', 'rm.idreservasi_dokter', '=', 'td.idreservasi_dokter')
                ->join('pet as p', 'td.idpet', '=', 'p.idpet')
                ->join('pemilik as pm', 'p.idpemilik', '=', 'pm.idpemilik')
                ->join('user as u', 'pm.iduser', '=', 'u.iduser')
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
}
