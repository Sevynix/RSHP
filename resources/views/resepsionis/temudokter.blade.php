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
    
    <!-- Informasi -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info">
                <h6 class="alert-heading">Informasi:</h6>
                <p class="mb-2">Sistem antrian temu dokter menampilkan daftar antrian untuk hari ini. Nomor urut akan otomatis bertambah setiap ada pendaftaran baru.</p>
                <p class="mb-0"><strong>Status:</strong></p>
                <ul class="mb-0 mt-1">
                    <li><span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Menunggu</span> - Antrian aktif, dapat diklik "Selesai"</li>
                    <li><span class="badge bg-success"><i class="fas fa-check"></i> Selesai</span> - Antrian sudah diselesaikan (data tersimpan untuk rekam historis)</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
