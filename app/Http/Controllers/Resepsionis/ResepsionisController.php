<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ResepsionisController extends Controller
{
    public function dashboard()
    {
        try {
            $user = Auth::user();
            
            $todayQueue = DB::table('temu_dokter')
                ->where('status', 1)
                ->whereDate('waktu_daftar', now()->toDateString())
                ->whereNull('deleted_at')
                ->count();
            
            $completedToday = DB::table('temu_dokter')
                ->where('status', 2)
                ->whereDate('waktu_daftar', now()->toDateString())
                ->whereNull('deleted_at')
                ->count();
            
            $totalPets = DB::table('pet')
                ->whereNull('deleted_at')
                ->count();
            
            $totalOwners = DB::table('pemilik')
                ->whereNull('deleted_at')
                ->count();
            
            $activeQueue = DB::table('temu_dokter as td')
                ->join('pet as p', 'td.idpet', '=', 'p.idpet')
                ->join('pemilik as pm', 'p.idpemilik', '=', 'pm.idpemilik')
                ->join('user as u', 'pm.iduser', '=', 'u.iduser')
                ->join('role_user as ru', 'td.idrole_user', '=', 'ru.idrole_user')
                ->join('user as doc', 'ru.iduser', '=', 'doc.iduser')
                ->where('td.status', 1)
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
                    'td.waktu_daftar'
                )
                ->orderBy('td.no_urut', 'asc')
                ->get();
            
            $recentPets = DB::table('pet as p')
                ->join('pemilik as pm', 'p.idpemilik', '=', 'pm.idpemilik')
                ->join('user as u', 'pm.iduser', '=', 'u.iduser')
                ->whereNull('p.deleted_at')
                ->whereNull('pm.deleted_at')
                ->select(
                    'p.idpet',
                    'p.nama as nama_pet',
                    'u.nama as nama_pemilik'
                )
                ->orderBy('p.idpet', 'desc')
                ->limit(5)
                ->get();
            
        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            $todayQueue = 0;
            $completedToday = 0;
            $totalPets = 0;
            $totalOwners = 0;
            $activeQueue = [];
            $recentPets = [];
        }
        
        return view('resepsionis.dashboard', compact(
            'user',
            'todayQueue',
            'completedToday',
            'totalPets',
            'totalOwners',
            'activeQueue',
            'recentPets'
        ));
    }
}
