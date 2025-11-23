@extends('layouts.admin')

@section('title', 'Detail Dokter')
@section('page-title', 'Detail Dokter')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-user-md me-2"></i>Detail Dokter</h4>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">
                        <i class="fas fa-id-card me-2 text-primary"></i>ID Dokter
                    </div>
                    <div class="col-md-8">: {{ $dokter->id_dokter }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">
                        <i class="fas fa-user me-2 text-primary"></i>Nama Lengkap
                    </div>
                    <div class="col-md-8">: {{ $dokter->user->nama }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">
                        <i class="fas fa-envelope me-2 text-primary"></i>Email
                    </div>
                    <div class="col-md-8">: {{ $dokter->user->email }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">
                        <i class="fas fa-phone me-2 text-primary"></i>No HP
                    </div>
                    <div class="col-md-8">: {{ $dokter->no_hp }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">
                        <i class="fas fa-map-marker-alt me-2 text-primary"></i>Alamat
                    </div>
                    <div class="col-md-8">: {{ $dokter->alamat }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">
                        <i class="fas fa-stethoscope me-2 text-primary"></i>Bidang Dokter
                    </div>
                    <div class="col-md-8">: {{ $dokter->bidang_dokter }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">
                        <i class="fas fa-venus-mars me-2 text-primary"></i>Jenis Kelamin
                    </div>
                    <div class="col-md-8">: {{ $dokter->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <a href="{{ route('admin.dokter.edit', $dokter->id_dokter) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                    <a href="{{ route('admin.dokter.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
