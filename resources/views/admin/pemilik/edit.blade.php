@extends('layouts.admin')

@section('title', 'Edit Pemilik')
@section('page-title', 'Edit Pemilik')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-8">
        <div class="form-wrapper-test rounded shadow-sm">
            <div class="form-info-panel d-none d-lg-flex flex-column">
                <div class="mb-4">
                    <h3 class="d-flex align-items-center">
                        <i class="fas fa-user-edit me-2"></i> 
                        <span>Edit Pemilik</span>
                    </h3>
                </div>
                <p class="mt-2 text-white-50">
                    Perbarui informasi pemilik di sebelah kanan. 
                    Password hanya diisi jika ingin mengganti password.
                </p>
                <div class="mt-auto">
                    <p class="mb-0 text-white-50">
                        <i class="fas fa-info-circle me-2"></i>
                        Kosongkan password jika tidak ingin mengubah
                    </p>
                </div>
            </div>
            <div class="form-body">
                <h4 class="fs-4 mb-4 d-lg-none d-flex align-items-center">
                    <i class="fas fa-user-edit me-2"></i>
                    Edit Data Pemilik
                </h4>
                
                @if(session('error'))
                    <x-alert type="error" :message="session('error')" />
                @endif

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
                
                <form action="{{ route('admin.pemilik.update', $pemilik->idpemilik) }}" method="POST" class="needs-validation" novalidate>
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
                               value="{{ old('nama', $pemilik->user->nama) }}"
                               required
                               placeholder="Masukkan nama lengkap pemilik">
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
                               value="{{ old('email', $pemilik->user->email) }}"
                               required
                               placeholder="contoh@email.com">
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
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-lock me-2"></i>Konfirmasi Password Baru
                        </label>
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation" 
                               class="form-control" 
                               placeholder="Ulangi password baru"
                               minlength="6">
                        <small class="form-text text-muted">Ulangi password baru jika Anda mengisi password baru.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="no_wa" class="form-label">
                            <i class="fas fa-phone me-2"></i>No WhatsApp
                        </label>
                        <input type="text" 
                               name="no_wa" 
                               id="no_wa" 
                               class="form-control @error('no_wa') is-invalid @enderror" 
                               value="{{ old('no_wa', $pemilik->no_wa) }}"
                               placeholder="08xxxxxxxxxx">
                        <small class="form-text text-muted">Format: 08xxxxxxxxxx</small>
                        @error('no_wa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="alamat" class="form-label">
                            <i class="fas fa-map-marker-alt me-2"></i>Alamat
                        </label>
                        <textarea name="alamat" 
                                  id="alamat" 
                                  class="form-control @error('alamat') is-invalid @enderror" 
                                  rows="3"
                                  placeholder="Masukkan alamat lengkap">{{ old('alamat', $pemilik->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Pemilik
                        </button>
                        <a href="{{ route('admin.pemilik.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
