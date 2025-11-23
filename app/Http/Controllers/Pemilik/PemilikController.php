<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Pemilik;
use App\Models\Pet;
use App\Models\TemuDokter;
use App\Models\RekamMedis;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PemilikController extends Controller
{
    /**
     * Display dashboard for pemilik
     */
    public function dashboard()
    {
        if (session('user_role') != 5) {
            abort(403);
        }

        $user = Auth::user();
        $pemilik = $user->pemilik;

        if (!$pemilik) {
            return redirect()->route('home')->with('error', 'Data pemilik tidak ditemukan');
        }

        $totalPets = Pet::where('idpemilik', $pemilik->idpemilik)->count();
        
        $totalJadwal = TemuDokter::whereHas('pet', function($query) use ($pemilik) {
            $query->where('idpemilik', $pemilik->idpemilik);
        })->count();
        
        $upcomingJadwal = TemuDokter::whereHas('pet', function($query) use ($pemilik) {
            $query->where('idpemilik', $pemilik->idpemilik);
        })->whereDate('waktu_daftar', '>=', now()->toDateString())
        ->count();

        $totalMedicalRecords = RekamMedis::whereHas('temuDokter.pet', function($query) use ($pemilik) {
            $query->where('idpemilik', $pemilik->idpemilik);
        })->count();

        // Get recent pets (up to 4)
        $recentPets = DB::table('pet')
            ->join('ras_hewan', 'pet.idras_hewan', '=', 'ras_hewan.idras_hewan')
            ->join('jenis_hewan', 'ras_hewan.idjenis_hewan', '=', 'jenis_hewan.idjenis_hewan')
            ->where('pet.idpemilik', $pemilik->idpemilik)
            ->select('pet.nama AS nama', 'ras_hewan.nama_ras', 'jenis_hewan.nama_jenis_hewan')
            ->limit(4)
            ->get()
            ->toArray();

        // Get recent activities (reservations + medical records, up to 5)
        $reservations = DB::table('temu_dokter')
            ->join('pet', 'temu_dokter.idpet', '=', 'pet.idpet')
            ->leftJoin('role_user', 'temu_dokter.idrole_user', '=', 'role_user.idrole_user')
            ->leftJoin('user', 'role_user.iduser', '=', 'user.iduser')
            ->where('pet.idpemilik', $pemilik->idpemilik)
            ->select(
                DB::raw("'reservation' AS type"),
                'temu_dokter.status',
                'pet.nama AS pet_name',
                'user.nama AS doctor_name',
                'temu_dokter.waktu_daftar AS date',
                DB::raw('NULL AS diagnosis')
            )
            ->orderByDesc('temu_dokter.waktu_daftar')
            ->limit(3)
            ->get();

        $medicalRecords = DB::table('rekam_medis')
            ->join('temu_dokter', 'rekam_medis.idreservasi_dokter', '=', 'temu_dokter.idreservasi_dokter')
            ->join('pet', 'temu_dokter.idpet', '=', 'pet.idpet')
            ->leftJoin('user', 'rekam_medis.dokter_pemeriksa', '=', 'user.iduser')
            ->where('pet.idpemilik', $pemilik->idpemilik)
            ->select(
                DB::raw("'medical_record' AS type"),
                DB::raw('NULL AS status'),
                'pet.nama AS pet_name',
                'user.nama AS doctor_name',
                'rekam_medis.created_at AS date',
                'rekam_medis.diagnosa AS diagnosis'
            )
            ->orderByDesc('rekam_medis.created_at')
            ->limit(2)
            ->get();

        $recentActivities = $reservations->merge($medicalRecords)
            ->sortByDesc('date')
            ->take(5)
            ->values()
            ->toArray();

        return view('pemilik.dashboard', compact('pemilik', 'totalPets', 'totalJadwal', 'upcomingJadwal', 'totalMedicalRecords', 'recentPets', 'recentActivities'));
    }

    /**
     * Display jadwal temu dokter
     */
    public function jadwal()
    {
        if (session('user_role') != 5) {
            abort(403);
        }

        $user = Auth::user();
        $pemilik = $user->pemilik;

        if (!$pemilik) {
            return redirect()->route('home')->with('error', 'Data pemilik tidak ditemukan');
        }

        $jadwalQuery = TemuDokter::with(['pet', 'dokter.user'])
            ->whereHas('pet', function($query) use ($pemilik) {
                $query->where('idpemilik', $pemilik->idpemilik);
            });

        // Statistics
        $totalKunjungan = $jadwalQuery->count();
        $menunggu = TemuDokter::whereHas('pet', function($query) use ($pemilik) {
                $query->where('idpemilik', $pemilik->idpemilik);
            })->where('status', 1)->count();
        $selesai = TemuDokter::whereHas('pet', function($query) use ($pemilik) {
                $query->where('idpemilik', $pemilik->idpemilik);
            })->where('status', 2)->count();
        $hewanBerbeda = TemuDokter::whereHas('pet', function($query) use ($pemilik) {
                $query->where('idpemilik', $pemilik->idpemilik);
            })->distinct('idpet')->count();

        $jadwalList = $jadwalQuery->orderBy('waktu_daftar', 'desc')->paginate(10);

        return view('pemilik.jadwal', compact('jadwalList', 'pemilik', 'totalKunjungan', 'menunggu', 'selesai', 'hewanBerbeda'));
    }

    /**
     * Display rekam medis pets
     */
    public function rekamMedis()
    {
        if (session('user_role') != 5) {
            abort(403);
        }

        $user = Auth::user();
        $pemilik = $user->pemilik;

        if (!$pemilik) {
            return redirect()->route('home')->with('error', 'Data pemilik tidak ditemukan');
        }

        $pets = Pet::with([
                'rekamMedis.detailRekamMedis.kodeTindakanTerapi', 
                'rekamMedis.detailRekamMedis.kategori', 
                'rekamMedis.dokterPemeriksa',
                'rekamMedis.temuDokter',
                'jenisHewan', 
                'rasHewan'
            ])
            ->where('idpemilik', $pemilik->idpemilik)
            ->get();

        // Debug: Check if rekamMedis are being loaded
        foreach($pets as $pet) {
            \Log::info("Pet: {$pet->nama} - Rekam Medis Count: " . $pet->rekamMedis->count());
        }

        return view('pemilik.rekam_medis', compact('pets', 'pemilik'));
    }

    /**
     * Display pemilik profile
     */
    public function profil()
    {
        if (session('user_role') != 5) {
            abort(403);
        }

        $user = Auth::user();
        $pemilik = $user->pemilik;

        if (!$pemilik) {
            return redirect()->route('home')->with('error', 'Data pemilik tidak ditemukan');
        }

        return view('pemilik.profil', compact('pemilik', 'user'));
    }

    /**
     * Show edit profil form
     */
    public function editProfil()
    {
        if (session('user_role') != 5) {
            abort(403);
        }

        $user = Auth::user();
        $pemilik = $user->pemilik;

        if (!$pemilik) {
            return redirect()->route('home')->with('error', 'Data pemilik tidak ditemukan');
        }

        return view('pemilik.edit_profil', compact('pemilik', 'user'));
    }

    /**
     * Update pemilik profile
     */
    public function updateProfil(Request $request)
    {
        if (session('user_role') != 5) {
            abort(403);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $pemilik = $user->pemilik;

        if (!$pemilik) {
            return redirect()->route('home')->with('error', 'Data pemilik tidak ditemukan');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:user,username,' . $user->iduser . ',iduser',
            'email' => 'required|email|max:255|unique:user,email,' . $user->iduser . ',iduser',
            'password' => 'nullable|string|min:6',
            'no_wa' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $userData = [
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $user->update($userData);

            $pemilik->update([
                'alamat' => $validated['alamat'] ?? null,
                'no_wa' => $validated['no_wa'] ?? null,
            ]);

            DB::commit();
            return redirect()->route('pemilik.profil')->with('success', 'Profil berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display list of pets owned
     */
    public function pets()
    {
        if (session('user_role') != 5) {
            abort(403);
        }

        $user = Auth::user();
        $pemilik = $user->pemilik;

        if (!$pemilik) {
            return redirect()->route('home')->with('error', 'Data pemilik tidak ditemukan');
        }

        $pets = Pet::with(['jenisHewan', 'rasHewan'])
            ->where('idpemilik', $pemilik->idpemilik)
            ->paginate(10);

        // Count unique jenis hewan
        $totalJenisHewan = Pet::where('pet.idpemilik', $pemilik->idpemilik)
            ->join('ras_hewan', 'pet.idras_hewan', '=', 'ras_hewan.idras_hewan')
            ->distinct()
            ->count('ras_hewan.idjenis_hewan');

        // Count unique ras
        $totalRas = Pet::where('idpemilik', $pemilik->idpemilik)
            ->distinct('idras_hewan')
            ->count('idras_hewan');

        return view('pemilik.pets', compact('pets', 'totalJenisHewan', 'totalRas'));
    }
}
