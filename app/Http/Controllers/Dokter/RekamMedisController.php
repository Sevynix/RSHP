<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use App\Models\DetailRekamMedis;
use App\Models\TemuDokter;
use App\Models\KodeTindakanTerapi;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RekamMedisController extends Controller
{
    public function index()
    {
        // Check authorization
        if (session('user_role') != 2) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        // Get all medical records with related data
        $records = DB::table('rekam_medis as rm')
            ->join('temu_dokter as td', 'rm.idreservasi_dokter', '=', 'td.idreservasi_dokter')
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->join('pemilik as pm', 'p.idpemilik', '=', 'pm.idpemilik')
            ->join('user as u', 'pm.iduser', '=', 'u.iduser')
            ->join('ras_hewan as rh', 'p.idras_hewan', '=', 'rh.idras_hewan')
            ->join('jenis_hewan as jh', 'rh.idjenis_hewan', '=', 'jh.idjenis_hewan')
            ->leftJoin('role_user as ru', 'rm.dokter_pemeriksa', '=', 'ru.idrole_user')
            ->leftJoin('user as dok', 'ru.iduser', '=', 'dok.iduser')
            ->select(
                'rm.idrekam_medis',
                'rm.idreservasi_dokter',
                'rm.created_at',
                'rm.diagnosa',
                'rm.anamnesa',
                'rm.temuan_klinis',
                'rm.dokter_pemeriksa',
                'p.nama as nama_pet',
                'jh.nama_jenis_hewan as jenis_hewan',
                'rh.nama_ras as ras',
                'u.nama as nama_pemilik',
                'dok.nama as nama_dokter_pemeriksa',
                'td.waktu_daftar',
                'td.no_urut'
            )
            ->orderBy('rm.created_at', 'desc')
            ->paginate(10);

        return view('dokter.rekammedis.index', compact('records'));
    }

    public function create()
    {
        // Check authorization
        if (session('user_role') != 2) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        // Get active reservations (status = 1, menunggu)
        $reservations = DB::table('temu_dokter as td')
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->join('pemilik as pm', 'p.idpemilik', '=', 'pm.idpemilik')
            ->join('user as u', 'pm.iduser', '=', 'u.iduser')
            ->join('ras_hewan as rh', 'p.idras_hewan', '=', 'rh.idras_hewan')
            ->join('jenis_hewan as jh', 'rh.idjenis_hewan', '=', 'jh.idjenis_hewan')
            ->leftJoin('rekam_medis as rm', 'td.idreservasi_dokter', '=', 'rm.idreservasi_dokter')
            ->where('td.status', 1)
            ->whereNull('rm.idrekam_medis') // Only reservations without medical records
            ->select(
                'td.idreservasi_dokter',
                'td.waktu_daftar',
                'td.no_urut',
                'p.nama as nama_pet',
                'jh.nama_jenis_hewan as jenis_hewan',
                'rh.nama_ras as ras',
                'u.nama as nama_pemilik'
            )
            ->orderBy('td.waktu_daftar', 'desc')
            ->orderBy('td.no_urut', 'asc')
            ->get();

        // Get all doctors
        $dokters = DB::table('user as u')
            ->join('role_user as ru', 'u.iduser', '=', 'ru.iduser')
            ->where('ru.idrole', 2) // Role dokter
            ->select('u.iduser', 'u.nama')
            ->get();

        return view('dokter.rekammedis.create', compact('reservations', 'dokters'));
    }

    public function store(Request $request)
    {
        // Check authorization
        if (session('user_role') != 2) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $validated = $request->validate([
            'idreservasi_dokter' => 'required|exists:temu_dokter,idreservasi_dokter',
            'anamnesa' => 'required|string',
            'temuan_klinis' => 'required|string',
            'diagnosa' => 'required|string',
            'dokter_pemeriksa' => 'required|exists:user,iduser'
        ]);

        try {
            DB::beginTransaction();

            // Create medical record
            $rekamMedis = RekamMedis::create($validated);

            // Update reservation status to completed (2)
            DB::table('temu_dokter')
                ->where('idreservasi_dokter', $validated['idreservasi_dokter'])
                ->update(['status' => 2]);

            DB::commit();

            return redirect()->route('dokter.rekammedis.index')
                ->with('success', 'Rekam medis berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan rekam medis: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        // Check authorization
        if (session('user_role') != 2) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        // Get medical record with all related data
        $record = DB::table('rekam_medis as rm')
            ->join('temu_dokter as td', 'rm.idreservasi_dokter', '=', 'td.idreservasi_dokter')
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->join('pemilik as pm', 'p.idpemilik', '=', 'pm.idpemilik')
            ->join('user as u', 'pm.iduser', '=', 'u.iduser')
            ->join('ras_hewan as rh', 'p.idras_hewan', '=', 'rh.idras_hewan')
            ->join('jenis_hewan as jh', 'rh.idjenis_hewan', '=', 'jh.idjenis_hewan')
            ->leftJoin('role_user as ru', 'rm.dokter_pemeriksa', '=', 'ru.idrole_user')
            ->leftJoin('user as dok', 'ru.iduser', '=', 'dok.iduser')
            ->where('rm.idrekam_medis', $id)
            ->select(
                'rm.*',
                'p.nama as nama_pet',
                'p.jenis_kelamin as jenis_kelamin_pet',
                'p.tanggal_lahir as tanggal_lahir_pet',
                'jh.nama_jenis_hewan as jenis_hewan',
                'rh.nama_ras as ras',
                'u.nama as nama_pemilik',
                'u.email as email_pemilik',
                'pm.alamat as alamat_pemilik',
                'pm.no_wa as no_wa_pemilik',
                'dok.nama as nama_dokter_pemeriksa',
                'td.waktu_daftar',
                'td.no_urut'
            )
            ->first();

        if (!$record) {
            return redirect()->route('dokter.rekammedis.index')
                ->with('error', 'Rekam medis tidak ditemukan');
        }

        // Get details
        $details = DB::table('detail_rekam_medis as drm')
            ->join('kode_tindakan_terapi as ktt', 'drm.idkode_tindakan_terapi', '=', 'ktt.idkode_tindakan_terapi')
            ->join('kategori as k', 'ktt.idkategori', '=', 'k.idkategori')
            ->where('drm.idrekam_medis', $id)
            ->select(
                'drm.*',
                'ktt.kode',
                'ktt.deskripsi_tindakan_terapi',
                'k.nama_kategori'
            )
            ->get();

        return view('dokter.rekammedis.show', compact('record', 'details'));
    }

    public function edit($id)
    {
        // Check authorization
        if (session('user_role') != 2) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $record = RekamMedis::with('temuDokter.pet')->findOrFail($id);

        // Get active reservations
        $reservations = DB::table('temu_dokter as td')
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->join('pemilik as pm', 'p.idpemilik', '=', 'pm.idpemilik')
            ->join('user as u', 'pm.iduser', '=', 'u.iduser')
            ->join('ras_hewan as rh', 'p.idras_hewan', '=', 'rh.idras_hewan')
            ->join('jenis_hewan as jh', 'rh.idjenis_hewan', '=', 'jh.idjenis_hewan')
            ->where(function($query) use ($record) {
                $query->where('td.status', 1)
                      ->orWhere('td.idreservasi_dokter', $record->idreservasi_dokter);
            })
            ->select(
                'td.idreservasi_dokter',
                'td.waktu_daftar',
                'td.no_urut',
                'p.nama as nama_pet',
                'jh.nama_jenis_hewan as jenis_hewan',
                'rh.nama_ras as ras',
                'u.nama as nama_pemilik'
            )
            ->orderBy('td.waktu_daftar', 'desc')
            ->get();

        // Get all doctors
        $dokters = DB::table('user as u')
            ->join('role_user as ru', 'u.iduser', '=', 'ru.iduser')
            ->where('ru.idrole', 2)
            ->select('u.iduser', 'u.nama')
            ->get();

        return view('dokter.rekammedis.edit', compact('record', 'reservations', 'dokters'));
    }

    public function update(Request $request, $id)
    {
        // Check authorization
        if (session('user_role') != 2) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $validated = $request->validate([
            'idreservasi_dokter' => 'required|exists:temu_dokter,idreservasi_dokter',
            'anamnesa' => 'required|string',
            'temuan_klinis' => 'required|string',
            'diagnosa' => 'required|string',
            'dokter_pemeriksa' => 'required|exists:user,iduser'
        ]);

        try {
            $rekamMedis = RekamMedis::findOrFail($id);
            $rekamMedis->update($validated);

            return redirect()->route('dokter.rekammedis.index')
                ->with('success', 'Rekam medis berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengupdate rekam medis: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        // Check authorization
        if (session('user_role') != 2) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        try {
            DB::beginTransaction();

            // Delete details first
            DB::table('detail_rekam_medis')
                ->where('idrekam_medis', $id)
                ->delete();

            // Delete medical record
            $rekamMedis = RekamMedis::findOrFail($id);
            
            // Update reservation status back to pending (1)
            DB::table('temu_dokter')
                ->where('idreservasi_dokter', $rekamMedis->idreservasi_dokter)
                ->update(['status' => 1]);

            $rekamMedis->delete();

            DB::commit();

            return redirect()->route('dokter.rekammedis.index')
                ->with('success', 'Rekam medis berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus rekam medis: ' . $e->getMessage());
        }
    }

    // Add detail methods
    public function addDetail($id)
    {
        // Check authorization
        if (session('user_role') != 2) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $record = RekamMedis::findOrFail($id);
        
        $categories = Kategori::all();
        $treatmentCodes = KodeTindakanTerapi::with('kategori')->get();

        return view('dokter.rekammedis.adddetail', compact('record', 'categories', 'treatmentCodes'));
    }

    public function storeDetail(Request $request, $id)
    {
        // Check authorization
        if (session('user_role') != 2) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $validated = $request->validate([
            'idkode_tindakan_terapi' => 'required|exists:kode_tindakan_terapi,idkode_tindakan_terapi',
            'detail' => 'nullable|string'
        ]);

        try {
            DetailRekamMedis::create([
                'idrekam_medis' => $id,
                'idkode_tindakan_terapi' => $validated['idkode_tindakan_terapi'],
                'detail' => $validated['detail'] ?? null
            ]);

            return redirect()->route('dokter.rekammedis.show', $id)
                ->with('success', 'Detail rekam medis berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan detail: ' . $e->getMessage())
                ->withInput();
        }
    }
}
