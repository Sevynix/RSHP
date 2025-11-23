@extends('layouts.main')

@section('title', 'Edit Profil Perawat')
@section('page-title', 'Edit Profil Perawat')
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
    <h3 class="fs-4 mb-4">Edit Profil Perawat</h3>

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

                    <form action="{{ route('perawat.profil.update') }}" method="POST">
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
                                      required>{{ old('alamat', $perawat->alamat) }}</textarea>
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
                                   value="{{ old('no_hp', $perawat->no_hp) }}"
                                   required>
                            @error('no_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="pendidikan" class="form-label">
                                <i class="fas fa-graduation-cap me-2"></i>Pendidikan <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="pendidikan" 
                                   id="pendidikan" 
                                   class="form-control @error('pendidikan') is-invalid @enderror" 
                                   value="{{ old('pendidikan', $perawat->pendidikan) }}"
                                   required>
                            @error('pendidikan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('perawat.profil') }}" class="btn btn-secondary">
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
