@extends('layouts.app')

@section('title', 'Profil Pemilik')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Profil Saya</h1>
        <div>
            <a href="{{ route('pemilik.edit-profil') }}" class="btn btn-primary">
                <i class="bi bi-pencil-square"></i> Edit Profil
            </a>
            <a href="{{ route('pemilik.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                        <i class="bi bi-person-circle fs-1 text-primary"></i>
                    </div>
                    <h4 class="mt-3 mb-0">{{ $user->name }}</h4>
                    <p class="text-muted">Pemilik Hewan</p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Informasi Akun</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="200"><strong>Username</strong></td>
                            <td>{{ $user->username }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email</strong></td>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>Alamat</strong></td>
                            <td>{{ $pemilik->alamat ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>No. HP</strong></td>
                            <td>{{ $pemilik->no_hp ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Terdaftar Sejak</strong></td>
                            <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
