<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisHewan;
use Illuminate\Http\Request;

class JenisHewanController extends Controller
{
    public function index()
    {
        $jenisHewanList = JenisHewan::withCount('rasHewan')
            ->orderBy('nama_jenis_hewan', 'asc')
            ->get();
        return view('admin.jenis-hewan.index', compact('jenisHewanList'));
    }

    public function create()
    {
        return view('admin.jenis-hewan.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis_hewan' => 'required|string|max:255',
        ]);

        JenisHewan::create([
            'nama_jenis_hewan' => $request->nama_jenis_hewan,
        ]);

        return redirect()->route('admin.jenis-hewan.index')->with('success', 'Jenis Hewan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $jenisHewan = JenisHewan::findOrFail($id);
        return view('admin.jenis-hewan.form', compact('jenisHewan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_jenis_hewan' => 'required|string|max:255',
        ]);

        $jenisHewan = JenisHewan::findOrFail($id);
        $jenisHewan->update([
            'nama_jenis_hewan' => $request->nama_jenis_hewan,
        ]);

        return redirect()->route('admin.jenis-hewan.index')->with('success', 'Jenis Hewan berhasil diupdate!');
    }

    public function destroy($id)
    {
        $jenisHewan = JenisHewan::findOrFail($id);
        $jenisHewan->delete();

        return redirect()->route('admin.jenis-hewan.index')->with('success', 'Jenis Hewan berhasil dihapus!');
    }
}
