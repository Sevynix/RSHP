@extends('layouts.admin')

@section('title', isset($kodeTindakan) ? 'Edit Kode Tindakan' : 'Tambah Kode Tindakan')
@section('page-title', isset($kodeTindakan) ? 'Edit Kode Tindakan' : 'Tambah Kode Tindakan')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-{{ isset($kodeTindakan) ? 'edit' : 'plus' }} me-2"></i>
                    {{ isset($kodeTindakan) ? 'Edit Kode Tindakan' : 'Tambah Kode Tindakan Baru' }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ isset($kodeTindakan) ? route('admin.kodetindakanterapi.update', $kodeTindakan->idkode_tindakan_terapi) : route('admin.kodetindakanterapi.store') }}" method="POST">
                    @csrf
                    @if(isset($kodeTindakan))
                        @method('PUT')
                    @endif
                    
                    <div class="mb-3">
                        <label for="kode" class="form-label">Kode <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('kode') is-invalid @enderror" 
                               id="kode" 
                               name="kode" 
                               value="{{ old('kode', $kodeTindakan->kode ?? '') }}" 
                               required>
                        @error('kode')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="deskripsi_tindakan_terapi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('deskripsi_tindakan_terapi') is-invalid @enderror" 
                                  id="deskripsi_tindakan_terapi" 
                                  name="deskripsi_tindakan_terapi" 
                                  rows="3" 
                                  required>{{ old('deskripsi_tindakan_terapi', $kodeTindakan->deskripsi_tindakan_terapi ?? '') }}</textarea>
                        @error('deskripsi_tindakan_terapi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="idkategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select @error('idkategori') is-invalid @enderror" 
                                id="idkategori" 
                                name="idkategori" 
                                required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoriList as $kategori)
                                <option value="{{ $kategori->idkategori }}" 
                                    {{ old('idkategori', $kodeTindakan->idkategori ?? '') == $kategori->idkategori ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('idkategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="idkategori_klinis" class="form-label">Kategori Klinis <span class="text-danger">*</span></label>
                        <select class="form-select @error('idkategori_klinis') is-invalid @enderror" 
                                id="idkategori_klinis" 
                                name="idkategori_klinis" 
                                required>
                            <option value="">-- Pilih Kategori Klinis --</option>
                            @foreach($kategoriKlinisList as $kategoriKlinis)
                                <option value="{{ $kategoriKlinis->idkategori_klinis }}" 
                                    {{ old('idkategori_klinis', $kodeTindakan->idkategori_klinis ?? '') == $kategoriKlinis->idkategori_klinis ? 'selected' : '' }}>
                                    {{ $kategoriKlinis->nama_kategori_klinis }}
                                </option>
                            @endforeach
                        </select>
                        @error('idkategori_klinis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan
                        </button>
                        <a href="{{ route('admin.kodetindakanterapi.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
