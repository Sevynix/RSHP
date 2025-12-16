<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use App\Models\TemuDokter;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TemuDokterController extends Controller
{
    /**
     * Display a listing of temu dokter appointments
     */
    public function index()
    {
        if (!in_array(session('user_role'), [4, 1])) { // Resepsionis or Admin
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        // Get today's appointments
        $antrianList = DB::table('temu_dokter as td')
            ->leftJoin('pet as p', 'td.idpet', '=', 'p.idpet')
            ->leftJoin('pemilik as pm', 'p.idpemilik', '=', 'pm.idpemilik')
            ->leftJoin('user as usr', 'pm.iduser', '=', 'usr.iduser')
            ->leftJoin('role_user as ru', 'td.idrole_user', '=', 'ru.idrole_user')
            ->leftJoin('user as dokter', 'ru.iduser', '=', 'dokter.iduser')
            ->whereNull('td.deleted_at')
            ->whereNull('p.deleted_at')
            ->whereNull('pm.deleted_at')
            ->select(
                'td.*',
                'p.nama as nama_pet',
                'usr.nama as nama_pemilik',
                'dokter.nama as nama_dokter'
            )
            ->whereDate('td.waktu_daftar', today())
            ->orderBy('td.no_urut', 'asc')
            ->get();

        // Get expired appointments (status menunggu tapi sudah lewat)
        $expiredList = DB::table('temu_dokter as td')
            ->leftJoin('pet as p', 'td.idpet', '=', 'p.idpet')
            ->leftJoin('pemilik as pm', 'p.idpemilik', '=', 'pm.idpemilik')
            ->leftJoin('user as usr', 'pm.iduser', '=', 'usr.iduser')
            ->leftJoin('role_user as ru', 'td.idrole_user', '=', 'ru.idrole_user')
            ->leftJoin('user as dokter', 'ru.iduser', '=', 'dokter.iduser')
            ->where('td.status', 1)
            ->whereDate('td.waktu_daftar', '<', today())
            ->whereNull('td.deleted_at')
            ->whereNull('p.deleted_at')
            ->whereNull('pm.deleted_at')
            ->select(
                'td.*',
                'p.nama as nama_pet',
                'usr.nama as nama_pemilik',
                'dokter.nama as nama_dokter'
            )
            ->orderBy('td.waktu_daftar', 'desc')
            ->get();

        return view('resepsionis.temudokter', compact('antrianList', 'expiredList'));
    }

    /**
     * Show the form for creating a new appointment
     */
    public function create()
    {
        if (!in_array(session('user_role'), [4, 1])) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        // Get list of pets with their owners
        $petList = DB::table('pet as p')
            ->leftJoin('pemilik as pm', 'p.idpemilik', '=', 'pm.idpemilik')
            ->leftJoin('user as usr', 'pm.iduser', '=', 'usr.iduser')
            ->whereNull('p.deleted_at')
            ->whereNull('pm.deleted_at')
            ->select('p.idpet', 'p.nama as nama_pet', 'usr.nama as nama_pemilik')
            ->orderBy('p.nama', 'asc')
            ->get();

        // Get list of doctors (role_id = 2 for dokter)
        $dokterList = DB::table('role_user as ru')
            ->join('user as u', 'ru.iduser', '=', 'u.iduser')
            ->where('ru.idrole', 2)
            ->where('ru.status', '1')
            ->whereNull('ru.deleted_at')
            ->whereNull('u.deleted_at')
            ->select('ru.idrole_user', 'u.nama')
            ->orderBy('u.nama', 'asc')
            ->get();

        return view('resepsionis.addtemudokter', compact('petList', 'dokterList'));
    }

    /**
     * Store a newly created appointment
     */
    public function store(Request $request)
    {
        if (!in_array(session('user_role'), [4, 1])) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $request->validate([
            'idpet' => 'required|exists:pet,idpet',
            'idrole_user_dokter' => 'required|exists:role_user,idrole_user',
        ], [
            'idpet.required' => 'Pet harus dipilih',
            'idpet.exists' => 'Pet tidak ditemukan',
            'idrole_user_dokter.required' => 'Dokter harus dipilih',
            'idrole_user_dokter.exists' => 'Dokter tidak ditemukan',
        ]);

        try {
            $maxNoUrut = DB::table('temu_dokter')
                ->whereDate('waktu_daftar', today())
                ->max('no_urut');
            
            $nextNoUrut = $maxNoUrut ? $maxNoUrut + 1 : 1;

            DB::table('temu_dokter')->insert([
                'no_urut' => $nextNoUrut,
                'waktu_daftar' => now(),
                'idpet' => $request->idpet,
                'idrole_user' => $request->idrole_user_dokter,
                'status' => '1', // Menunggu
            ]);

            return redirect()->route('resepsionis.temudokter.index')
                ->with('success', 'Jadwal temu dokter berhasil ditambahkan');

        } catch (\Exception $e) {
            Log::error("TemuDokter store error: " . $e->getMessage());
            return back()->with('error', 'Gagal membuat jadwal temu dokter: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mark appointment as completed
     */
    public function complete(Request $request)
    {
        if (!in_array(session('user_role'), [4, 1])) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        try {
            if ($request->has('no_urut')) {
                // Complete by queue number (for today's appointments)
                DB::table('temu_dokter')
                    ->whereDate('waktu_daftar', today())
                    ->where('no_urut', $request->no_urut)
                    ->update(['status' => '2']); // Selesai
                    
            } elseif ($request->has('id_reservasi')) {
                // Complete by reservation ID (for old appointments)
                DB::table('temu_dokter')
                    ->where('idreservasi_dokter', $request->id_reservasi)
                    ->update(['status' => '2']); // Selesai
            }

            return redirect()->route('resepsionis.temudokter.index')
                ->with('success', 'Temu dokter berhasil diselesaikan');

        } catch (\Exception $e) {
            Log::error("TemuDokter complete error: " . $e->getMessage());
            return back()->with('error', 'Gagal menyelesaikan temu dokter');
        }
    }
}
