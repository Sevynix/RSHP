@extends('layouts.resepsionis')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fs-4 mb-0">Data Pemilik</h3>
        <a href="{{ route('resepsionis.pemilik.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>Tambah Pemilik
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
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
                        @forelse($pemilik as $p)
                        <tr>
                            <td>{{ $p->idpemilik }}</td>
                            <td>{{ $p->nama }}</td>
                            <td>{{ $p->email }}</td>
                            <td>
                                @if(!empty($p->no_wa))
                                    <a href="https://wa.me/+62{{ substr($p->no_wa, 1) }}" 
                                       target="_blank" class="text-success">
                                        <i class="fab fa-whatsapp me-1"></i>{{ $p->no_wa }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if(!empty($p->alamat))
                                    <span title="{{ $p->alamat }}">
                                        {{ strlen($p->alamat) > 30 ? substr($p->alamat, 0, 30) . '...' : $p->alamat }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('resepsionis.pemilik.destroy', $p->idpemilik) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin menghapus pemilik ini? Relasi user tidak akan terhapus.');">
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
    </div>
</div>
@endsection
