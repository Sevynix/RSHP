@extends('layouts.admin')

@section('title', 'Manajemen Role User')
@section('page-title', 'Manajemen Role User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fs-4 mb-0">Manajemen Role User</h3>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <table class="table rounded shadow-sm table-hover table-custom-header">
            <thead>
                <tr>
                    <th>ID User</th>
                    <th>Nama</th>
                    <th>Role yang Dimiliki</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usersWithRoles as $userData)
                <tr>
                    <td>{{ $userData['iduser'] }}</td>
                    <td>{{ $userData['nama'] }}</td>
                    <td>
                        @if(empty($userData['roles']))
                            <span class="badge bg-secondary">Belum ada role</span>
                        @else
                            @foreach($userData['roles'] as $role)
                                <span class="badge bg-{{ $role['status_aktif'] == 1 ? 'success' : 'warning' }} me-1">
                                    {{ $role['nama_role'] }}
                                    @if($role['status_aktif'] == 0)
                                        (Nonaktif)
                                    @endif
                                </span>
                            @endforeach
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.role-user.assign', $userData['iduser']) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-user-shield me-1"></i>Kelola Role
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center p-4">Tidak ada data pengguna.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
