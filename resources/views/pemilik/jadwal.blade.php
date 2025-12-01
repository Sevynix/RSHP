@extends('layouts.main')

@section('title', 'Jadwal Temu Dokter')
@section('page-title', 'Jadwal Temu Dokter')
@section('user-role', 'Pemilik')

@section('sidebar-menu')
    <li class="{{ request()->routeIs('pemilik.dashboard') ? 'active' : '' }}">
        <a href="{{ route('pemilik.dashboard') }}">
            <i class="fas fa-tachometer-alt"></i>
            <span class="nav-text">Dashboard</span>
        </a>
    </li>
    <li class="{{ request()->routeIs('pemilik.pets') ? 'active' : '' }}">
        <a href="{{ route('pemilik.pets') }}">
            <i class="fas fa-paw"></i>
            <span class="nav-text">Hewan Peliharaan</span>
        </a>
    </li>
    <li class="{{ request()->routeIs('pemilik.jadwal') ? 'active' : '' }}">
        <a href="{{ route('pemilik.jadwal') }}">
            <i class="fas fa-calendar-alt"></i>
            <span class="nav-text">Jadwal Temu</span>
        </a>
    </li>
    <li class="{{ request()->routeIs('pemilik.rekam-medis') ? 'active' : '' }}">
        <a href="{{ route('pemilik.rekam-medis') }}">
            <i class="fas fa-file-medical"></i>
            <span class="nav-text">Rekam Medis</span>
        </a>
    </li>
    <li class="{{ request()->routeIs('pemilik.profil*') ? 'active' : '' }}">
        <a href="{{ route('pemilik.profil') }}">
            <i class="fas fa-user-circle"></i>
            <span class="nav-text">Profil</span>
        </a>
    </li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-calendar-alt me-2"></i>
            Jadwal Kunjungan Saya
        </h1>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold text-dark mb-1">{{ $totalKunjungan }}</h4>
                            <p class="text-secondary mb-0">Total Kunjungan</p>
                        </div>
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold text-dark mb-1">{{ $menunggu }}</h4>
                            <p class="text-secondary mb-0">Menunggu</p>
                        </div>
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold text-dark mb-1">{{ $selesai }}</h4>
                            <p class="text-secondary mb-0">Selesai</p>
                        </div>
                        <div class="stat-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fw-bold text-dark mb-1">{{ $hewanBerbeda }}</h4>
                            <p class="text-secondary mb-0">Hewan Berbeda</p>
                        </div>
                        <div class="stat-icon bg-info">
                            <i class="fas fa-paw"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reservations List -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Riwayat Kunjungan
            </h6>
        </div>
        <div class="card-body">
            @if($jadwalList->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No. Reservasi</th>
                                <th>Tanggal & Waktu</th>
                                <th>Hewan</th>
                                <th>No. Urut</th>
                                <th>Dokter</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jadwalList as $reservation)
                                <tr>
                                    <td>
                                        <span class="badge bg-info">
                                            #{{ $reservation->idreservasi_dokter }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ \Carbon\Carbon::parse($reservation->waktu_daftar)->format('d M Y') }}</strong><br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($reservation->waktu_daftar)->format('H:i') }} WIB</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-paw text-primary me-2"></i>
                                            <div>
                                                <strong>{{ $reservation->pet->nama }}</strong><br>
                                                <small class="text-muted">{{ $reservation->pet->rasHewan->nama_ras ?? 'Tidak diketahui' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary fs-6">
                                            {{ $reservation->no_urut }}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="fas fa-user-md text-success me-1"></i>
                                        Dr. {{ $reservation->dokter->user->nama ?? 'Belum ditentukan' }}
                                    </td>
                                    <td>
                                        @if($reservation->status == 1)
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock me-1"></i>Menunggu
                                            </span>
                                        @elseif($reservation->status == 2)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Selesai
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-times-circle me-1"></i>Dibatalkan
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $jadwalList->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-4x text-secondary mb-4"></i>
                    <h4 class="text-dark">Belum Ada Kunjungan</h4>
                    <p class="text-dark">Anda belum memiliki riwayat kunjungan ke klinik.</p>
                    <p class="text-secondary small">
                        Untuk membuat reservasi baru, silakan hubungi resepsionis di klinik.
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Information -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-primary shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-info-circle text-primary fs-4 me-3"></i>
                        <div>
                            <h6 class="fw-bold text-dark mb-2">Informasi Kunjungan</h6>
                            <p class="text-dark mb-2">Status kunjungan dijelaskan sebagai berikut:</p>
                            <ul class="mb-0">
                                <li><span class="badge bg-warning text-white me-1">MENUNGGU</span> - Kunjungan yang telah dijadwalkan dan sedang menunggu giliran</li>
                                <li><span class="badge bg-success text-white me-1">SELESAI</span> - Kunjungan yang sudah selesai dilakukan</li>
                                <li><span class="badge bg-secondary text-white me-1">DIBATALKAN</span> - Kunjungan yang dibatalkan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
