@extends('layouts.resepsionis')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fs-4 mb-0">Data Pet</h3>
        <a href="{{ route('resepsionis.pet.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>Tambah Pet
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
            <div class="table-responsive">
                <table class="table rounded shadow-sm table-hover table-custom-header">
                    <thead>
                        <tr>
                            <th>ID Pet</th>
                            <th>Nama Pet</th>
                            <th>Jenis Kelamin</th>
                            <th>Pemilik</th>
                            <th>Ras Hewan</th>
                            <th>Jenis Hewan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pets as $pet)
                        <tr>
                            <td>{{ $pet->idpet }}</td>
                            <td>{{ $pet->nama }}</td>
                            <td>
                                <span class="badge bg-{{ $pet->jenis_kelamin == 'J' ? 'primary' : 'info' }}">
                                    {{ $pet->jenis_kelamin == 'J' ? 'Jantan' : 'Betina' }}
                                </span>
                            </td>
                            <td>{{ $pet->nama_pemilik }}</td>
                            <td>{{ $pet->nama_ras ?? '-' }}</td>
                            <td>{{ $pet->nama_jenis_hewan }}</td>
                            <td>
                                <a href="{{ route('resepsionis.pet.edit', $pet->idpet) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                                <form action="{{ route('resepsionis.pet.destroy', $pet->idpet) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pet ini? Semua riwayat temu dokter akan ikut terhapus!');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash me-1"></i>Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center p-4">Tidak ada data pet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
