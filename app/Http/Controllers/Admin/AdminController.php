<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!session('logged_in') || session('role') !== 'Administrator') {
            return redirect()->route('login')->with('error', 'Akses ditolak. Anda harus login sebagai admin.');
        }

        try {
            $totalUsers = DB::table('user')->count();
            $totalPemilik = DB::table('pemilik')->count();
            $totalPets = DB::table('pet')->count();
            $totalKategori = DB::table('kategori')->count();
            $totalKategoriKlinis = DB::table('kategori_klinis')->count();
            $totalKodeTindakan = DB::table('kode_tindakan_terapi')->count();
            $totalJenisHewan = DB::table('jenis_hewan')->count();
            $totalRasHewan = DB::table('ras_hewan')->count();
            $totalRoles = DB::table('role')->count();
            
            try {
                $totalRekamMedis = DB::table('rekam_medis')->count();
            } catch (\Exception $e) {
                $totalRekamMedis = 0;
            }
            
            try {
                $totalTemuDokter = DB::table('temu_dokter')->count();
            } catch (\Exception $e) {
                $totalTemuDokter = 0;
            }
            
            try {
                $todayAppointments = DB::table('temu_dokter')
                    ->whereDate('waktu_daftar', now()->toDateString())
                    ->count();
            } catch (\Exception $e) {
                $todayAppointments = 0;
            }
            
            try {
                $recentAppointments = DB::table('temu_dokter as td')
                    ->join('pet as p', 'td.idpet', '=', 'p.idpet')
                    ->join('pemilik as pm', 'p.idpemilik', '=', 'pm.idpemilik')
                    ->join('user as u', 'pm.iduser', '=', 'u.iduser')
                    ->join('role_user as ru', 'td.idrole_user', '=', 'ru.idrole_user')
                    ->join('user as doc', 'ru.iduser', '=', 'doc.iduser')
                    ->select(
                        'td.idreservasi_dokter',
                        'p.nama as nama_pet',
                        'u.nama as nama_pemilik',
                        'doc.nama as nama_dokter',
                        'td.waktu_daftar',
                        'td.status'
                    )
                    ->orderBy('td.waktu_daftar', 'desc')
                    ->limit(5)
                    ->get();
            } catch (\Exception $e) {
                $recentAppointments = [];
            }
            
        } catch (\Exception $e) {
            $totalUsers = $totalPemilik = $totalPets = $totalRekamMedis = 0;
            $totalTemuDokter = $totalKategori = $totalKategoriKlinis = $totalKodeTindakan = 0;
            $totalJenisHewan = $totalRasHewan = $totalRoles = $todayAppointments = 0;
            $recentAppointments = [];
        }
        
        return view('admin.dashboard', compact(
            'totalUsers', 'totalPemilik', 'totalPets', 'totalRekamMedis', 
            'totalTemuDokter', 'totalKategori', 'totalKategoriKlinis', 
            'totalKodeTindakan', 'totalJenisHewan', 'totalRasHewan', 
            'totalRoles', 'todayAppointments', 'recentAppointments'
        ));
    }
}