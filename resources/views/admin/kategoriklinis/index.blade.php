@extends('layouts.admin')

@section('title', 'Manajemen Kategori Klinis')
@section('page-title', 'Manajemen Kategori Klinis')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fs-4 mb-0">Manajemen Kategori Klinis</h3>
    <a href="{{ route('admin.kategoriklinis.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle me-2"></i>Tambah Kategori Klinis
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table rounded shadow-sm table-hover table-custom-header">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Kategori Klinis</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategoriKlinisList as $kategori)
                <tr>
                    <td>{{ $kategori->idkategori_klinis }}</td>
                    <td>{{ $kategori->nama_kategori_klinis }}</td>
                    <td>
                        <a href="{{ route('admin.kategoriklinis.edit', $kategori->idkategori_klinis) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.kategoriklinis.destroy', $kategori->idkategori_klinis) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kategori klinis ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center p-4">Tidak ada data kategori klinis.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
