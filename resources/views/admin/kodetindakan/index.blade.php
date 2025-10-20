@extends('layouts.admin')

@section('title', 'Manajemen Kode Tindakan Terapi')
@section('page-title', 'Manajemen Kode Tindakan Terapi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fs-4 mb-0">Manajemen Kode Tindakan Terapi</h3>
    <a href="{{ route('admin.kodetindakanterapi.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle me-2"></i>Tambah Data
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <table class="table rounded shadow-sm table-hover table-custom-header">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode</th>
                    <th>Deskripsi</th>
                    <th>Kategori</th>
                    <th>Kategori Klinis</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($listData as $data)
                <tr>
                    <td>{{ $data->idkode_tindakan_terapi }}</td>
                    <td><span class="badge bg-primary">{{ $data->kode }}</span></td>
                    <td>{{ $data->deskripsi_tindakan_terapi }}</td>
                    <td>{{ $data->kategori->nama_kategori ?? '-' }}</td>
                    <td>{{ $data->kategoriKlinis->nama_kategori_klinis ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.kodetindakanterapi.edit', $data->idkode_tindakan_terapi) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.kodetindakanterapi.destroy', $data->idkode_tindakan_terapi) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kode tindakan ini?')">
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
                    <td colspan="6" class="text-center p-4">Tidak ada data kode tindakan terapi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
