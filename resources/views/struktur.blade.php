@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/struktur.css') }}">
@endpush

@section('content')
    <section id="struktur">
        <h2><i class="fas fa-sitemap"></i> Struktur Organisasi RSHP</h2>
        
        <div class="struktur-container">
            <div class="struktur-director">
                <div class="struktur-card director">
                    <div class="struktur-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="struktur-photo">
                        <img src="{{ asset('images/direktur.png') }}" alt="Direktur">
                    </div>
                    <h3>Direktur</h3>
                    <p class="name">Dr. Ira Sari Yudaniyanti, M.P., drh.</p>
                    <p class="role">Pemimpin Umum RSHP</p>
                </div>
            </div>

            <div class="struktur-wakil">
                <div class="struktur-card vice-director">
                    <div class="struktur-icon">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <div class="struktur-photo">
                        <img src="{{ asset('images/wakil1.png') }}" alt="Wakil Direktur 1">
                    </div>
                    <h3>Wakil Direktur 1</h3>
                    <p class="subtitle">Pelayanan Medis, Pendidikan dan Penelitian</p>
                    <p class="name">Dr. Nusdianto Triakoso, M.P., drh.</p>
                </div>

                <div class="struktur-card vice-director">
                    <div class="struktur-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <div class="struktur-photo">
                        <img src="{{ asset('images/wakil2.png') }}" alt="Wakil Direktur 2">
                    </div>
                    <h3>Wakil Direktur 2</h3>
                    <p class="subtitle">SDM, Sarana Prasarana, dan Keuangan</p>
                    <p class="name">Dr. Miyayu Soneta S., M.Vet., drh.</p>
                </div>
            </div>

            <div class="struktur-divisions">
                <div class="struktur-card division">
                    <div class="struktur-icon small">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h4>Divisi Medis</h4>
                    <p class="desc">Pelayanan medis, rawat jalan, rawat inap, dan bedah</p>
                </div>

                <div class="struktur-card division">
                    <div class="struktur-icon small">
                        <i class="fas fa-microscope"></i>
                    </div>
                    <h4>Divisi Laboratorium</h4>
                    <p class="desc">Pemeriksaan diagnostik dan analisis laboratorium</p>
                </div>

                <div class="struktur-card division">
                    <div class="struktur-icon small">
                        <i class="fas fa-book-medical"></i>
                    </div>
                    <h4>Divisi Akademik</h4>
                    <p class="desc">Pendidikan, pelatihan, dan penelitian veteriner</p>
                </div>

                <div class="struktur-card division">
                    <div class="struktur-icon small">
                        <i class="fas fa-user-nurse"></i>
                    </div>
                    <h4>Divisi Keperawatan</h4>
                    <p class="desc">Perawatan pasien dan administrasi medis</p>
                </div>
            </div>
        </div>
    </section>
@endsection
