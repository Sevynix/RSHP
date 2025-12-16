@extends('layouts.admin')

@section('title', isset($pet) ? 'Edit Pet' : 'Tambah Pet')
@section('page-title', isset($pet) ? 'Edit Pet' : 'Tambah Pet')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-{{ isset($pet) ? 'edit' : 'plus' }} me-2"></i>
                    {{ isset($pet) ? 'Edit Data Pet' : 'Tambah Pet Baru' }}
                </h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ isset($pet) ? route('admin.pet.update', $pet->idpet) : route('admin.pet.store') }}" method="POST">
                    @csrf
                    @if(isset($pet))
                        @method('PUT')
                    @endif
                    
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Pet <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('nama') is-invalid @enderror" 
                               id="nama" 
                               name="nama" 
                               value="{{ old('nama', $pet->nama ?? '') }}" 
                               required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                   id="tanggal_lahir" 
                                   name="tanggal_lahir" 
                                   value="{{ old('tanggal_lahir', $pet->tanggal_lahir ?? '') }}" 
                                   required>
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_kelamin') is-invalid @enderror" 
                                    id="jenis_kelamin" 
                                    name="jenis_kelamin" 
                                    required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="J" {{ old('jenis_kelamin', $pet->jenis_kelamin ?? '') == 'J' ? 'selected' : '' }}>Jantan</option>
                                <option value="B" {{ old('jenis_kelamin', $pet->jenis_kelamin ?? '') == 'B' ? 'selected' : '' }}>Betina</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="warna_tanda" class="form-label">Warna / Tanda</label>
                        <input type="text" 
                               class="form-control @error('warna_tanda') is-invalid @enderror" 
                               id="warna_tanda" 
                               name="warna_tanda" 
                               value="{{ old('warna_tanda', $pet->warna_tanda ?? '') }}">
                        @error('warna_tanda')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="idpemilik" class="form-label">Pemilik <span class="text-danger">*</span></label>
                        <select class="form-select @error('idpemilik') is-invalid @enderror" 
                                id="idpemilik" 
                                name="idpemilik" 
                                required>
                            <option value="">-- Pilih Pemilik --</option>
                            @foreach($pemiliks as $pemilik)
                                <option value="{{ $pemilik->idpemilik }}" 
                                    {{ old('idpemilik', $pet->idpemilik ?? '') == $pemilik->idpemilik ? 'selected' : '' }}>
                                    {{ $pemilik->user ? $pemilik->user->nama : 'Pemilik #'.$pemilik->idpemilik }}
                                </option>
                            @endforeach
                        </select>
                        @error('idpemilik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="idras_hewan" class="form-label">Ras Hewan <span class="text-danger">*</span></label>
                        <select class="form-select @error('idras_hewan') is-invalid @enderror" 
                                id="idras_hewan" 
                                name="idras_hewan" 
                                required>
                            <option value="">-- Pilih Ras --</option>
                            @foreach($rases as $ras)
                                <option value="{{ $ras->idras_hewan }}" 
                                    {{ old('idras_hewan', $pet->idras_hewan ?? '') == $ras->idras_hewan ? 'selected' : '' }}>
                                    {{ $ras->nama_ras }} ({{ $ras->jenisHewan->nama_jenis_hewan }})
                                </option>
                            @endforeach
                        </select>
                        @error('idras_hewan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>{{ isset($pet) ? 'Update Data' : 'Simpan Data' }}
                        </button>
                        <a href="{{ route('admin.pet.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
