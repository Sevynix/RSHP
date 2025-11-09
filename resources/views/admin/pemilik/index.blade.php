@extends('layouts.admin')

@section('title', 'Manajemen Pemilik')
@section('page-title', 'Manajemen Pemilik')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fs-4 mb-0">Manajemen Pemilik</h3>
    <a href="{{ route('admin.pemilik.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle me-2"></i>Tambah Pemilik
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table rounded shadow-sm table-hover table-custom-header">
            <thead>
                <tr>
                    <th>ID Pemilik</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. WhatsApp</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pemiliks as $pemilik)
                <tr>
                    <td>{{ $pemilik->idpemilik }}</td>
                    <td>{{ $pemilik->user->nama }}</td>
                    <td>{{ $pemilik->user->email }}</td>
                    <td>
                        @if(!empty($pemilik->no_wa))
                            <a href="https://wa.me/+62{{ substr($pemilik->no_wa, 1) }}" 
                               target="_blank" class="text-success">
                                <i class="fab fa-whatsapp me-1"></i>{{ $pemilik->no_wa }}
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if(!empty($pemilik->alamat))
                            <span title="{{ $pemilik->alamat }}">
                                {{ strlen($pemilik->alamat) > 30 ? substr($pemilik->alamat, 0, 30) . '...' : $pemilik->alamat }}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.pemilik.destroy', $pemilik->idpemilik) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin menghapus pemilik ini? Relasi user tidak akan terhapus.');">
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
                    <td colspan="6" class="text-center p-4">Tidak ada data pemilik.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
