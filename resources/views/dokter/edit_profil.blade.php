@extends('layouts.main')

@section('title', 'Edit Profil Dokter')
@section('page-title', 'Edit Profil Dokter')
@section('user-role', 'Dokter')

@section('sidebar-menu')
    <li class="{{ request()->routeIs('dokter.dashboard') ? 'active' : '' }}">
        <a href="{{ route('dokter.dashboard') }}">
            <i class="fas fa-tachometer-alt"></i>
            <span class="nav-text">Dashboard</span>
        </a>
    </li>
    <li class="{{ request()->routeIs('dokter.data-pasien') ? 'active' : '' }}">
        <a href="{{ route('dokter.data-pasien') }}">
            <i class="fas fa-paw"></i>
            <span class="nav-text">Data Pasien</span>
        </a>
    </li>
    <li class="{{ request()->routeIs('dokter.rekammedis.*') ? 'active' : '' }}">
        <a href="{{ route('dokter.rekammedis.index') }}">
            <i class="fas fa-file-medical"></i>
            <span class="nav-text">Rekam Medis</span>
        </a>
    </li>
    <li class="{{ request()->routeIs('dokter.profil*') ? 'active' : '' }}">
        <a href="{{ route('dokter.profil') }}">
            <i class="fas fa-user-circle"></i>
            <span class="nav-text">Profil</span>
        </a>
    </li>
@endsection

@section('content')
<div class="container-fluid px-4 py-3">
    <h3 class="fs-4 mb-4">Edit Profil Dokter</h3>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger d-flex align-items-center">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <div>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('dokter.profil.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama" class="form-label">
                                <i class="fas fa-user me-2"></i>Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="nama" 
                                   id="nama" 
                                   class="form-control @error('nama') is-invalid @enderror" 
                                   value="{{ old('nama', $user->nama) }}"
                                   required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $user->email) }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-2"></i>Password Baru (Opsional)
                            </label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="Kosongkan jika tidak ingin mengubah password"
                                   minlength="6">
                            <small class="form-text text-muted">Minimal 6 karakter. Kosongkan jika tidak ingin mengubah.</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label">
                                <i class="fas fa-map-marker-alt me-2"></i>Alamat <span class="text-danger">*</span>
                            </label>
                            <textarea name="alamat" 
                                      id="alamat" 
                                      class="form-control @error('alamat') is-invalid @enderror" 
                                      rows="3"
                                      required>{{ old('alamat', $dokter->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="no_hp" class="form-label">
                                <i class="fas fa-phone me-2"></i>No HP <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="no_hp" 
                                   id="no_hp" 
                                   class="form-control @error('no_hp') is-invalid @enderror" 
                                   value="{{ old('no_hp', $dokter->no_hp) }}"
                                   required>
                            @error('no_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bidang_dokter" class="form-label">
                                <i class="fas fa-stethoscope me-2"></i>Bidang Dokter <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="bidang_dokter" 
                                   id="bidang_dokter" 
                                   class="form-control @error('bidang_dokter') is-invalid @enderror" 
                                   value="{{ old('bidang_dokter', $dokter->bidang_dokter) }}"
                                   required>
                            @error('bidang_dokter')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('dokter.profil') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
