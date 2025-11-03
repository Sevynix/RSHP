@extends('layouts.resepsionis')

@section('title', 'Tambah Jadwal Temu Dokter')

@section('page-title', 'Tambah Jadwal Temu Dokter')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Tambah Jadwal Temu Dokter</h1>
            </div>
        </div>
    </div>
    
    @if(session('error'))
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
    @endif
    
    @if($errors->any())
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Form untuk tambah jadwal -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-plus me-2"></i>
                        Form Tambah Jadwal Temu Dokter
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('resepsionis.temudokter.store') }}">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="idpet" class="form-label">
                                    <i class="fas fa-paw me-1"></i>Pilih Pet <span class="text-danger">*</span>
                                </label>
                                <select name="idpet" id="idpet" class="form-select @error('idpet') is-invalid @enderror" required>
                                    <option value="">-- Pilih Pet --</option>
                                    @foreach($petList as $pet)
                                        <option value="{{ $pet->idpet }}" {{ old('idpet') == $pet->idpet ? 'selected' : '' }}>
                                            {{ $pet->nama_pet }} - {{ $pet->nama_pemilik }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('idpet')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Pilih hewan peliharaan yang akan diperiksa</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="idrole_user_dokter" class="form-label">
                                    <i class="fas fa-user-md me-1"></i>Pilih Dokter <span class="text-danger">*</span>
                                </label>
                                <select name="idrole_user_dokter" id="idrole_user_dokter" class="form-select @error('idrole_user_dokter') is-invalid @enderror" required>
                                    <option value="">-- Pilih Dokter --</option>
                                    @foreach($dokterList as $dokter)
                                        <option value="{{ $dokter->idrole_user }}" {{ old('idrole_user_dokter') == $dokter->idrole_user ? 'selected' : '' }}>
                                            {{ $dokter->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('idrole_user_dokter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Pilih dokter yang akan memeriksa</div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-info-circle me-2"></i>Informasi Penting:
                                    </h6>
                                    <ul class="mb-0">
                                        <li>Jadwal akan otomatis diberikan nomor urut untuk hari ini</li>
                                        <li>Status awal akan diset sebagai "Menunggu"</li>
                                        <li>Waktu pendaftaran akan dicatat secara otomatis</li>
                                        <li>Pastikan data pet dan dokter sudah benar sebelum menyimpan</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('resepsionis.temudokter.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Tambah ke Antrian
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
