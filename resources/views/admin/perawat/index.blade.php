@extends('layouts.admin')

@section('title', 'Manajemen Perawat')
@section('page-title', 'Manajemen Perawat')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fs-4 mb-0">Manajemen Perawat</h3>
    <a href="{{ route('admin.perawat.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle me-2"></i>Tambah Perawat
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
                    <th>Pendidikan</th>
                    <th>Jenis Kelamin</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($perawats as $perawat)
                <tr>
                    <td>{{ $perawat->id_perawat }}</td>
                    <td>{{ $perawat->user->nama }}</td>
                    <td>{{ $perawat->user->email }}</td>
                    <td>{{ $perawat->no_hp }}</td>
                    <td>{{ $perawat->pendidikan }}</td>
                    <td>{{ $perawat->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    <td>
                        <a href="{{ route('admin.perawat.show', $perawat->id_perawat) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye me-1"></i>Detail
                        </a>
                        <a href="{{ route('admin.perawat.edit', $perawat->id_perawat) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <form action="{{ route('admin.perawat.destroy', $perawat->id_perawat) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus perawat {{ $perawat->user->nama }}? Tindakan ini tidak dapat dibatalkan!');">
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
                    <td colspan="7" class="text-center">Tidak ada data perawat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
