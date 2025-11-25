@extends('layouts.main')

@section('title', 'Rekam Medis Hewan')
@section('page-title', 'Rekam Medis Hewan')
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
<div class="container-fluid">
    <div class="content-header mb-4">
        <h2><i class="fas fa-file-medical"></i> Rekam Medis Hewan Saya</h2>
        <p>Riwayat pemeriksaan dan perawatan lengkap hewan peliharaan Anda</p>
    </div>

    @php
        $allRecords = [];
        $recordsByPet = [];
        
        foreach($pets as $pet) {
            if($pet->rekamMedis->count() > 0) {
                foreach($pet->rekamMedis as $rekamMedis) {
                    $allRecords[] = $rekamMedis;
                    $petName = $pet->nama;
                    if (!isset($recordsByPet[$petName])) {
                        $recordsByPet[$petName] = [];
                    }
                    $recordsByPet[$petName][] = $rekamMedis;
                }
            }
        }
    @endphp

    @if(empty($allRecords))
        <div class="no-records">
            <i class="fas fa-heart-broken"></i>
            <h3>Belum Ada Rekam Medis</h3>
            <p>Hewan peliharaan Anda belum memiliki rekam medis atau riwayat pemeriksaan.</p>
            <p>Rekam medis akan muncul setelah hewan Anda diperiksa oleh dokter di RSHP.</p>
            <p><strong>Hubungi resepsionis untuk membuat jadwal pemeriksaan.</strong></p>
        </div>
    @else
        @foreach($pets as $pet)
            @if($pet->rekamMedis->count() > 0)
                @foreach($pet->rekamMedis as $rekamMedis)
                    <div class="medical-record-card">
                        <div class="record-header">
                            <div class="pet-name">
                                <i class="fas fa-paw pet-icon-header"></i>
                                {{ $pet->nama }}
                            </div>
                            <div class="record-date">
                                <i class="fas fa-calendar-alt"></i>
                                {{ \Carbon\Carbon::parse($rekamMedis->created_at)->format('d F Y, H:i') }}
                            </div>
                        </div>

                        <div class="pet-basic-info">
                            <div class="detail-item">
                                <div class="detail-label"><i class="fas fa-tag"></i> Jenis Hewan</div>
                                <div class="detail-value">{{ $pet->rasHewan->jenisHewan->nama_jenis_hewan ?? 'Tidak diketahui' }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label"><i class="fas fa-dna"></i> Ras</div>
                                <div class="detail-value">{{ $pet->rasHewan->nama_ras ?? 'Tidak diketahui' }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label"><i class="fas fa-user-md"></i> Dokter Pemeriksa</div>
                                <div class="detail-value">
                                    {{ $rekamMedis->dokterPemeriksa?->user?->nama ?? 'Tidak diketahui' }}
                                </div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label"><i class="fas fa-palette"></i> Warna/Tanda</div>
                                <div class="detail-value">
                                    {{ !empty($pet->warna_tanda) ? $pet->warna_tanda : 'Tidak tercatat' }}
                                </div>
                            </div>
                        </div>

                        @if(!empty($rekamMedis->anamnesa))
                            <div class="medical-section section-orange">
                                <div class="section-header">
                                    <div class="section-icon">
                                        <i class="fas fa-comments"></i>
                                    </div>
                                    <h3 class="section-title">Anamnesis</h3>
                                </div>
                                <div class="section-content">{{ $rekamMedis->anamnesa }}</div>
                            </div>
                        @endif

                        @if(!empty($rekamMedis->diagnosa))
                            <div class="medical-section section-green">
                                <div class="section-header">
                                    <div class="section-icon">
                                        <i class="fas fa-stethoscope"></i>
                                    </div>
                                    <h3 class="section-title">Diagnosis</h3>
                                </div>
                                <div class="section-content">{{ $rekamMedis->diagnosa }}</div>
                            </div>
                        @endif

                        @if(!empty($rekamMedis->temuan_klinis))
                            <div class="medical-section section-blue">
                                <div class="section-header">
                                    <div class="section-icon">
                                        <i class="fas fa-prescription-bottle-alt"></i>
                                    </div>
                                    <h3 class="section-title">Temuan Klinis</h3>
                                </div>
                                <div class="section-content">{{ $rekamMedis->temuan_klinis }}</div>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        @endforeach
    @endif
</div>
@endsection
