<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\Pemilik;
use App\Models\RasHewan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PetController extends Controller
{
    public function index()
    {
        // Only allow admin (role 1)
        if (session('user_role') != 1) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $pets = Pet::with(['pemilik.user', 'rasHewan.jenisHewan'])
            ->orderBy('idpet', 'desc')
            ->get();

        return view('admin.pet.index', compact('pets'));
    }

    public function create()
    {
        // Only allow admin (role 1)
        if (session('user_role') != 1) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $pemiliks = Pemilik::with('user')->whereHas('user')->get();
        $rases = RasHewan::with('jenisHewan')->orderBy('nama_ras')->get();

        return view('admin.pet.form', compact('pemiliks', 'rases'));
    }

    public function store(Request $request)
    {
        // Only allow admin (role 1)
        if (session('user_role') != 1) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:J,B',
            'warna_tanda' => 'nullable|string|max:100',
            'idpemilik' => 'required|exists:pemilik,idpemilik',
            'idras_hewan' => 'required|exists:ras_hewan,idras_hewan',
        ], [
            'nama.required' => 'Nama pet harus diisi',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid',
            'idpemilik.required' => 'Pemilik harus dipilih',
            'idpemilik.exists' => 'Pemilik tidak ditemukan',
            'idras_hewan.required' => 'Ras hewan harus dipilih',
            'idras_hewan.exists' => 'Ras hewan tidak ditemukan',
        ]);

        Pet::create($validated);

        return redirect()->route('admin.pet.index')
            ->with('success', 'Pet berhasil ditambahkan');
    }

    public function edit($id)
    {
        if (session('user_role') != 1) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $pet = Pet::findOrFail($id);
        $pemiliks = Pemilik::with('user')->whereHas('user')->get();
        $rases = RasHewan::with('jenisHewan')->orderBy('nama_ras')->get();

        return view('admin.pet.form', compact('pet', 'pemiliks', 'rases'));
    }

    public function update(Request $request, $id)
    {
        // Only allow admin (role 1)
        if (session('user_role') != 1) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        $pet = Pet::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:J,B',
            'warna_tanda' => 'nullable|string|max:100',
            'idpemilik' => 'required|exists:pemilik,idpemilik',
            'idras_hewan' => 'required|exists:ras_hewan,idras_hewan',
        ], [
            'nama.required' => 'Nama pet harus diisi',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid',
            'idpemilik.required' => 'Pemilik harus dipilih',
            'idpemilik.exists' => 'Pemilik tidak ditemukan',
            'idras_hewan.required' => 'Ras hewan harus dipilih',
            'idras_hewan.exists' => 'Ras hewan tidak ditemukan',
        ]);

        $pet->update($validated);

        return redirect()->route('admin.pet.index')
            ->with('success', 'Pet berhasil diupdate');
    }

    public function destroy($id)
    {
        // Only allow admin (role 1)
        if (session('user_role') != 1) {
            return redirect()->route('login')->with('error', 'Unauthorized access');
        }

        try {
            $pet = Pet::findOrFail($id);
            $petName = $pet->nama;

            $hasAppointments = $pet->temuDokter()->count() > 0;

            $pet->delete();

            if ($hasAppointments) {
                return redirect()->route('admin.pet.index')
                    ->with('success', "Pet '{$petName}' dan semua rekam medisnya berhasil dihapus");
            } else {
                return redirect()->route('admin.pet.index')
                    ->with('success', "Pet '{$petName}' berhasil dihapus");
            }
        } catch (\Exception $e) {
            Log::error('Error deleting pet: ' . $e->getMessage());
            
            return redirect()->route('admin.pet.index')
                ->with('error', 'Gagal menghapus pet: ' . $e->getMessage());
        }
    }
}
