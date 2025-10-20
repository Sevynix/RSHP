@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="border-bottom pb-2">Selamat Datang, {{ session('nama', 'Admin') }}!</h2>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-white-50">Total Users</h6>
                        <h2 class="mb-0">{{ $totalUsers }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-white-50">Total Pets</h6>
                        <h2 class="mb-0">{{ $totalPets }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-paw fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-white-50">Today's Appointments</h6>
                        <h2 class="mb-0">{{ $todayAppointments }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-check fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 col-sm-6">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted">Total Pemilik</h6>
                        <h2 class="mb-0">{{ $totalPemilik }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-tie fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">Master Data Sistem</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <i class="fas fa-tags fa-3x text-primary mb-3"></i>
                                <h5>Kategori</h5>
                                <h2 class="text-primary">{{ $totalKategori }}</h2>
                                <a href="{{ route('admin.kategori.index') }}" class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="fas fa-eye me-1"></i>Lihat Data
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <i class="fas fa-clinic-medical fa-3x text-success mb-3"></i>
                                <h5>Kategori Klinis</h5>
                                <h2 class="text-success">{{ $totalKategoriKlinis }}</h2>
                                <a href="{{ route('admin.kategoriklinis.index') }}" class="btn btn-sm btn-outline-success mt-2">
                                    <i class="fas fa-eye me-1"></i>Lihat Data
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card border-info">
                            <div class="card-body text-center">
                                <i class="fas fa-notes-medical fa-3x text-info mb-3"></i>
                                <h5>Kode Tindakan</h5>
                                <h2 class="text-info">{{ $totalKodeTindakan }}</h2>
                                <a href="{{ route('admin.kodetindakanterapi.index') }}" class="btn btn-sm btn-outline-info mt-2">
                                    <i class="fas fa-eye me-1"></i>Lihat Data
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                <i class="fas fa-paw fa-3x text-warning mb-3"></i>
                                <h5>Jenis Hewan</h5>
                                <h2 class="text-warning">{{ $totalJenisHewan }}</h2>
                                <a href="{{ route('admin.jenishewan.index') }}" class="btn btn-sm btn-outline-warning mt-2">
                                    <i class="fas fa-eye me-1"></i>Lihat Data
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card border-danger">
                            <div class="card-body text-center">
                                <i class="fas fa-dog fa-3x text-danger mb-3"></i>
                                <h5>Ras Hewan</h5>
                                <h2 class="text-danger">{{ $totalRasHewan }}</h2>
                                <a href="{{ route('admin.rashewan.index') }}" class="btn btn-sm btn-outline-danger mt-2">
                                    <i class="fas fa-eye me-1"></i>Lihat Data
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card border-secondary">
                            <div class="card-body text-center">
                                <i class="fas fa-user-shield fa-3x text-secondary mb-3"></i>
                                <h5>Role</h5>
                                <h2 class="text-secondary">{{ $totalRoles }}</h2>
                                <a href="{{ route('admin.role-user.index') }}" class="btn btn-sm btn-outline-secondary mt-2">
                                    <i class="fas fa-eye me-1"></i>Lihat Data
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(!empty($recentAppointments) && count($recentAppointments) > 0)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0"><i class="fas fa-calendar me-2"></i>Temu Dokter Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Pemilik</th>
                                <th>Pet</th>
                                <th>Dokter</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAppointments as $appointment)
                            <tr>
                                <td>{{ date('d M Y H:i', strtotime($appointment->waktu_daftar)) }}</td>
                                <td>{{ $appointment->nama_pemilik }}</td>
                                <td>{{ $appointment->nama_pet }}</td>
                                <td>{{ $appointment->nama_dokter }}</td>
                                <td>
                                    <span class="badge bg-{{ $appointment->status == 'selesai' ? 'success' : 'warning' }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row mt-4">
    <div class="col-12">
        <div class="alert alert-info">
            <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Informasi:</h6>
            <p class="mb-0">Gunakan menu di sebelah kiri untuk mengelola data sistem.</p>
        </div>
    </div>
</div>
@endsection
