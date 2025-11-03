@extends('layouts.authenticated')

@section('title', 'Edit Rekam Medis')
@section('page-title', 'Edit Rekam Medis')
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
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-medical me-2"></i>Edit Rekam Medis
                    </h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('perawat.rekammedis.update', $record->idrekam_medis) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="idreservasi_dokter" class="form-label">Reservasi Dokter <span class="text-danger">*</span></label>
                                <select class="form-select @error('idreservasi_dokter') is-invalid @enderror" 
                                        id="idreservasi_dokter" 
                                        name="idreservasi_dokter" required>
                                    <option value="">Pilih Reservasi</option>
                                    @foreach($reservations as $reservation)
                                        <option value="{{ $reservation->idreservasi_dokter }}" 
                                                {{ (old('idreservasi_dokter', $record->idreservasi_dokter) == $reservation->idreservasi_dokter) ? 'selected' : '' }}>
                                            #{{ $reservation->no_urut }} - {{ $reservation->nama_pet }} 
                                            ({{ $reservation->nama_pemilik }}) - {{ \Carbon\Carbon::parse($reservation->waktu_daftar)->format('d/m/Y') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('idreservasi_dokter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="dokter_pemeriksa" class="form-label">Dokter Pemeriksa <span class="text-danger">*</span></label>
                                <select class="form-select @error('dokter_pemeriksa') is-invalid @enderror" 
                                        id="dokter_pemeriksa" 
                                        name="dokter_pemeriksa" required>
                                    <option value="">Pilih Dokter</option>
                                    @foreach($dokters as $dokter)
                                        <option value="{{ $dokter->iduser }}" 
                                                {{ (old('dokter_pemeriksa', $record->dokter_pemeriksa) == $dokter->iduser) ? 'selected' : '' }}>
                                            {{ $dokter->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('dokter_pemeriksa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="anamnesa" class="form-label">Anamnesa <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('anamnesa') is-invalid @enderror" 
                                          id="anamnesa" 
                                          name="anamnesa" 
                                          rows="3" 
                                          required
                                          placeholder="Riwayat penyakit dan gejala yang dialami hewan...">{{ old('anamnesa', $record->anamnesa) }}</textarea>
                                @error('anamnesa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="temuan_klinis" class="form-label">Temuan Klinis <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('temuan_klinis') is-invalid @enderror" 
                                          id="temuan_klinis" 
                                          name="temuan_klinis" 
                                          rows="3" 
                                          required
                                          placeholder="Hasil pemeriksaan fisik dan temuan klinis lainnya...">{{ old('temuan_klinis', $record->temuan_klinis) }}</textarea>
                                @error('temuan_klinis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="diagnosa" class="form-label">Diagnosa <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('diagnosa') is-invalid @enderror" 
                                          id="diagnosa" 
                                          name="diagnosa" 
                                          rows="2" 
                                          required
                                          placeholder="Diagnosa berdasarkan anamnesa dan temuan klinis...">{{ old('diagnosa', $record->diagnosa) }}</textarea>
                                @error('diagnosa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('perawat.rekammedis.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Rekam Medis
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
