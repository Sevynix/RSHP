@extends('layouts.main')

@section('title', 'Profil Perawat')
@section('page-title', 'Profil Perawat')
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
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fs-4 mb-0">Profil Perawat</h3>
        <a href="{{ route('perawat.profil.edit') }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>Edit Profil
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-user-nurse me-2"></i>Informasi Pribadi</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">
                            <i class="fas fa-id-card me-2 text-primary"></i>ID Perawat
                        </div>
                        <div class="col-md-8">: {{ $perawat->id_perawat }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">
                            <i class="fas fa-user me-2 text-primary"></i>Nama Lengkap
                        </div>
                        <div class="col-md-8">: {{ $user->nama }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">
                            <i class="fas fa-envelope me-2 text-primary"></i>Email
                        </div>
                        <div class="col-md-8">: {{ $user->email }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">
                            <i class="fas fa-phone me-2 text-primary"></i>No HP
                        </div>
                        <div class="col-md-8">: {{ $perawat->no_hp }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">
                            <i class="fas fa-map-marker-alt me-2 text-primary"></i>Alamat
                        </div>
                        <div class="col-md-8">: {{ $perawat->alamat }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">
                            <i class="fas fa-graduation-cap me-2 text-primary"></i>Pendidikan
                        </div>
                        <div class="col-md-8">: {{ $perawat->pendidikan }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">
                            <i class="fas fa-venus-mars me-2 text-primary"></i>Jenis Kelamin
                        </div>
                        <div class="col-md-8">: {{ $perawat->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
