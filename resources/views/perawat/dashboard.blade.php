@extends('layouts.main')

@section('title', 'Dashboard Perawat')
@section('page-title', 'Dashboard Perawat')
@section('user-role', 'Perawat')

@section('sidebar-menu')
    <li class="{{ request()->routeIs('perawat.dashboard') ? 'active' : '' }}">
        <a href="{{ route('perawat.dashboard') }}">
            <i class="fas fa-tachometer-alt"></i>
            <span class="nav-text">Dashboard</span>
        </a>
    </li>
    <li class="{{ request()->routeIs('perawat.rekammedis.*') ? 'active' : '' }}">
        <a href="{{ route('perawat.rekammedis.index') }}">
            <i class="fas fa-file-medical"></i>
            <span class="nav-text">Rekam Medis</span>
        </a>
    </li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="welcome-card text-white p-4 rounded shadow" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h2 class="mb-0"><i class="fas fa-user-nurse me-2"></i>Dashboard Perawat</h2>
            <p class="mb-0 mt-2">Selamat datang, {{ $user->nama ?? 'Perawat' }}!</p>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Rekam Medis Hari Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['today_records'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Reservasi Menunggu</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_reservations'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Rekam Medis</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['monthly_records'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-medical fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Reservasi Hari Ini</h6>
            </div>
            <div class="card-body">
                @if(!empty($todayReservations) && $todayReservations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>No. Urut</th>
                                    <th>Pet</th>
                                    <th>Pemilik</th>
                                    <th>Dokter</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todayReservations->take(5) as $reservation)
                                    <tr>
                                        <td>{{ $reservation->no_urut ?? 'N/A' }}</td>
                                        <td>{{ $reservation->nama_pet ?? 'N/A' }}</td>
                                        <td>{{ $reservation->nama_pemilik ?? 'N/A' }}</td>
                                        <td>{{ $reservation->nama_dokter ?? 'N/A' }}</td>
                                        <td>
                                            @if(($reservation->status ?? 0) == 1)
                                                <span class="badge bg-warning">Menunggu</span>
                                            @elseif(($reservation->status ?? 0) == 2)
                                                <span class="badge bg-success">Selesai</span>
                                            @else
                                                <span class="badge bg-secondary">Tidak Aktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('perawat.rekammedis.index') }}" class="btn btn-primary">
                            <i class="fas fa-clipboard-list me-1"></i>Lihat Semua Rekam Medis
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Tidak ada reservasi hari ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Rekam Medis Terbaru</h6>
            </div>
            <div class="card-body">
                @if(!empty($recentRecords) && $recentRecords->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Pet</th>
                                    <th>Diagnosis</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRecords->take(5) as $record)
                                    <tr>
                                        <td>{{ date('d/m/Y', strtotime($record->created_at)) }}</td>
                                        <td>{{ $record->nama_pet ?? 'N/A' }}</td>
                                        <td>{{ substr($record->diagnosa ?? 'N/A', 0, 30) }}...</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-file-medical fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada rekam medis</p>
                    </div>
                @endif
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
</style>
@endsection
