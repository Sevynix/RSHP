@extends('layouts.admin')

@section('title', isset($kategoriKlinis) ? 'Edit Kategori Klinis' : 'Tambah Kategori Klinis')
@section('page-title', isset($kategoriKlinis) ? 'Edit Kategori Klinis' : 'Tambah Kategori Klinis')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-{{ isset($kategoriKlinis) ? 'edit' : 'plus' }} me-2"></i>
                    {{ isset($kategoriKlinis) ? 'Edit Kategori Klinis' : 'Tambah Kategori Klinis Baru' }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ isset($kategoriKlinis) ? route('admin.kategoriklinis.update', $kategoriKlinis->idkategori_klinis) : route('admin.kategoriklinis.store') }}" method="POST">
                    @csrf
                    @if(isset($kategoriKlinis))
                        @method('PUT')
                    @endif
                    
                    <div class="mb-3">
                        <label for="nama_kategori_klinis" class="form-label">Nama Kategori Klinis <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('nama_kategori_klinis') is-invalid @enderror" 
                               id="nama_kategori_klinis" 
                               name="nama_kategori_klinis" 
                               value="{{ old('nama_kategori_klinis', $kategoriKlinis->nama_kategori_klinis ?? '') }}" 
                               required>
                        @error('nama_kategori_klinis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan
                        </button>
                        <a href="{{ route('admin.kategoriklinis.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
