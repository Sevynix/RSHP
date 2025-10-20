@extends('layouts.admin')

@section('title', isset($jenisHewan) ? 'Edit Jenis Hewan' : 'Tambah Jenis Hewan')
@section('page-title', isset($jenisHewan) ? 'Edit Jenis Hewan' : 'Tambah Jenis Hewan')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-{{ isset($jenisHewan) ? 'edit' : 'plus' }} me-2"></i>
                    {{ isset($jenisHewan) ? 'Edit Jenis Hewan' : 'Tambah Jenis Hewan Baru' }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ isset($jenisHewan) ? route('admin.jenis-hewan.update', $jenisHewan->idjenis_hewan) : route('admin.jenis-hewan.store') }}" method="POST">
                    @csrf
                    @if(isset($jenisHewan))
                        @method('PUT')
                    @endif
                    
                    <div class="mb-3">
                        <label for="nama_jenis_hewan" class="form-label">Nama Jenis Hewan <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('nama_jenis_hewan') is-invalid @enderror" 
                               id="nama_jenis_hewan" 
                               name="nama_jenis_hewan" 
                               value="{{ old('nama_jenis_hewan', $jenisHewan->nama_jenis_hewan ?? '') }}" 
                               required>
                        <div class="form-text">Contoh: Anjing, Kucing, Burung, Kelinci, dll.</div>
                        @error('nama_jenis_hewan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan
                        </button>
                        <a href="{{ route('admin.jenis-hewan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
