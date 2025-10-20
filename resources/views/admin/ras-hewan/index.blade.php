@extends('layouts.admin')

@section('title', 'Manajemen Ras Hewan')
@section('page-title', 'Manajemen Ras Hewan')

@push('styles')
<style>
.jenis-group-header {
    background-color: #f8f9fa !important;
    border-left: 4px solid #0d6efd;
    font-weight: bold;
    vertical-align: middle;
}
.add-ras-row {
    background-color: #f1f3f4 !important;
}
.add-ras-row td {
    border-top: 2px solid #dee2e6 !important;
}
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fs-4 mb-0">Manajemen Ras Hewan</h3>
    <a href="{{ route('admin.ras-hewan.create') }}" class="btn btn-primary">
        <i class="fas fa-plus-circle me-2"></i>Tambah Ras Hewan
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
                    <th>Jenis Hewan</th>
                    <th>Nama Ras</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $groupedRas = [];
                    foreach($rasHewanList as $ras) {
                        $jenisNama = $ras->jenisHewan->nama_jenis_hewan ?? 'Tidak Diketahui';
                        $groupedRas[$jenisNama][] = $ras;
                    }
                @endphp
                
                @forelse($groupedRas as $jenisNama => $rasList)
                    @php
                        $firstRas = $rasList[0];
                    @endphp
                    @foreach($rasList as $index => $ras)
                    <tr>
                        @if($index === 0)
                            <td class="jenis-group-header" rowspan="{{ count($rasList) + 1 }}">
                                <i class="fas fa-paw me-2"></i>{{ $jenisNama }}
                            </td>
                        @endif
                        <td>{{ $ras->nama_ras }}</td>
                        <td>
                            <a href="{{ route('admin.ras-hewan.edit', $ras->idras_hewan) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.ras-hewan.destroy', $ras->idras_hewan) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus ras hewan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    <tr class="add-ras-row">
                        <td colspan="2" class="text-center">
                            <a href="{{ route('admin.ras-hewan.create', ['jenis' => $firstRas->idjenis_hewan]) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-plus me-1"></i>Tambah Ras untuk {{ $jenisNama }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center p-4">Tidak ada data ras hewan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
