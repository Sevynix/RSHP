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
<style>
.medical-record-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    margin-bottom: 25px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: 1px solid #e9ecef;
}

.medical-record-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.record-header {
    border-bottom: 3px solid #4CAF50;
    padding-bottom: 20px;
    margin-bottom: 20px;
    position: relative;
}

.pet-name {
    font-size: 1.6em;
    font-weight: 700;
    color: #2c5aa0;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}

.pet-icon {
    margin-right: 10px;
    color: #4CAF50;
}

.record-date {
    color: #6c757d;
    font-size: 1em;
    font-weight: 500;
    display: flex;
    align-items-center;
}

.record-date i {
    margin-right: 8px;
    color: #4CAF50;
}

.record-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.detail-item {
    padding: 15px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 10px;
    border-left: 4px solid #4CAF50;
    transition: background 0.2s ease;
}

.detail-item:hover {
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
}

.detail-label {
    font-weight: 700;
    color: #495057;
    margin-bottom: 8px;
    font-size: 0.9em;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-value {
    color: #212529;
    font-size: 1em;
    line-height: 1.4;
}

.medical-section {
    margin-top: 20px;
    padding: 20px;
    border-radius: 12px;
    border-left: 5px solid #4CAF50;
    position: relative;
}

.diagnosis-section {
    background: linear-gradient(135deg, #e8f5e8 0%, #d4edda 100%);
}

.therapy-section {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-left-color: #2196F3;
}

.anamnesis-section {
    background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
    border-left-color: #FF9800;
}

.notes-section {
    background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
    border-left-color: #9C27B0;
}

.section-title {
    font-weight: 700;
    color: #495057;
    margin-bottom: 12px;
    font-size: 1.1em;
    display: flex;
    align-items-center;
}

.section-title i {
    margin-right: 10px;
    font-size: 1.2em;
}

.section-content {
    color: #495057;
    line-height: 1.6;
    font-size: 1em;
}

.no-records {
    text-align: center;
    padding: 80px 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.no-records i {
    font-size: 5em;
    color: #dee2e6;
    margin-bottom: 30px;
}

.no-records h3 {
    color: #495057;
    margin-bottom: 15px;
    font-weight: 600;
}

.no-records p {
    color: #6c757d;
    font-size: 1.1em;
    line-height: 1.6;
}

.filter-tabs {
    margin-bottom: 30px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.filter-tab {
    background: white;
    border: 2px solid #e9ecef;
    color: #495057;
    padding: 10px 20px;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
}

.filter-tab:hover, .filter-tab.active {
    background: #4CAF50;
    color: white;
    border-color: #4CAF50;
    text-decoration: none;
}

@media (max-width: 768px) {
    .record-details {
        grid-template-columns: 1fr;
    }
    
    .pet-name {
        font-size: 1.3em;
    }
    
    .filter-tabs {
        justify-content: center;
    }
}
</style>

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
        
        $selectedPet = request()->get('pet', 'all');
    @endphp

    @if(count($allRecords) > 0)
        <!-- Pet Filter Tabs -->
        @if(count($recordsByPet) > 1)
        <div class="filter-tabs">
            <a href="{{ route('pemilik.rekam-medis') }}?pet=all" class="filter-tab {{ $selectedPet === 'all' ? 'active' : '' }}">
                <i class="fas fa-list"></i> Semua Hewan ({{ count($allRecords) }})
            </a>
            @foreach($recordsByPet as $petName => $records)
            <a href="{{ route('pemilik.rekam-medis') }}?pet={{ urlencode($petName) }}" class="filter-tab {{ $selectedPet === $petName ? 'active' : '' }}">
                <i class="fas fa-paw"></i> {{ $petName }} ({{ count($records) }})
            </a>
            @endforeach
        </div>
        @endif

        <!-- Medical Records -->
        @foreach($pets as $pet)
            @if($pet->rekamMedis->count() > 0)
                @foreach($pet->rekamMedis as $rekamMedis)
                    @if($selectedPet === 'all' || $selectedPet === $pet->nama)
                    <div class="medical-record-card record-item">
                        <div class="record-header">
                            <div class="pet-name">
                                <i class="fas fa-paw pet-icon"></i>
                                {{ $pet->nama }}
                            </div>
                            <div class="record-date">
                                <i class="fas fa-calendar-alt"></i>
                                {{ \Carbon\Carbon::parse($rekamMedis->created_at)->format('d F Y, H:i') }}
                            </div>
                        </div>

                        <div class="record-details">
                            <div class="detail-item">
                                <div class="detail-label"><i class="fas fa-tag"></i> Jenis Hewan</div>
                                <div class="detail-value">{{ $pet->jenisHewan->nama_jenis_hewan ?? 'Tidak diketahui' }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label"><i class="fas fa-dna"></i> Ras</div>
                                <div class="detail-value">{{ $pet->rasHewan->nama_ras ?? 'Tidak diketahui' }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label"><i class="fas fa-user-md"></i> Dokter Pemeriksa</div>
                                <div class="detail-value">{{ $rekamMedis->dokterPemeriksa->nama ?? 'Tidak diketahui' }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label"><i class="fas fa-palette"></i> Warna/Tanda</div>
                                <div class="detail-value">
                                    {{ !empty($pet->warna_tanda) ? $pet->warna_tanda : 'Tidak tercatat' }}
                                </div>
                            </div>
                        </div>

                        @if(!empty($rekamMedis->anamnesa))
                            <div class="medical-section anamnesis-section">
                                <div class="section-title">
                                    <i class="fas fa-comments"></i> Anamnesis
                                </div>
                                <div class="section-content">{!! nl2br(e($rekamMedis->anamnesa)) !!}</div>
                            </div>
                        @endif

                        @if(!empty($rekamMedis->diagnosa))
                            <div class="medical-section diagnosis-section">
                                <div class="section-title">
                                    <i class="fas fa-stethoscope"></i> Diagnosis
                                </div>
                                <div class="section-content">{!! nl2br(e($rekamMedis->diagnosa)) !!}</div>
                            </div>
                        @endif

                        @if(!empty($rekamMedis->temuan_klinis))
                            <div class="medical-section therapy-section">
                                <div class="section-title">
                                    <i class="fas fa-prescription-bottle-alt"></i> Temuan Klinis & Terapi
                                </div>
                                <div class="section-content">{!! nl2br(e($rekamMedis->temuan_klinis)) !!}</div>
                            </div>
                        @endif

                        @if($rekamMedis->detailRekamMedis->count() > 0)
                            <div class="medical-section therapy-section">
                                <div class="section-title">
                                    <i class="fas fa-notes-medical"></i> Detail Tindakan & Terapi
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered bg-white">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Tindakan/Terapi</th>
                                                <th>Kategori</th>
                                                <th>Catatan</th>
                                                <th>Biaya</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $totalBiaya = 0; @endphp
                                            @foreach($rekamMedis->detailRekamMedis as $detail)
                                                <tr>
                                                    <td>{{ $detail->kodeTindakanTerapi->nama_tindakan_terapi }}</td>
                                                    <td>{{ $detail->kategori->nama_kategori }}</td>
                                                    <td>{{ $detail->catatan ?? '-' }}</td>
                                                    <td>Rp {{ number_format($detail->biaya, 0, ',', '.') }}</td>
                                                </tr>
                                                @php $totalBiaya += $detail->biaya; @endphp
                                            @endforeach
                                            <tr class="table-light">
                                                <td colspan="3" class="text-end"><strong>Total Biaya:</strong></td>
                                                <td><strong>Rp {{ number_format($totalBiaya, 0, ',', '.') }}</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                    @endif
                @endforeach
            @endif
        @endforeach
    @else
        <div class="no-records">
            <i class="fas fa-heart-broken"></i>
            <h3>Belum Ada Rekam Medis</h3>
            <p>Hewan peliharaan Anda belum memiliki rekam medis atau riwayat pemeriksaan.</p>
            <p>Rekam medis akan muncul setelah hewan Anda diperiksa oleh dokter di RSHP.</p>
            <p><strong>Hubungi resepsionis untuk membuat jadwal pemeriksaan.</strong></p>
        </div>
    @endif
</div>
@endsection
