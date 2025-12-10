@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')
    <section class="hero-home">
        <div class="hero-content">
            <div class="hero-text">
                <div class="hero-badge">
                    <i class="fas fa-award"></i>
                    <span>Akreditasi A</span>
                </div>
                <h1 class="hero-title">
                    <span class="gradient-text">Rumah Sakit Hewan Pendidikan</span>
                    <br>Universitas Airlangga
                </h1>
                <p class="hero-subtitle">Pusat Pelayanan Kesehatan Hewan Terpercaya dengan Standar Medis Tinggi, Didukung oleh Tenaga Profesional dan Fasilitas Modern</p>
                
            </div>
            <div class="hero-image">
                <img src="{{ asset('images/RSHP.WEBP') }}" alt="RSHP Unair">
            </div>
        </div>
    </section>

    <section class="stats-section">
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-md"></i>
                </div>
                <h3 class="stat-number">25+</h3>
                <p class="stat-label">Dokter Hewan Profesional</p>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-hospital"></i>
                </div>
                <h3 class="stat-number">10.000+</h3>
                <p class="stat-label">Hewan Ditangani/Tahun</p>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3 class="stat-number">24/7</h3>
                <p class="stat-label">Layanan Darurat</p>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h3 class="stat-number">15+</h3>
                <p class="stat-label">Tahun Pengalaman</p>
            </div>
        </div>
    </section>

    <section class="features-section">
        <h2 class="section-title">
            Keunggulan RSHP
        </h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-microscope"></i>
                </div>
                <h3>Laboratorium Modern</h3>
                <p>Dilengkapi peralatan diagnostik canggih untuk pemeriksaan yang akurat dan cepat</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3>Tenaga Ahli Bersertifikat</h3>
                <p>Dokter hewan dan staff medis berpengalaman dengan kualifikasi tinggi</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <h3>Perawatan Komprehensif</h3>
                <p>Mulai dari konsultasi, diagnostik, hingga perawatan intensif dan bedah</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3>Sistem Digital Terintegrasi</h3>
                <p>Rekam medis digital dan sistem reservasi online untuk kemudahan Anda</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <h3>Pelayanan Ramah</h3>
                <p>Staff yang peduli dan perhatian terhadap kesejahteraan hewan kesayangan Anda</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <h3>Standar Internasional</h3>
                <p>Menerapkan protokol medis dan standar pelayanan bertaraf internasional</p>
            </div>
        </div>
    </section>

    <section class="services-preview">
        <h2 class="section-title">
            <i class="fas fa-stethoscope"></i> Layanan Unggulan Kami
        </h2>
        <div class="services-grid">
            <div class="service-preview-card">
                <div class="service-image">
                    <i class="fas fa-syringe"></i>
                </div>
                <h3>Vaksinasi</h3>
                <p>Program vaksinasi lengkap untuk melindungi hewan dari penyakit menular</p>
                <a href="/layanan" class="learn-more">Pelajari Lebih Lanjut →</a>
            </div>
            <div class="service-preview-card">
                <div class="service-image">
                    <i class="fas fa-cut"></i>
                </div>
                <h3>Bedah</h3>
                <p>Fasilitas operasi modern dengan tim bedah berpengalaman</p>
                <a href="/layanan" class="learn-more">Pelajari Lebih Lanjut →</a>
            </div>
            <div class="service-preview-card">
                <div class="service-image">
                    <i class="fas fa-x-ray"></i>
                </div>
                <h3>Radiologi</h3>
                <p>Pemeriksaan X-Ray dan USG untuk diagnosis yang tepat</p>
                <a href="/layanan" class="learn-more">Pelajari Lebih Lanjut →</a>
            </div>
        </div>
    </section>
@endsection