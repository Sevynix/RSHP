@extends('layouts.main')

@section('title', 'Hewan Peliharaan Saya')
@section('page-title', 'Hewan Peliharaan Saya')
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
            <i class="fas fa-paw me-2"></i>
            Hewan Peliharaan Saya
        </h1>
    </div>

    @if($pets->count() > 0)
        <!-- Pet Statistics -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title mb-0">{{ $pets->total() }}</h4>
                                <p class="card-text">Total Hewan Peliharaan</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-paw fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title mb-0">{{ $totalJenisHewan }}</h4>
                                <p class="card-text">Jenis Hewan</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-tags fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title mb-0">{{ $totalRas }}</h4>
                                <p class="card-text">Ras Berbeda</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-dna fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pets Grid -->
        <div class="row">
            @foreach($pets as $pet)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card pet-card h-100 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-paw me-2"></i>
                                {{ $pet->nama }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-4 text-center">
                                    <div class="pet-avatar">
                                        @php
                                            $animalIcon = 'fa-paw';
                                            if (stripos($pet->jenisHewan->nama_jenis_hewan, 'anjing') !== false) {
                                                $animalIcon = 'fa-dog';
                                            } elseif (stripos($pet->jenisHewan->nama_jenis_hewan, 'kucing') !== false) {
                                                $animalIcon = 'fa-cat';
                                            }
                                        @endphp
                                        <i class="fas {{ $animalIcon }} fa-4x text-primary mb-2"></i>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="pet-info">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td><strong>Jenis:</strong></td>
                                                <td>{{ $pet->jenisHewan->nama_jenis_hewan ?? 'Tidak diketahui' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Ras:</strong></td>
                                                <td>{{ $pet->rasHewan->nama_ras ?? 'Tidak diketahui' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Jenis Kelamin:</strong></td>
                                                <td>{{ $pet->jenis_kelamin ?? 'Tidak diketahui' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            @if(!empty($pet->tanggal_lahir) && $pet->tanggal_lahir !== '0000-00-00')
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-birthday-cake me-1"></i>
                                        <strong>Tanggal Lahir:</strong> {{ \Carbon\Carbon::parse($pet->tanggal_lahir)->format('d M Y') }}
                                        @php
                                            $birthDate = \Carbon\Carbon::parse($pet->tanggal_lahir);
                                            $age = $birthDate->diff(now());
                                        @endphp
                                        @if($age->y > 0)
                                            ({{ $age->y }} tahun
                                            @if($age->m > 0)
                                                {{ $age->m }} bulan
                                            @endif
                                            )
                                        @elseif($age->m > 0)
                                            ({{ $age->m }} bulan)
                                        @else
                                            ({{ $age->d }} hari)
                                        @endif
                                    </small>
                                </div>
                            @else
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-birthday-cake me-1"></i>
                                        <strong>Tanggal Lahir:</strong> Tidak diketahui
                                    </small>
                                </div>
                            @endif
                            
                            @if(!empty($pet->warna_tanda))
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-palette me-1"></i>
                                        <strong>Warna/Tanda:</strong> {{ $pet->warna_tanda }}
                                    </small>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-light">
                            <div class="row">
                                <div class="col-6">
                                    <a href="{{ route('pemilik.jadwal') }}?pet={{ $pet->idpet }}" 
                                       class="btn btn-outline-primary btn-sm w-100">
                                        <i class="fas fa-calendar me-1"></i>
                                        Riwayat Kunjungan
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('pemilik.rekam-medis') }}?pet={{ $pet->idpet }}" 
                                       class="btn btn-outline-success btn-sm w-100">
                                        <i class="fas fa-file-medical me-1"></i>
                                        Rekam Medis
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-3">
            {{ $pets->links() }}
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-paw fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted">Belum Ada Hewan Peliharaan</h4>
                        <p class="text-muted">Anda belum memiliki hewan peliharaan yang terdaftar dalam sistem.</p>
                        <p class="text-muted small">
                            Untuk mendaftarkan hewan peliharaan baru, silakan hubungi resepsionis di klinik.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
