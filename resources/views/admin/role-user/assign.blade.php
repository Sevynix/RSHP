@extends('layouts.admin')

@section('title', 'Kelola Role User')
@section('page-title', 'Kelola Role User')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center bg-primary text-white">
                <i class="fas fa-user-shield me-2"></i>
                <h5 class="mb-0">
                    Tambah Role untuk: {{ $user->nama }}
                </h5>
            </div>
            <div class="card-body p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <form action="{{ route('admin.role-user.update', $user->iduser) }}" method="POST">
                    @csrf
                    
                    <!-- Role yang Dimiliki -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white d-flex align-items-center">
                            <i class="fas fa-shield-alt me-2"></i>
                            <span>Role yang Dimiliki</span>
                        </div>
                        <ul class="list-group list-group-flush">
                            @if(empty($assignedRoles))
                                <li class="list-group-item text-muted">
                                    <i class="fas fa-info-circle me-2"></i>Belum ada role yang ditugaskan
                                </li>
                            @else
                                @foreach($assignedRoles as $role)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="role-name">{{ $role->nama_role }}</span>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       name="toggle_role_{{ $role->idrole }}" 
                                                       id="toggle_{{ $role->idrole }}"
                                                       {{ $role->pivot->status_aktif == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="toggle_{{ $role->idrole }}">
                                                    {{ $role->pivot->status_aktif == 1 ? 'Aktif' : 'Nonaktif' }}
                                                </label>
                                            </div>
                                            <button type="submit" 
                                                    name="action" 
                                                    value="remove_{{ $role->idrole }}" 
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Yakin ingin menghapus role {{ $role->nama_role }}?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                    
                    <!-- Role yang Tersedia -->
                    @if(!empty($unassignedRoles) && count($unassignedRoles) > 0)
                        <div class="card">
                            <div class="card-header bg-info text-white d-flex align-items-center">
                                <i class="fas fa-plus-circle me-2"></i>
                                <span>Role yang Tersedia</span>
                            </div>
                            <ul class="list-group list-group-flush">
                                @foreach($unassignedRoles as $role)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>{{ $role->nama_role }}</span>
                                        <button type="submit" 
                                                name="action" 
                                                value="add_{{ $role->idrole }}" 
                                                class="btn btn-sm btn-success">
                                            <i class="fas fa-plus me-1"></i>Tambahkan
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="alert alert-info mt-3" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-2"></i>
                            <span>Gunakan toggle switch untuk mengaktifkan/menonaktifkan role, lalu klik "Simpan Perubahan".</span>
                        </div>
                    </div>
                    
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" name="action" value="save" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.role-user.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
