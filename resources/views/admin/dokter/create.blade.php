@extends('layouts.admin')

@section('title', 'Tambah Dokter')
@section('page-title', 'Tambah Dokter')

@push('scripts')
<script>
function selectMethod(method) {
    // Hide both forms
    document.getElementById('form-new').style.display = 'none';
    document.getElementById('form-existing').style.display = 'none';
    
    // Reset button styles
    document.getElementById('btn-new').className = 'btn btn-outline-primary';
    document.getElementById('btn-existing').className = 'btn btn-outline-secondary';
    
    if (method === 'new') {
        document.getElementById('form-new').style.display = 'block';
        document.getElementById('btn-new').className = 'btn btn-primary';
    } else {
        document.getElementById('form-existing').style.display = 'block';
        document.getElementById('btn-existing').className = 'btn btn-secondary';
    }
}

// Show new form by default
document.addEventListener('DOMContentLoaded', function() {
    selectMethod('new');
});
</script>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-10">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="fas fa-user-md me-2"></i>
                <h5 class="mb-0">Tambah Dokter Baru</h5>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <x-alert type="error" :message="session('error')" />
                @endif

                @if($errors->any())
                    <div class="alert alert-danger d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <div>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                
                <!-- Method Selection -->
                <div class="mb-3">
                    <label class="form-label">Pilih Metode Penambahan:</label>
                    <div class="d-flex gap-3">
                        <button type="button" class="btn btn-outline-primary" id="btn-new" onclick="selectMethod('new')">
                            <i class="fas fa-user-plus me-2"></i>Buat User & Dokter Baru
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="btn-existing" onclick="selectMethod('existing')">
                            <i class="fas fa-user-check me-2"></i>Gunakan User yang Ada
                        </button>
                    </div>
                </div>
                
                <!-- Create New User Form -->
                <div id="form-new">
                    <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-file-alt me-2"></i>Formulir User & Dokter Baru</h6>
                    <form action="{{ route('admin.dokter.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required placeholder="Masukkan nama lengkap">
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="contoh@email.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Minimal 6 karakter" minlength="6">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="no_hp" class="form-label">No HP <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" required placeholder="08xxxxxxxxxx">
                                    @error('no_hp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bidang_dokter" class="form-label">Bidang Dokter <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('bidang_dokter') is-invalid @enderror" id="bidang_dokter" name="bidang_dokter" value="{{ old('bidang_dokter') }}" required placeholder="Dokter Hewan Umum, Spesialis, dll">
                                    @error('bidang_dokter')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3" required placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Buat User & Dokter
                            </button>
                            <a href="{{ route('admin.dokter.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Use Existing User Form -->
                <div id="form-existing" style="display: none;">
                    <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-user-check me-2"></i>Gunakan User yang Ada</h6>
                    <form action="{{ route('admin.dokter.store-existing') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="iduser" class="form-label">Pilih User <span class="text-danger">*</span></label>
                            <select name="iduser" id="iduser" class="form-select @error('iduser') is-invalid @enderror" required>
                                <option value="">-- Pilih User --</option>
                                @foreach($availableUsers as $user)
                                    <option value="{{ $user->iduser }}" {{ old('iduser') == $user->iduser ? 'selected' : '' }}>
                                        {{ $user->nama }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hanya user yang belum menjadi dokter yang ditampilkan</small>
                            @error('iduser')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="no_hp_existing" class="form-label">No HP <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp_existing" name="no_hp" value="{{ old('no_hp') }}" required placeholder="08xxxxxxxxxx">
                                    @error('no_hp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bidang_dokter_existing" class="form-label">Bidang Dokter <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('bidang_dokter') is-invalid @enderror" id="bidang_dokter_existing" name="bidang_dokter" value="{{ old('bidang_dokter') }}" required placeholder="Dokter Hewan Umum, Spesialis, dll">
                                    @error('bidang_dokter')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jenis_kelamin_existing" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select name="jenis_kelamin" id="jenis_kelamin_existing" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="alamat_existing" class="form-label">Alamat <span class="text-danger">*</span></label>
                                    <textarea name="alamat" id="alamat_existing" class="form-control @error('alamat') is-invalid @enderror" rows="3" required placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                                    @error('alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Jadikan Dokter
                            </button>
                            <a href="{{ route('admin.dokter.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
