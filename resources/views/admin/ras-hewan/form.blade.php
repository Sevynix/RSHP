@extends('layouts.admin')

@section('title', isset($rasHewan) ? 'Edit Ras Hewan' : 'Tambah Ras Hewan')
@section('page-title', isset($rasHewan) ? 'Edit Ras Hewan' : 'Tambah Ras Hewan')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-{{ isset($rasHewan) ? 'edit' : 'plus' }} me-2"></i>
                    {{ isset($rasHewan) ? 'Edit Ras Hewan' : 'Tambah Ras Hewan Baru' }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ isset($rasHewan) ? route('admin.ras-hewan.update', $rasHewan->idras_hewan) : route('admin.ras-hewan.store') }}" method="POST">
                    @csrf
                    @if(isset($rasHewan))
                        @method('PUT')
                    @endif
                    
                    <div class="mb-3">
                        <label for="idjenis_hewan" class="form-label">Jenis Hewan <span class="text-danger">*</span></label>
                        <select class="form-select @error('idjenis_hewan') is-invalid @enderror" 
                                id="idjenis_hewan" 
                                name="idjenis_hewan" 
                                required>
                            <option value="">Pilih Jenis Hewan</option>
                            @foreach($jenisHewanList as $jenis)
                                <option value="{{ $jenis->idjenis_hewan }}" 
                                    {{ old('idjenis_hewan', $rasHewan->idjenis_hewan ?? request('jenis')) == $jenis->idjenis_hewan ? 'selected' : '' }}>
                                    {{ $jenis->nama_jenis_hewan }}
                                </option>
                            @endforeach
                        </select>
                        @error('idjenis_hewan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="nama_ras" class="form-label">Nama Ras <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('nama_ras') is-invalid @enderror" 
                               id="nama_ras" 
                               name="nama_ras" 
                               value="{{ old('nama_ras', $rasHewan->nama_ras ?? '') }}" 
                               required>
                        <div class="form-text">Contoh: Golden Retriever, Persian, Angora, dll.</div>
                        @error('nama_ras')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan
                        </button>
                        <a href="{{ route('admin.ras-hewan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
