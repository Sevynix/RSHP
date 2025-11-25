@extends('layouts.main')

@section('title', 'Profil Pemilik')
@section('page-title', 'Profil Saya')
@section('user-role', 'Pemilik')

@section('sidebar-menu')
    <li class="{{ request()->routeIs('pemilik.dashboard') ? 'active' : '' }}">
        <a href="{{ route('pemilik.dashboard') }}">
            <i class="fas fa-tachometer-alt"></i>
            <span class="nav-text">Dashboard</span>
        </a>
    </li>
    <li class="{{ request()->routeIs('pemilik.pets') ? 'active' : '' }}">
        <a href="{{ route('pemilik.pets') }}">
            <i class="fas fa-paw"></i>
            <span class="nav-text">Hewan Peliharaan</span>
        </a>
    </li>
    <li class="{{ request()->routeIs('pemilik.jadwal') ? 'active' : '' }}">
        <a href="{{ route('pemilik.jadwal') }}">
            <i class="fas fa-calendar-alt"></i>
            <span class="nav-text">Jadwal Temu</span>
        </a>
    </li>
    <li class="{{ request()->routeIs('pemilik.rekam-medis') ? 'active' : '' }}">
        <a href="{{ route('pemilik.rekam-medis') }}">
            <i class="fas fa-file-medical"></i>
            <span class="nav-text">Rekam Medis</span>
        </a>
    </li>
    <li class="{{ request()->routeIs('pemilik.profil*') ? 'active' : '' }}">
        <a href="{{ route('pemilik.profil') }}">
            <i class="fas fa-user-circle"></i>
            <span class="nav-text">Profil</span>
        </a>
    </li>
@endsection

@section('content')
<div class="profile-card">
    <div class="profile-header">
        <div class="profile-avatar">
            <i class="fas fa-user"></i>
        </div>
        <div class="profile-name">{{ $user->nama }}</div>
        <div class="profile-role">Pemilik Hewan</div>
    </div>

    <div class="info-section">
        <h5><i class="fas fa-user-circle"></i> Informasi Akun</h5>
        
        <div class="info-item">
            <div class="info-label">
                <i class="fas fa-user"></i> Username
            </div>
            <div class="info-value">{{ $user->nama }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">
                <i class="fas fa-envelope"></i> Email
            </div>
            <div class="info-value">{{ $user->email }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">
                <i class="fas fa-map-marker-alt"></i> Alamat
            </div>
            <div class="info-value">{{ $pemilik->alamat ?? '-' }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">
                <i class="fas fa-phone"></i> No. HP
            </div>
            <div class="info-value">{{ $pemilik->no_wa ?? '-' }}</div>
        </div>

        <div class="info-item">
            <div class="info-label">
                <i class="fas fa-calendar-check"></i> Terdaftar Sejak
            </div>
            <div class="info-value">{{ \Carbon\Carbon::parse($pemilik->created_at ?? now())->format('d/m/Y') }}</div>
        </div>
    </div>

    <div class="action-buttons">
        <a href="{{ route('pemilik.edit-profil') }}" class="btn-edit">
            <i class="fas fa-edit"></i> Edit Profil
        </a>
        <a href="{{ route('pemilik.dashboard') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection
