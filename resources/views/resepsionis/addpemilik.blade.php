@extends('layouts.resepsionis')

@section('title', 'Tambah Pemilik')
@section('page-title', 'Tambah Pemilik')

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
    <div class="col-12 col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="fas fa-user-plus me-2"></i>
                <h5 class="mb-0">Tambah Pemilik Baru</h5>
            </div>
            <div class="card-body">
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
                            <i class="fas fa-user-plus me-2"></i>Buat User & Pemilik Baru
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="btn-existing" onclick="selectMethod('existing')">
                            <i class="fas fa-user-check me-2"></i>Gunakan User yang Ada
                        </button>
                    </div>
                </div>
                
                <!-- Create New User Form -->
                <div id="form-new">
                    <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-file-alt me-2"></i>Formulir User & Pemilik Baru</h6>
                    <form action="{{ route('resepsionis.pemilik.storeNew') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}" required placeholder="Masukkan nama lengkap">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required placeholder="contoh@email.com">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="no_wa" class="form-label">Nomor WhatsApp</label>
                                    <input type="text" class="form-control" id="no_wa" name="no_wa" value="{{ old('no_wa') }}" placeholder="08xxxxxxxxxx" pattern="^08[0-9]{8,11}$">
                                    <small class="text-muted">Format: 08xxxxxxxxxx</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <input type="text" class="form-control" id="alamat" name="alamat" value="{{ old('alamat') }}" placeholder="Alamat lengkap">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password" name="password" required placeholder="Minimal 6 karakter" minlength="6">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Ketik ulang password">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Buat User & Pemilik
                            </button>
                            <a href="{{ route('resepsionis.pemilik.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Use Existing User Form -->
                <div id="form-existing" style="display: none;">
                    <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-user-check me-2"></i>Gunakan User yang Ada</h6>
                    <form action="{{ route('resepsionis.pemilik.storeExisting') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="iduser" class="form-label">Pilih User <span class="text-danger">*</span></label>
                            <select name="iduser" id="iduser" class="form-select" required>
                                <option value="">-- Pilih User --</option>
                                @foreach($existingUsers as $user)
                                    <option value="{{ $user->iduser }}">
                                        {{ $user->nama }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hanya user yang belum menjadi pemilik yang ditampilkan</small>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="no_wa_existing" class="form-label">Nomor WhatsApp</label>
                                    <input type="text" class="form-control" id="no_wa_existing" name="no_wa" placeholder="08xxxxxxxxxx" pattern="^08[0-9]{8,11}$">
                                    <small class="text-muted">Format: 08xxxxxxxxxx</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="alamat_existing" class="form-label">Alamat</label>
                                    <input type="text" class="form-control" id="alamat_existing" name="alamat" placeholder="Alamat lengkap">
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Jadikan Pemilik
                            </button>
                            <a href="{{ route('resepsionis.pemilik.index') }}" class="btn btn-secondary">
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
