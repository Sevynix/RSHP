@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-8">
        <div class="form-wrapper-test rounded shadow-sm">
            <div class="form-info-panel d-none d-lg-flex flex-column">
                <div class="mb-4">
                    <h3 class="d-flex align-items-center">
                        <i class="fas fa-user-edit me-2"></i> 
                        <span>Edit User</span>
                    </h3>
                </div>
                <p class="mt-2 text-white-50">
                    Anda dapat mengubah nama pengguna di formulir sebelah kanan. 
                    Email dan password tidak dapat diubah dari halaman ini.
                </p>
                <div class="mt-auto">
                    <p class="mb-0 text-white-50">
                        <i class="fas fa-info-circle me-2"></i>
                        Pastikan data yang diupdate sudah benar.
                    </p>
                </div>
            </div>
            <div class="form-body">
                <h4 class="fs-4 mb-4 d-lg-none d-flex align-items-center">
                    <i class="fas fa-user-edit me-2"></i>
                    Edit User
                </h4>
                
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
                
                <form method="POST" action="{{ route('admin.users.update', $user->iduser) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="nama" class="form-label">
                            <i class="fas fa-user me-2"></i>Nama <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               id="nama"
                               name="nama" 
                               class="form-control @error('nama') is-invalid @enderror" 
                               value="{{ old('nama', $user->nama) }}" 
                               required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-envelope me-2"></i>Email (tidak bisa diubah)
                        </label>
                        <input type="email" 
                               class="form-control" 
                               value="{{ $user->email }}" 
                               disabled>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-lock me-2"></i>Password (tidak bisa diubah)
                        </label>
                        <input type="password" class="form-control" value="********" disabled>
                        <small class="text-muted">Gunakan menu "Reset Password" untuk mengubah password</small>
                    </div>
                    
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-sync-alt me-2"></i>Update User
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
