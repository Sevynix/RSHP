@extends('layouts.admin')

@section('title', 'Manajemen Dokter')
@section('page-title', 'Manajemen Dokter')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fs-4 mb-0">Manajemen Dokter</h3>
    <a href="{{ route('admin.dokter.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle me-2"></i>Tambah Dokter
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table rounded shadow-sm table-hover table-custom-header">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No HP</th>
                    <th>Bidang</th>
                    <th>Jenis Kelamin</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dokters as $dokter)
                <tr>
                    <td>{{ $dokter->id_dokter }}</td>
                    <td>{{ $dokter->user ? $dokter->user->nama : '-' }}</td>
                    <td>{{ $dokter->user ? $dokter->user->email : '-' }}</td>
                    <td>{{ $dokter->no_hp }}</td>
                    <td>{{ $dokter->bidang_dokter }}</td>
                    <td>{{ $dokter->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    <td>
                        <a href="{{ route('admin.dokter.show', $dokter->id_dokter) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye me-1"></i>Detail
                        </a>
                        <a href="{{ route('admin.dokter.edit', $dokter->id_dokter) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <form action="{{ route('admin.dokter.destroy', $dokter->id_dokter) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokter {{ $dokter->user ? $dokter->user->nama : 'ini' }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data dokter.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
