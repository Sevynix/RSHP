@extends('layouts.main')

@section('title', 'Dashboard Dokter')
@section('page-title', 'Dashboard Dokter')
@section('user-role', 'Dokter')

@section('sidebar-menu')
    <li class="{{ request()->routeIs('dokter.dashboard') ? 'active' : '' }}">
        <a href="{{ route('dokter.dashboard') }}">
            <i class="fas fa-tachometer-alt"></i>
            <span class="nav-text">Dashboard</span>
        </a>
    </li>
    <li class="{{ request()->routeIs('dokter.rekammedis.*') ? 'active' : '' }}">
        <a href="{{ route('dokter.rekammedis.index') }}">
            <i class="fas fa-file-medical"></i>
            <span class="nav-text">Rekam Medis</span>
        </a>
    </li>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="welcome-card text-white p-4 rounded shadow" style="background: linear-gradient(135deg, #1cc88a 0%, #17a673 100%);">
            <h2 class="mb-0"><i class="fas fa-user-md me-2"></i>Dashboard Dokter</h2>
            <p class="mb-0 mt-2">Selamat datang, {{ $user->nama ?? 'Dokter' }}!</p>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Rekam Medis Hari Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['today_records'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-medical-alt fa-2x text-gray-300"></i>
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

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Rekam Medis</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['monthly_records'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
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
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pet Hari Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['today_pets'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-paw fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Rekam Medis Terbaru</h6>
                <a href="{{ route('dokter.rekammedis.index') }}" class="btn btn-sm btn-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @if(!empty($recentRecords) && $recentRecords->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Pet</th>
                                    <th>Pemilik</th>
                                    <th>Diagnosis</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRecords->take(5) as $record)
                                    <tr>
                                        <td>{{ date('d/m/Y H:i', strtotime($record->created_at)) }}</td>
                                        <td>
                                            <strong>{{ $record->nama_pet ?? 'N/A' }}</strong><br>
                                            <small class="text-muted">{{ $record->jenis_hewan ?? 'N/A' }}</small>
                                        </td>
                                        <td>{{ $record->nama_pemilik ?? 'N/A' }}</td>
                                        <td>
                                            <span class="text-truncate" style="max-width: 150px; display: inline-block;">
                                                {{ $record->diagnosa ?? 'N/A' }}
                                            </span>
                                        </td>
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

    <div class="col-lg-6 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-success">Antrian Pasien Hari Ini</h6>
                <span class="badge bg-success">
                    {{ date('d M Y') }}
                </span>
            </div>
            <div class="card-body">
                @if(!empty($todayReservations) && $todayReservations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No. Urut</th>
                                    <th>Pet</th>
                                    <th>Pemilik</th>
                                    <th>Waktu</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todayReservations->take(5) as $reservation)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $reservation->no_urut ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong>{{ $reservation->nama_pet ?? 'N/A' }}</strong>
                                        </td>
                                        <td>{{ $reservation->nama_pemilik ?? 'N/A' }}</td>
                                        <td>{{ date('H:i', strtotime($reservation->waktu_daftar ?? '')) }} WIB</td>
                                        <td>
                                            @if(($reservation->status ?? 0) == 1)
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-clock me-1"></i>Menunggu
                                                </span>
                                            @elseif(($reservation->status ?? 0) == 2)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Selesai
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-times me-1"></i>Batal
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Tidak ada antrian hari ini</p>
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
.text-truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
@endsection
