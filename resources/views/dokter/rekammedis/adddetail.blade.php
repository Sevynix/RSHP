@extends('layouts.main')

@section('title', 'Tambah Detail Tindakan')
@section('page-title', 'Tambah Detail Tindakan')
@section('user-role', 'Dokter')

@section('sidebar-menu')
    <li class="{{ request()->routeIs('dokter.dashboard') ? 'active' : '' }}">
        <a href="{{ route('dokter.dashboard') }}">
            <i class="fas fa-tachometer-alt"></i>
            <span class="nav-text">Dashboard</span>
        </a>
    </li>
    <li class="{{ request()->routeIs('dokter.data-pasien') ? 'active' : '' }}">
        <a href="{{ route('dokter.data-pasien') }}">
            <i class="fas fa-paw"></i>
            <span class="nav-text">Data Pasien</span>
        </a>
    </li>
    <li class="{{ request()->routeIs('dokter.rekammedis.*') ? 'active' : '' }}">
        <a href="{{ route('dokter.rekammedis.index') }}">
            <i class="fas fa-file-medical"></i>
            <span class="nav-text">Rekam Medis</span>
        </a>
    </li>
    <li class="{{ request()->routeIs('dokter.profil*') ? 'active' : '' }}">
        <a href="{{ route('dokter.profil') }}">
            <i class="fas fa-user-circle"></i>
            <span class="nav-text">Profil</span>
        </a>
    </li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Detail Tindakan
                    </h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Informasi Rekam Medis -->
                    <div class="alert alert-info mb-3">
                        <h6><i class="fas fa-file-medical me-2"></i>Informasi Rekam Medis</h6>
                        <p class="mb-1"><strong>ID:</strong> {{ $record->idrekam_medis }}</p>
                        <p class="mb-1"><strong>Diagnosa:</strong> {{ $record->diagnosa }}</p>
                        <p class="mb-0"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($record->created_at)->format('d/m/Y H:i') }}</p>
                    </div>

                    <!-- Filter Kategori -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="kategori_filter" class="form-label">Filter Kategori</label>
                            <select class="form-select" id="kategori_filter">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->idkategori }}">
                                        {{ $category->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih kategori untuk memfilter tindakan</small>
                        </div>
                    </div>

                    <!-- Main Form -->
                    <form action="{{ route('dokter.rekammedis.storedetail', $record->idrekam_medis) }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="idkode_tindakan_terapi" class="form-label">Kode Tindakan <span class="text-danger">*</span></label>
                                <select class="form-select @error('idkode_tindakan_terapi') is-invalid @enderror" 
                                        id="idkode_tindakan_terapi" 
                                        name="idkode_tindakan_terapi" required>
                                    <option value="">Pilih Kode Tindakan</option>
                                    @foreach($treatmentCodes as $code)
                                        <option value="{{ $code->idkode_tindakan_terapi }}"
                                                data-kategori="{{ $code->idkategori }}"
                                                {{ old('idkode_tindakan_terapi') == $code->idkode_tindakan_terapi ? 'selected' : '' }}>
                                            {{ $code->kode }} - {{ $code->deskripsi_tindakan_terapi }}
                                            @if($code->kategori)
                                                ({{ $code->kategori->nama_kategori }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div id="filter-info" class="text-muted small mt-1" style="display: none;">
                                    Menampilkan <span id="filtered-count">0</span> dari {{ count($treatmentCodes) }} kode tindakan
                                </div>
                                @error('idkode_tindakan_terapi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="detail" class="form-label">Detail Tindakan</label>
                                <textarea class="form-control @error('detail') is-invalid @enderror" 
                                          id="detail" 
                                          name="detail" 
                                          rows="3"
                                          placeholder="Tambahkan detail atau catatan khusus untuk tindakan ini (opsional)...">{{ old('detail') }}</textarea>
                                <small class="text-muted">Opsional: Berisi catatan tambahan atau detail khusus untuk tindakan ini</small>
                                @error('detail')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('dokter.rekammedis.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Detail
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const kategoriFilter = document.getElementById('kategori_filter');
    const kodeTindakanSelect = document.getElementById('idkode_tindakan_terapi');
    const filterInfo = document.getElementById('filter-info');
    const filteredCount = document.getElementById('filtered-count');
    
    // Simpan semua option asli
    const allOptions = Array.from(kodeTindakanSelect.options);
    
    kategoriFilter.addEventListener('change', function() {
        const selectedKategori = this.value;
        
        // Reset select
        kodeTindakanSelect.innerHTML = '<option value="">Pilih Kode Tindakan</option>';
        
        let count = 0;
        
        // Filter dan tambahkan option yang sesuai
        allOptions.forEach(option => {
            if (option.value === '') return; // Skip placeholder
            
            const optionKategori = option.getAttribute('data-kategori');
            
            // Tampilkan jika tidak ada filter atau kategori cocok
            if (!selectedKategori || optionKategori === selectedKategori) {
                kodeTindakanSelect.appendChild(option.cloneNode(true));
                count++;
            }
        });
        
        // Update info
        if (selectedKategori) {
            filteredCount.textContent = count;
            filterInfo.style.display = 'block';
            
            if (count === 0) {
                const noDataOption = document.createElement('option');
                noDataOption.value = '';
                noDataOption.disabled = true;
                noDataOption.textContent = 'Tidak ada kode tindakan untuk kategori ini';
                kodeTindakanSelect.appendChild(noDataOption);
            }
        } else {
            filterInfo.style.display = 'none';
        }
    });
});
</script>
@endpush
@endsection
