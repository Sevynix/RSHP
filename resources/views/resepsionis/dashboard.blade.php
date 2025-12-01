@extends('layouts.resepsionis')

@section('title', 'Dashboard Resepsionis')
@section('page-title', 'Dashboard Resepsionis')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard Resepsionis</h1>
            </div>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="stat-card card-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-2">Antrian Aktif Hari Ini</p>
                        <h3 class="mb-0">{{ $todayQueue ?? 0 }}</h3>
                    </div>
                    <div>
                        <i class="fas fa-clock stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card card-success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-2">Selesai Hari Ini</p>
                        <h3 class="mb-0">{{ $completedToday ?? 0 }}</h3>
                    </div>
                    <div>
                        <i class="fas fa-check-circle stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card card-info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-2">Total Pet</p>
                        <h3 class="mb-0">{{ $totalPets ?? 0 }}</h3>
                    </div>
                    <div>
                        <i class="fas fa-paw stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card card-warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-2">Total Pemilik</p>
                        <h3 class="mb-0">{{ $totalOwners ?? 0 }}</h3>
                    </div>
                    <div>
                        <i class="fas fa-users stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Current Queue Status -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Antrian Aktif Hari Ini</h5>
                </div>
                <div class="card-body">
                    @if(!empty($activeQueue) && $activeQueue->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>No. Urut</th>
                                        <th>Pet</th>
                                        <th>Pemilik</th>
                                        <th>Dokter</th>
                                        <th>Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activeQueue as $queue)
                                    <tr>
                                        <td><span class="badge bg-primary">{{ $queue->no_urut }}</span></td>
                                        <td>{{ $queue->nama_pet }}</td>
                                        <td>{{ $queue->nama_pemilik }}</td>
                                        <td>{{ $queue->nama_dokter }}</td>
                                        <td>{{ date('H:i', strtotime($queue->waktu_daftar)) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-calendar-check fa-3x mb-3"></i>
                            <p class="mb-0">Tidak ada antrian aktif untuk hari ini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6 class="card-title mb-0">Pet Terbaru Didaftarkan</h6>
                </div>
                <div class="card-body">
                    @if(!empty($recentPets) && $recentPets->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentPets->take(5) as $pet)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <strong>{{ $pet->nama_pet }}</strong>
                                    <small class="text-muted d-block">{{ $pet->nama_pemilik }}</small>
                                </div>
                                <small class="text-muted">{{ date('d/m', strtotime($pet->created_at ?? 'now')) }}</small>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">Belum ada pet yang didaftarkan</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="card-title mb-0">Aktivitas Hari Ini</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item mb-2">
                            <i class="fas fa-clock text-primary me-2"></i>
                            <small class="text-muted">{{ date('H:i') }}</small> - Login sebagai Resepsionis
                        </div>
                        @if($todayQueue > 0)
                        <div class="timeline-item mb-2">
                            <i class="fas fa-plus text-success me-2"></i>
                            <small class="text-muted">Hari ini</small> - {{ $todayQueue }} antrian temu dokter
                        </div>
                        @endif
                        @if($completedToday > 0)
                        <div class="timeline-item mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <small class="text-muted">Hari ini</small> - {{ $completedToday }} temu dokter selesai
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-info-circle text-primary fs-4 me-3"></i>
                        <div>
                            <h6 class="fw-bold text-dark mb-2">Informasi:</h6>
                            <p class="text-muted mb-0">Sebagai resepsionis, Anda dapat mengelola temu dokter, melihat data pemilik dan pet.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
