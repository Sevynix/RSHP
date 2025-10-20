<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriKlinis;
use Illuminate\Http\Request;

class KategoriKlinisController extends Controller
{
    public function index()
    {
        $kategoriKlinisList = KategoriKlinis::orderBy('idkategori_klinis', 'asc')->get();
        return view('admin.kategoriklinis.index', compact('kategoriKlinisList'));
    }

    public function create()
    {
        return view('admin.kategoriklinis.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori_klinis' => 'required|string|max:255',
        ]);

        KategoriKlinis::create([
            'nama_kategori_klinis' => $request->nama_kategori_klinis,
        ]);

        return redirect()->route('admin.kategoriklinis.index')->with('success', 'Kategori Klinis berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kategoriKlinis = KategoriKlinis::findOrFail($id);
        return view('admin.kategoriklinis.form', compact('kategoriKlinis'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori_klinis' => 'required|string|max:255',
        ]);

        $kategoriKlinis = KategoriKlinis::findOrFail($id);
        $kategoriKlinis->update([
            'nama_kategori_klinis' => $request->nama_kategori_klinis,
        ]);

        return redirect()->route('admin.kategoriklinis.index')->with('success', 'Kategori Klinis berhasil diupdate!');
    }

    public function destroy($id)
    {
        $kategoriKlinis = KategoriKlinis::findOrFail($id);
        $kategoriKlinis->delete();

        return redirect()->route('admin.kategoriklinis.index')->with('success', 'Kategori Klinis berhasil dihapus!');
    }
}
