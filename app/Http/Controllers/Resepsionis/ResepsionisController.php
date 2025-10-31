<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ResepsionisController extends Controller
{
    public function dashboard()
    {
        try {
            $user = Auth::user();
            
            $todayQueue = DB::table('temu_dokter')
                ->where('status', 1)
                ->whereDate('waktu_daftar', now()->toDateString())
                ->count();
            
            $completedToday = DB::table('temu_dokter')
                ->where('status', 2)
                ->whereDate('waktu_daftar', now()->toDateString())
                ->count();
            
            $totalPets = DB::table('pet')->count();
            
            $totalOwners = DB::table('pemilik')->count();
            
            $activeQueue = DB::table('temu_dokter as td')
                ->join('pet as p', 'td.idpet', '=', 'p.idpet')
                ->join('pemilik as pm', 'p.idpemilik', '=', 'pm.idpemilik')
                ->join('user as u', 'pm.iduser', '=', 'u.iduser')
                ->join('role_user as ru', 'td.idrole_user', '=', 'ru.idrole_user')
                ->join('user as doc', 'ru.iduser', '=', 'doc.iduser')
                ->where('td.status', 1)
                ->whereDate('td.waktu_daftar', now()->toDateString())
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
                ->select(
                    'p.idpet',
                    'p.nama as nama_pet',
                    'u.nama as nama_pemilik',
                    'p.created_at'
                )
                ->orderBy('p.created_at', 'desc')
                ->limit(5)
                ->get();
            
        } catch (\Exception $e) {
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
