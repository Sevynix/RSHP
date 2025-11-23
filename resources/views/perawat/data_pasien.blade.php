@extends('layouts.main')

@section('title', 'Data Pasien')
@section('page-title', 'Data Pasien')
@section('user-role', 'Perawat')

@section('sidebar-menu')
    <li class="{{ request()->routeIs('perawat.dashboard') ? 'active' : '' }}">
        <a href="{{ route('perawat.dashboard') }}">
            <i class="fas fa-tachometer-alt"></i>
            <span class="nav-text">Dashboard</span>
        </a>
    </li>
    <li class="{{ request()->routeIs('perawat.data-pasien') ? 'active' : '' }}">
        <a href="{{ route('perawat.data-pasien') }}">
            <i class="fas fa-paw"></i>
            <span class="nav-text">Data Pasien</span>
        </a>
    </li>
    <li class="{{ request()->routeIs('perawat.rekammedis.*') ? 'active' : '' }}">
        <a href="{{ route('perawat.rekammedis.index') }}">
            <i class="fas fa-file-medical"></i>
            <span class="nav-text">Rekam Medis</span>
        </a>
    </li>
    <li class="{{ request()->routeIs('perawat.profil*') ? 'active' : '' }}">
        <a href="{{ route('perawat.profil') }}">
            <i class="fas fa-user-circle"></i>
            <span class="nav-text">Profil</span>
        </a>
    </li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Data Pasien</h1>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($pasien->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama Hewan</th>
                                <th>Jenis</th>
                                <th>Ras</th>
                                <th>Pemilik</th>
                                <th>Kontak</th>
                                <th>Info</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pasien as $pet)
                                <tr>
                                    <td>
                                        <strong>{{ $pet->nama }}</strong><br>
                                        <small class="text-muted">{{ $pet->jenis_kelamin == 'J' ? 'Jantan' : 'Betina' }}</small>
                                    </td>
                                    <td>{{ $pet->jenis_hewan }}</td>
                                    <td>{{ $pet->ras_hewan }}</td>
                                    <td>
                                        <strong>{{ $pet->nama_pemilik }}</strong><br>
                                        <small class="text-muted">{{ $pet->email_pemilik }}</small>
                                    </td>
                                    <td>
                                        @if($pet->no_wa)
                                            <a href="https://wa.me/{{ $pet->no_wa }}" target="_blank" class="btn btn-sm btn-success">
                                                <i class="bi bi-whatsapp"></i> {{ $pet->no_wa }}
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-muted small">
                                            <div><strong>Tgl Lahir:</strong> {{ $pet->tanggal_lahir ? \Carbon\Carbon::parse($pet->tanggal_lahir)->format('d/m/Y') : '-' }}</div>
                                            <div><strong>Warna/Tanda:</strong> {{ $pet->warna_tanda ?? '-' }}</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $pasien->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                    <p class="text-muted mt-3">Belum ada data pasien</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
