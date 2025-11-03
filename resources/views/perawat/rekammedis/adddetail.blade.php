@extends('layouts.authenticated')

@section('title', 'Tambah Detail Tindakan')
@section('page-title', 'Tambah Detail Tindakan')
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
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Detail Tindakan
                    </h5>
                </div>
                <div class="card-body">
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

                    <!-- Informasi Rekam Medis -->
                    <div class="alert alert-info mb-3">
                        <h6><i class="fas fa-file-medical me-2"></i>Informasi Rekam Medis</h6>
                        <p class="mb-1"><strong>ID:</strong> {{ $record->idrekam_medis }}</p>
                        <p class="mb-1"><strong>Diagnosa:</strong> {{ $record->diagnosa }}</p>
                        <p class="mb-0"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($record->created_at)->format('d/m/Y H:i') }}</p>
                    </div>

                    <!-- Filter Kategori Form -->
                    <form method="GET" action="{{ route('perawat.rekammedis.adddetail', $record->idrekam_medis) }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="kategori_filter" class="form-label">Filter Kategori</label>
                                <select class="form-select" id="kategori_filter" name="kategori_filter" onchange="this.form.submit()">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->idkategori }}" 
                                                {{ request('kategori_filter') == $category->idkategori ? 'selected' : '' }}>
                                            {{ $category->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Pilih kategori untuk memfilter tindakan</small>
                            </div>
                        </div>
                    </form>

                    <!-- Main Form -->
                    <form action="{{ route('perawat.rekammedis.storedetail', $record->idrekam_medis) }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="idkode_tindakan_terapi" class="form-label">Kode Tindakan <span class="text-danger">*</span></label>
                                <select class="form-select @error('idkode_tindakan_terapi') is-invalid @enderror" 
                                        id="idkode_tindakan_terapi" 
                                        name="idkode_tindakan_terapi" required>
                                    <option value="">Pilih Kode Tindakan</option>
                                    @php
                                        $selectedCategory = request('kategori_filter');
                                        $filteredCount = 0;
                                    @endphp
                                    @foreach($treatmentCodes as $code)
                                        @if(!$selectedCategory || $code->idkategori == $selectedCategory)
                                            @php $filteredCount++; @endphp
                                            <option value="{{ $code->idkode_tindakan_terapi }}"
                                                    {{ old('idkode_tindakan_terapi') == $code->idkode_tindakan_terapi ? 'selected' : '' }}>
                                                {{ $code->kode }} - {{ $code->deskripsi_tindakan_terapi }}
                                                @if($code->kategori)
                                                    ({{ $code->kategori->nama_kategori }})
                                                @endif
                                            </option>
                                        @endif
                                    @endforeach
                                    @if($filteredCount == 0 && $selectedCategory)
                                        <option value="" disabled>Tidak ada kode tindakan untuk kategori ini</option>
                                    @endif
                                </select>
                                @error('idkode_tindakan_terapi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="detail" class="form-label">Detail Tindakan</label>
                                <textarea class="form-control @error('detail') is-invalid @enderror" 
                                          id="detail" 
                                          name="detail" 
                                          rows="3"
                                          placeholder="Tambahkan detail atau catatan khusus untuk tindakan ini (opsional)...">{{ old('detail') }}</textarea>
                                <small class="text-muted">Opsional: Berisi catatan tambahan atau detail khusus untuk tindakan ini</small>
                                @error('detail')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('perawat.rekammedis.show', $record->idrekam_medis) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Detail
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
