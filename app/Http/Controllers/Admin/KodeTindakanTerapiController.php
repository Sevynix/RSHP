<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KodeTindakanTerapi;
use App\Models\Kategori;
use App\Models\KategoriKlinis;
use Illuminate\Http\Request;

class KodeTindakanTerapiController extends Controller
{
    public function index()
    {
        $listData = KodeTindakanTerapi::with(['kategori', 'kategoriKlinis'])
            ->orderBy('idkode_tindakan_terapi', 'asc')
            ->get();
        return view('admin.kodetindakan.index', compact('listData'));
    }

    public function create()
    {
        $kategoriList = Kategori::orderBy('nama_kategori', 'asc')->get();
        $kategoriKlinisList = KategoriKlinis::orderBy('nama_kategori_klinis', 'asc')->get();
        return view('admin.kodetindakan.form', compact('kategoriList', 'kategoriKlinisList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:50',
            'deskripsi_tindakan_terapi' => 'required|string',
            'idkategori' => 'required|exists:kategori,idkategori',
            'idkategori_klinis' => 'required|exists:kategori_klinis,idkategori_klinis',
        ]);

        KodeTindakanTerapi::create($request->all());

        return redirect()->route('admin.kodetindakanterapi.index')->with('success', 'Kode Tindakan Terapi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kodeTindakan = KodeTindakanTerapi::with(['kategori', 'kategoriKlinis'])->findOrFail($id);
        $kategoriList = Kategori::orderBy('nama_kategori', 'asc')->get();
        $kategoriKlinisList = KategoriKlinis::orderBy('nama_kategori_klinis', 'asc')->get();
        return view('admin.kodetindakan.form', compact('kodeTindakan', 'kategoriList', 'kategoriKlinisList'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:50',
            'deskripsi_tindakan_terapi' => 'required|string',
            'idkategori' => 'required|exists:kategori,idkategori',
            'idkategori_klinis' => 'required|exists:kategori_klinis,idkategori_klinis',
        ]);

        $kodeTindakan = KodeTindakanTerapi::findOrFail($id);
        $kodeTindakan->update($request->all());

        return redirect()->route('admin.kodetindakanterapi.index')->with('success', 'Kode Tindakan Terapi berhasil diupdate!');
    }

    public function destroy($id)
    {
        $kodeTindakan = KodeTindakanTerapi::findOrFail($id);
        $kodeTindakan->delete();

        return redirect()->route('admin.kodetindakanterapi.index')->with('success', 'Kode Tindakan Terapi berhasil dihapus!');
    }
}
