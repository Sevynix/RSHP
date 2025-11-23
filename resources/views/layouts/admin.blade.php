@extends('layouts.main')

@section('user-role', 'Administrator')

@section('sidebar-menu')
    <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a href="{{ route('admin.dashboard') }}">
            <i class="fas fa-tachometer-alt"></i>
            <span class="nav-text">Dashboard</span>
        </a>
    </li>

    <div class="nav-section-title">
        <span>Master Data</span>
    </div>

    <li class="{{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
        <a href="{{ route('admin.kategori.index') }}">
            <i class="fas fa-tags"></i>
            <span class="nav-text">Kategori</span>
        </a>
    </li>

    <li class="{{ request()->routeIs('admin.kategoriklinis.*') ? 'active' : '' }}">
        <a href="{{ route('admin.kategoriklinis.index') }}">
            <i class="fas fa-clinic-medical"></i>
            <span class="nav-text">Kategori Klinis</span>
        </a>
    </li>

    <li class="{{ request()->routeIs('admin.kodetindakanterapi.*') ? 'active' : '' }}">
        <a href="{{ route('admin.kodetindakanterapi.index') }}">
            <i class="fas fa-notes-medical"></i>
            <span class="nav-text">Kode Tindakan Terapi</span>
        </a>
    </li>

    <li class="{{ request()->routeIs('admin.jenishewan.*') ? 'active' : '' }}">
        <a href="{{ route('admin.jenishewan.index') }}">
            <i class="fas fa-paw"></i>
            <span class="nav-text">Jenis Hewan</span>
        </a>
    </li>

    <li class="{{ request()->routeIs('admin.rashewan.*') ? 'active' : '' }}">
        <a href="{{ route('admin.rashewan.index') }}">
            <i class="fas fa-dog"></i>
            <span class="nav-text">Ras Hewan</span>
        </a>
    </li>

    <div class="nav-section-title">
        <span>Manajemen</span>
    </div>

    <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <a href="{{ route('admin.users.index') }}">
            <i class="fas fa-users"></i>
            <span class="nav-text">User</span>
        </a>
    </li>

    <li class="{{ request()->routeIs('admin.role-user.*') ? 'active' : '' }}">
        <a href="{{ route('admin.role-user.index') }}">
            <i class="fas fa-user-shield"></i>
            <span class="nav-text">Role User</span>
        </a>
    </li>

    <li class="{{ request()->routeIs('admin.pemilik.*') ? 'active' : '' }}">
        <a href="{{ route('admin.pemilik.index') }}">
            <i class="fas fa-user-tie"></i>
            <span class="nav-text">Pemilik</span>
        </a>
    </li>

    <li class="{{ request()->routeIs('admin.dokter.*') ? 'active' : '' }}">
        <a href="{{ route('admin.dokter.index') }}">
            <i class="fas fa-user-md"></i>
            <span class="nav-text">Dokter</span>
        </a>
    </li>

    <li class="{{ request()->routeIs('admin.perawat.*') ? 'active' : '' }}">
        <a href="{{ route('admin.perawat.index') }}">
            <i class="fas fa-user-nurse"></i>
            <span class="nav-text">Perawat</span>
        </a>
    </li>

    <li class="{{ request()->routeIs('admin.pet.*') ? 'active' : '' }}">
        <a href="{{ route('admin.pet.index') }}">
            <i class="fas fa-cat"></i>
            <span class="nav-text">Pet</span>
        </a>
    </li>
@endsection
