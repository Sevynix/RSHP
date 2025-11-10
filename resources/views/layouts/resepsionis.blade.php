@extends('layouts.main')

@section('user-role', 'Resepsionis')

@section('sidebar-menu')
    <!-- Dashboard -->
    <li class="{{ request()->routeIs('resepsionis.dashboard') ? 'active' : '' }}">
        <a href="{{ route('resepsionis.dashboard') }}">
            <i class="fas fa-tachometer-alt"></i>
            <span class="nav-text">Dashboard</span>
        </a>
    </li>
    
    <!-- Temu Dokter -->
    <li class="{{ request()->routeIs('resepsionis.temudokter.*') ? 'active' : '' }}">
        <a href="{{ route('resepsionis.temudokter.index') }}">
            <i class="fas fa-calendar-check"></i>
            <span class="nav-text">Temu Dokter</span>
        </a>
    </li>
    
    <div class="nav-section-title">
        <span>Data Management</span>
    </div>
    
    <!-- Data Pemilik -->
    <li class="{{ request()->routeIs('resepsionis.pemilik.*') ? 'active' : '' }}">
        <a href="{{ route('resepsionis.pemilik.index') }}">
            <i class="fas fa-users"></i>
            <span class="nav-text">Data Pemilik</span>
        </a>
    </li>
    
    <!-- Data Pet -->
    <li class="{{ request()->routeIs('resepsionis.pet.*') ? 'active' : '' }}">
        <a href="{{ route('resepsionis.pet.index') }}">
            <i class="fas fa-paw"></i>
            <span class="nav-text">Data Pet</span>
        </a>
    </li>
@endsection
