@extends('layouts.main')

@section('title', 'Dashboard Pemilik')
@section('page-title', 'Dashboard Pemilik')
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
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Dashboard Pemilik</h1>
            <p class="text-muted">Selamat datang, {{ Auth::user()->nama ?? session('user_name') }}</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Hewan Peliharaan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPets }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-paw fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Jadwal Mendatang</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $upcomingJadwal }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Kunjungan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalJadwal }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hospital fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Rekam Medis</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMedicalRecords }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-medical-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- My Pets Overview -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-paw me-2"></i>Hewan Peliharaan Saya
                    </h6>
                    <a href="{{ route('pemilik.pets') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-list me-1"></i>Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($recentPets) && count($recentPets) > 0)
                        <div class="row">
                            @foreach(array_slice($recentPets, 0, 4) as $pet)
                                <div class="col-md-6 mb-3">
                                    <div class="card border-primary h-100">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center">
                                                <div class="pet-icon me-3">
                                                    <i class="fas fa-dog fa-2x text-primary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-bold">{{ is_array($pet) ? $pet['nama'] : $pet->nama }}</h6>
                                                    <small class="text-muted">
                                                        {{ is_array($pet) ? ($pet['nama_ras'] ?? 'Tidak diketahui') : ($pet->nama_ras ?? 'Tidak diketahui') }}
                                                    </small><br>
                                                    <small class="text-info">
                                                        {{ is_array($pet) ? ($pet['nama_jenis_hewan'] ?? 'Hewan') : ($pet->nama_jenis_hewan ?? 'Hewan') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-paw fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Anda belum memiliki hewan peliharaan terdaftar</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-clock me-2"></i>Aktivitas Terbaru
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($recentActivities) && count($recentActivities) > 0)
                        <div class="timeline">
                            @foreach($recentActivities as $activity)
                                @php
                                    $activityData = is_array($activity) ? $activity : (array) $activity;
                                @endphp
                                <div class="timeline-item mb-3">
                                    <div class="d-flex">
                                        <div class="timeline-icon me-3">
                                            @if($activityData['type'] === 'reservation')
                                                @if($activityData['status'] == 1)
                                                    <i class="fas fa-calendar-check text-warning"></i>
                                                @elseif($activityData['status'] == 2)
                                                    <i class="fas fa-check-circle text-success"></i>
                                                @endif
                                            @else
                                                <i class="fas fa-file-medical text-info"></i>
                                            @endif
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="mb-1">
                                                @if($activityData['type'] === 'reservation')
                                                    Kunjungan - {{ $activityData['pet_name'] ?? 'Hewan' }}
                                                @else
                                                    Rekam Medis - {{ $activityData['pet_name'] ?? 'Hewan' }}
                                                @endif
                                            </h6>
                                            <small class="text-muted">
                                                <i class="fas fa-user-md me-1"></i>
                                                Dr. {{ $activityData['doctor_name'] ?? 'Tidak diketahui' }}
                                            </small><br>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ \Carbon\Carbon::parse($activityData['date'])->format('d M Y H:i') }}
                                            </small>
                                            @if(!empty($activityData['diagnosis']))
                                                <br><small class="text-info">
                                                    <i class="fas fa-notes-medical me-1"></i>
                                                    {{ Str::limit($activityData['diagnosis'], 50) }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada aktivitas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Information Alert -->
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info shadow" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle fa-2x me-3"></i>
                    <div>
                        <h5 class="alert-heading mb-2">
                            <i class="fas fa-hospital me-2"></i>Selamat Datang di RSHP (Rumah Sakit Hewan Peliharaan)
                        </h5>
                        <p class="mb-2">
                            <strong>Untuk membuat jadwal konsultasi atau pemeriksaan hewan peliharaan Anda:</strong>
                        </p>
                        <ul class="mb-2">
                            <li><i class="fas fa-phone me-2 text-primary"></i>Hubungi resepsionis kami di <strong>telepon</strong></li>
                            <li><i class="fas fa-map-marker-alt me-2 text-primary"></i>Kunjungi langsung <strong>RSHP</strong> untuk konsultasi</li>
                            <li><i class="fas fa-clock me-2 text-primary"></i>Jam operasional: <strong>Senin-Minggu, 08:00-20:00 WIB</strong></li>
                        </ul>
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt me-1"></i>
                                Gunakan menu navigasi di sidebar untuk melihat informasi hewan peliharaan, jadwal kunjungan, dan rekam medis Anda.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    .timeline-item {
        position: relative;
    }
    .timeline-icon {
        width: 30px;
        text-align: center;
    }
    .pet-icon {
        min-width: 50px;
    }
    .card:hover {
        transform: translateY(-2px);
        transition: all 0.2s ease;
    }
    </style>
</div>
@endsection
