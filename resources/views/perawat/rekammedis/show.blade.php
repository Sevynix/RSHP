@extends('layouts.main')

@section('title', 'Detail Rekam Medis')
@section('page-title', 'Detail Rekam Medis')
@section('user-role', 'Perawat')

@section('sidebar-menu')
    <li class="{{ request()->routeIs('perawat.dashboard') ? 'active' : '' }}">
        <a href="{{ route('perawat.dashboard') }}">
            <i class="fas fa-tachometer-alt"></i>
            <span class="nav-text">Dashboard</span>
        </a>
    </li>
    <li class="{{ request()->routeIs('perawat.data-pasien') ? 'active' : '' }}">
        <a href="{{ route('perawat.data-pasien') }}">
            <i class="fas fa-paw"></i>
            <span class="nav-text">Data Pasien</span>
        </a>
    </li>
    <li class="{{ request()->routeIs('perawat.rekammedis.*') ? 'active' : '' }}">
        <a href="{{ route('perawat.rekammedis.index') }}">
            <i class="fas fa-file-medical"></i>
            <span class="nav-text">Rekam Medis</span>
        </a>
    </li>
    <li class="{{ request()->routeIs('perawat.profil*') ? 'active' : '' }}">
        <a href="{{ route('perawat.profil') }}">
            <i class="fas fa-user-circle"></i>
            <span class="nav-text">Profil</span>
        </a>
    </li>
@endsection

@section('sidebar-menu')
    <li class="{{ request()->routeIs('perawat.dashboard') ? 'active' : '' }}">
        <a href="{{ route('perawat.dashboard') }}">
            <i class="fas fa-tachometer-alt"></i>
            <span class="nav-text">Dashboard</span>
        </a>
    </li>
    <li class="{{ request()->routeIs('perawat.rekammedis.*') ? 'active' : '' }}">
        <a href="{{ route('perawat.rekammedis.index') }}">
            <i class="fas fa-file-medical"></i>
            <span class="nav-text">Rekam Medis</span>
        </a>
    </li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-file-medical me-2"></i>
                    Detail Rekam Medis
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Informasi Pasien</h6>
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td class="fw-bold">ID Reservasi:</td>
                                <td><span class="badge bg-info">{{ $record->idreservasi_dokter }}</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Nama Pet:</td>
                                <td>{{ $record->nama_pet ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Ras:</td>
                                <td>{{ $record->ras ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Pemilik:</td>
                                <td>{{ $record->nama_pemilik ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">Informasi Pemeriksaan</h6>
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td class="fw-bold">Tanggal Periksa:</td>
                                <td>{{ \Carbon\Carbon::parse($record->created_at)->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Dokter Pemeriksa:</td>
                                <td>{{ $record->nama_dokter_pemeriksa ?? 'Dr. ' . ($record->dokter_pemeriksa ?? 'N/A') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Waktu Reservasi:</td>
                                <td>{{ $record->waktu_daftar ? \Carbon\Carbon::parse($record->waktu_daftar)->format('d/m/Y H:i') : '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-12 mb-3">
                        <h6 class="text-primary">Anamnesa</h6>
                        <div class="bg-light p-3 rounded">
                            {{ $record->anamnesa }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <h6 class="text-primary">Temuan Klinis</h6>
                        <div class="bg-light p-3 rounded">
                            {{ $record->temuan_klinis }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <h6 class="text-primary">Diagnosa</h6>
                        <div class="bg-light p-3 rounded">
                            {{ $record->diagnosa }}
                        </div>
                    </div>
                </div>

                <!-- Detail Tindakan -->
                @if($details->count() > 0)
                <div class="row">
                    <div class="col-12 mb-3">
                        <h6 class="text-primary">Detail Tindakan</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kode Tindakan</th>
                                        <th>Nama Tindakan</th>
                                        <th>Kategori</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($details as $detail)
                                    <tr>
                                        <td><span class="badge bg-dark fs-6 px-3 py-2">{{ $detail->kode ?? 'N/A' }}</span></td>
                                        <td>{{ $detail->deskripsi_tindakan_terapi ?? 'N/A' }}</td>
                                        <td><span class="badge bg-secondary">{{ $detail->nama_kategori ?? 'N/A' }}</span></td>
                                        <td>
                                            @if(!empty($detail->detail))
                                                <small class="text-muted">{{ $detail->detail }}</small>
                                            @else
                                                <small class="text-muted">-</small>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <hr class="my-4">

                <div class="row">
                    <div class="col-12">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            Dibuat pada: {{ \Carbon\Carbon::parse($record->created_at)->format('d/m/Y H:i:s') }}
                        </small>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('perawat.rekammedis.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                    </a>
                    <div>
                        <a href="{{ route('perawat.rekammedis.edit', $record->idrekam_medis) }}" 
                           class="btn btn-warning me-2">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <button onclick="window.print()" class="btn btn-info">
                            <i class="fas fa-print me-2"></i>Cetak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
