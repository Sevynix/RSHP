<?php

namespace App\Http\Controllers\Resepsionis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetController extends Controller
{
    public function index()
    {
        // Check authorization
        if (session('user_role') != 4) {
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        $pets = DB::table('pet')
            ->join('pemilik', 'pet.idpemilik', '=', 'pemilik.idpemilik')
            ->join('user', 'pemilik.iduser', '=', 'user.iduser')
            ->join('ras_hewan', 'pet.idras_hewan', '=', 'ras_hewan.idras_hewan')
            ->join('jenis_hewan', 'ras_hewan.idjenis_hewan', '=', 'jenis_hewan.idjenis_hewan')
            ->select(
                'pet.*',
                'user.nama as nama_pemilik',
                'jenis_hewan.nama_jenis_hewan',
                'ras_hewan.nama_ras'
            )
            ->get();

        return view('resepsionis.datapet', compact('pets'));
    }

    public function create()
    {
        // Check authorization
        if (session('user_role') != 4) {
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        $pemilik = DB::table('pemilik')
            ->join('user', 'pemilik.iduser', '=', 'user.iduser')
            ->select('pemilik.idpemilik', 'user.nama')
            ->get();
        $rasHewan = DB::table('ras_hewan')->get();

        return view('resepsionis.form_pet', compact('pemilik', 'rasHewan'));
    }

    public function store(Request $request)
    {
        // Check authorization
        if (session('user_role') != 4) {
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        $validated = $request->validate([
            'idpemilik' => 'required|exists:pemilik,idpemilik',
            'nama' => 'required',
            'idras_hewan' => 'required|exists:ras_hewan,idras_hewan',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:J,B',
            'warna_tanda' => 'nullable'
        ]);

        try {
            DB::table('pet')->insert([
                'idpemilik' => $validated['idpemilik'],
                'nama' => $validated['nama'],
                'idras_hewan' => $validated['idras_hewan'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'warna_tanda' => $validated['warna_tanda']
            ]);

            return redirect()->route('resepsionis.pet.index')->with('success', 'Data pet berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan data: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        // Check authorization
        if (session('user_role') != 4) {
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        $pet = DB::table('pet')->where('idpet', $id)->first();
        
        if (!$pet) {
            return redirect()->route('resepsionis.pet.index')->with('error', 'Data pet tidak ditemukan.');
        }

        $pemilik = DB::table('pemilik')
            ->join('user', 'pemilik.iduser', '=', 'user.iduser')
            ->select('pemilik.idpemilik', 'user.nama')
            ->get();
        $rasHewan = DB::table('ras_hewan')->get();

        return view('resepsionis.form_pet', compact('pet', 'pemilik', 'rasHewan'));
    }

    public function update(Request $request, $id)
    {
        // Check authorization
        if (session('user_role') != 4) {
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        $validated = $request->validate([
            'idpemilik' => 'required|exists:pemilik,idpemilik',
            'nama' => 'required',
            'idras_hewan' => 'required|exists:ras_hewan,idras_hewan',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:J,B',
            'warna_tanda' => 'nullable'
        ]);

        try {
            DB::table('pet')->where('idpet', $id)->update([
                'idpemilik' => $validated['idpemilik'],
                'nama' => $validated['nama'],
                'idras_hewan' => $validated['idras_hewan'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'warna_tanda' => $validated['warna_tanda']
            ]);

            return redirect()->route('resepsionis.pet.index')->with('success', 'Data pet berhasil diupdate.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengupdate data: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        // Check authorization
        if (session('user_role') != 4) {
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        try {
            DB::table('pet')->where('idpet', $id)->delete();
            return redirect()->route('resepsionis.pet.index')->with('success', 'Data pet berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
