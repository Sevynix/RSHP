@extends('layouts.admin')

@section('title', 'Reset Password')
@section('page-title', 'Reset Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="fas fa-key me-2"></i>
                <h5 class="mb-0">Reset Password</h5>
            </div>
            <div class="card-body p-4 text-center">
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <p class="mb-0">{{ session('success') }}</p>
                    </div>
                @elseif(session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <p class="mb-0">{{ session('error') }}</p>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <p class="mb-0">Password untuk user <strong>{{ $user->nama }}</strong> akan direset ke default: <strong>123456</strong></p>
                    </div>
                    
                    <form action="{{ route('admin.users.reset-password.confirm', $user->iduser) }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-lg">
                            <i class="fas fa-key me-2"></i>Konfirmasi Reset Password
                        </button>
                    </form>
                @endif
                
                <a href="{{ route('admin.users.index') }}" class="btn btn-success mt-3">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Data User
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
