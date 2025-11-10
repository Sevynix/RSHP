@extends('layouts.main')

@section('title', 'Data Rekam Medis')
@section('page-title', 'Data Rekam Medis')
@section('user-role', 'Dokter')

@section('sidebar-menu')
    <li class="{{ request()->routeIs('dokter.dashboard') ? 'active' : '' }}">
        <a href="{{ route('dokter.dashboard') }}">
            <i class="fas fa-tachometer-alt"></i>
            <span class="nav-text">Dashboard</span>
        </a>
    </li>
    <li class="{{ request()->routeIs('dokter.rekammedis.*') ? 'active' : '' }}">
        <a href="{{ route('dokter.rekammedis.index') }}">
            <i class="fas fa-file-medical"></i>
            <span class="nav-text">Rekam Medis</span>
        </a>
    </li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fs-4 mb-0">
            <i class="fas fa-file-medical-alt me-2"></i>Rekam Medis - Dokter
        </h3>
    </div>

    <!-- Search and Filter Section -->
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('dokter.rekammedis.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Pencarian</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="Cari berdasarkan anamnesa, diagnosa, nama pet, atau pemilik..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label for="date_from" class="form-label">Tanggal Dari</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" 
                           value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label">Tanggal Sampai</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" 
                           value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary flex-fill">
                            <i class="fas fa-search me-1"></i>Cari
                        </button>
                        <a href="{{ route('dokter.rekammedis.index') }}" class="btn btn-outline-secondary flex-fill" title="Reset Filter">
                            <i class="fas fa-undo me-1"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Reservasi ID</th>
                            <th>Tanggal</th>
                            <th>Pet</th>
                            <th>Pemilik</th>
                            <th>Diagnosa</th>
                            <th>Dokter</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records as $record)
                        <tr>
                            <td>{{ $record->idrekam_medis }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $record->idreservasi_dokter ?? 'N/A' }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($record->created_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                <strong>{{ $record->nama_pet ?? 'N/A' }}</strong><br>
                                <small class="text-muted">{{ $record->ras ?? '' }}</small>
                            </td>
                            <td>{{ $record->nama_pemilik ?? 'N/A' }}</td>
                            <td>
                                <span class="text-truncate d-block" style="max-width: 200px;" title="{{ $record->diagnosa }}">
                                    {{ $record->diagnosa }}
                                </span>
                            </td>
                            <td>{{ $record->nama_dokter_pemeriksa ?? 'Dr. ' . ($record->dokter_pemeriksa ?? 'N/A') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('dokter.rekammedis.show', $record->idrekam_medis) }}" 
                                       class="btn btn-sm btn-info" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center p-4">
                                <i class="fas fa-file-medical fa-3x text-muted mb-3 d-block"></i>
                                Tidak ada data rekam medis.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
