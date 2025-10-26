<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RasHewan;
use App\Models\JenisHewan;
use Illuminate\Http\Request;

class RasHewanController extends Controller
{
    public function index()
    {
        $rasHewanList = RasHewan::with('jenisHewan')
            ->orderBy('nama_ras', 'asc')
            ->get();
        return view('admin.rashewan.index', compact('rasHewanList'));
    }

    public function create()
    {
        $jenisHewanList = JenisHewan::orderBy('nama_jenis_hewan', 'asc')->get();
        return view('admin.rashewan.form', compact('jenisHewanList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ras' => 'required|string|max:255',
            'idjenis_hewan' => 'required|exists:jenis_hewan,idjenis_hewan',
        ]);

        RasHewan::create([
            'nama_ras' => $request->nama_ras,
            'idjenis_hewan' => $request->idjenis_hewan,
        ]);

        return redirect()->route('admin.rashewan.index')->with('success', 'Ras Hewan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $rasHewan = RasHewan::with('jenisHewan')->findOrFail($id);
        $jenisHewanList = JenisHewan::orderBy('nama_jenis_hewan', 'asc')->get();
        return view('admin.rashewan.form', compact('rasHewan', 'jenisHewanList'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_ras' => 'required|string|max:255',
            'idjenis_hewan' => 'required|exists:jenis_hewan,idjenis_hewan',
        ]);

        $rasHewan = RasHewan::findOrFail($id);
        $rasHewan->update([
            'nama_ras' => $request->nama_ras,
            'idjenis_hewan' => $request->idjenis_hewan,
        ]);

        return redirect()->route('admin.rashewan.index')->with('success', 'Ras Hewan berhasil diupdate!');
    }

    public function destroy($id)
    {
        $rasHewan = RasHewan::findOrFail($id);
        $rasHewan->delete();

        return redirect()->route('admin.rashewan.index')->with('success', 'Ras Hewan berhasil dihapus!');
    }
}
