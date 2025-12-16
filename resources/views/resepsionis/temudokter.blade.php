@extends('layouts.resepsionis')

@section('title', 'Temu Dokter')

@section('page-title', 'Temu Dokter')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Temu Dokter</h1>
                <div>
                    <a href="{{ route('resepsionis.temudokter.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Jadwal Baru
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Daftar Antrian -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Antrian Temu Dokter Hari Ini (<span class="text-black">{{ now()->format('d-m-Y') }}</span>)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col" class="text-center">Tanggal</th>
                                    <th scope="col" class="text-center">Waktu</th>
                                    <th scope="col" class="text-center">Pemilik</th>
                                    <th scope="col" class="text-center">Pet</th>
                                    <th scope="col" class="text-center">Dokter</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($antrianList as $antrian)
                                <tr class="{{ $antrian->status == '2' ? 'table-success opacity-75' : '' }}">
                                    <td class="text-center">{{ $antrian->no_urut }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($antrian->waktu_daftar)->format('d-m-Y') }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($antrian->waktu_daftar)->format('H:i') }}</td>
                                    <td class="text-center">{{ $antrian->nama_pemilik }}</td>
                                    <td class="text-center">{{ $antrian->nama_pet }}</td>
                                    <td class="text-center">{{ $antrian->nama_dokter }}</td>
                                    <td class="text-center">
                                        @if($antrian->status == '1')
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock me-1"></i>Menunggu
                                            </span>
                                        @elseif($antrian->status == '2')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Selesai
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($antrian->status == '1')
                                            <form method="POST" action="{{ route('resepsionis.temudokter.complete') }}" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="no_urut" value="{{ $antrian->no_urut }}">
                                                <button type="submit" class="btn btn-success btn-sm" 
                                                        onclick="return confirm('Tandai sebagai selesai?')">
                                                    <i class="fas fa-check"></i> Selesai
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle"></i> Sudah Selesai
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <div class="py-4">
                                            <i class="fas fa-calendar-x text-muted" style="font-size: 3rem;"></i>
                                            <p class="text-muted mt-2">Belum ada antrian untuk hari ini</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Antrian Tertunda dari Hari Sebelumnya -->
    @if($expiredList->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-warning border-warning d-flex align-items-center" role="alert" style="background-color: #fff3cd;">
                <i class="fas fa-exclamation-triangle me-3 text-warning" style="font-size: 1.5rem;"></i>
                <div>
                    <strong class="text-dark">Antrian Tertunda dari Hari Sebelumnya ({{ $expiredList->count() }} antrian)</strong>
                    <p class="mb-0 mt-1 text-dark">Perhatian: Ada antrian dari hari sebelumnya yang masih menunggu. Silakan koordinasi dengan Perawat untuk menangani.</p>
                </div>
            </div>
            
            <div class="card border-warning" style="border-width: 2px;">
                <div class="card-header" style="background-color: #fff3cd; border-bottom: 2px solid #ffc107;">
                    <h5 class="card-title mb-0" style="color: #856404;">
                        <i class="fas fa-exclamation-triangle me-2"></i>Antrian Kadaluwarsa
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead style="background-color: #fff3cd;">
                                <tr>
                                    <th scope="col" class="text-center" style="color: #856404;">Tanggal</th>
                                    <th scope="col" class="text-center" style="color: #856404;">#</th>
                                    <th scope="col" class="text-center" style="color: #856404;">Waktu</th>
                                    <th scope="col" class="text-center" style="color: #856404;">Pemilik</th>
                                    <th scope="col" class="text-center" style="color: #856404;">Pet</th>
                                    <th scope="col" class="text-center" style="color: #856404;">Dokter</th>
                                    <th scope="col" class="text-center" style="color: #856404;">Status</th>
                                    <th scope="col" class="text-center" style="color: #856404;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expiredList as $antrian)
                                <tr style="background-color: #fffbf0;">
                                    <td class="text-center">
                                        <strong style="color: #856404;">{{ \Carbon\Carbon::parse($antrian->waktu_daftar)->format('d-m-Y') }}</strong><br>
                                        <small class="badge" style="background-color: #ffc107; color: #000;">{{ \Carbon\Carbon::parse($antrian->waktu_daftar)->diffForHumans() }}</small>
                                    </td>
                                    <td class="text-center">{{ $antrian->no_urut }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($antrian->waktu_daftar)->format('H:i') }}</td>
                                    <td class="text-center">{{ $antrian->nama_pemilik }}</td>
                                    <td class="text-center">{{ $antrian->nama_pet }}</td>
                                    <td class="text-center">{{ $antrian->nama_dokter }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-clock me-1"></i>TERTUNDA
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <form method="POST" action="{{ route('resepsionis.temudokter.complete') }}" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="no_urut" value="{{ $antrian->no_urut }}">
                                            <button type="submit" class="btn btn-success btn-sm" 
                                                    onclick="return confirm('Tandai sebagai selesai?')" title="Selesaikan">
                                                <i class="fas fa-check"></i> Selesaikan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Informasi -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="d-flex align-items-start mb-3">
                        <i class="fas fa-info-circle text-primary fs-4 me-3 mt-1"></i>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold text-dark mb-2">Informasi:</h6>
                            <p class="text-muted mb-3">Sistem antrian temu dokter menampilkan daftar antrian untuk hari ini. Nomor urut akan otomatis bertambah setiap ada pendaftaran baru.</p>
                            <p class="fw-bold text-dark mb-2">Status:</p>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <span class="badge bg-warning"><i class="fas fa-clock me-1"></i>MENUNGGU</span>
                                    <span class="text-muted ms-2">- Antrian aktif, dapat diklik "Selesai"</span>
                                </li>
                                <li>
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>SELESAI</span>
                                    <span class="text-muted ms-2">- Antrian sudah diselesaikan (data tersimpan untuk rekam historis)</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
