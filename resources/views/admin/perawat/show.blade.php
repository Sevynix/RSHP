@extends('layouts.admin')

@section('title', 'Detail Perawat')
@section('page-title', 'Detail Perawat')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-user-nurse me-2"></i>Detail Perawat</h4>
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
                    <div class="col-md-8">: {{ $perawat->user->nama }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">
                        <i class="fas fa-envelope me-2 text-primary"></i>Email
                    </div>
                    <div class="col-md-8">: {{ $perawat->user->email }}</div>
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

                <div class="mt-4 d-flex gap-2">
                    <a href="{{ route('admin.perawat.edit', $perawat->id_perawat) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                    <a href="{{ route('admin.perawat.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
