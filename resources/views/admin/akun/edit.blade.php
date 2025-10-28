@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="bi bi-pencil me-2"></i>Edit Akun: {{ $akun->nama }}
                    </h3>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="bi bi-exclamation-triangle me-2"></i>Error Validasi:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.akun.update', $akun->id) }}" method="POST" id="form-update-akun">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Jurusan/Ormawa <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                           id="nama" name="nama" value="{{ old('nama', $akun->nama) }}" required>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tipe" class="form-label">Tipe <span class="text-danger">*</span></label>
                                    <select class="form-select @error('tipe') is-invalid @enderror" id="tipe" name="tipe" required>
                                        <option value="">Pilih Tipe</option>
                                        <option value="ormawa" {{ old('tipe', $akun->tipe) == 'ormawa' ? 'selected' : '' }}>Ormawa</option>
                                        <option value="jurusan" {{ old('tipe', $akun->tipe) == 'jurusan' ? 'selected' : '' }}>Jurusan</option>
                                    </select>
                                    @error('tipe')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Password Section -->
                        <div class="card mb-3 border-warning">
                            <div class="card-header bg-warning bg-opacity-10">
                                <h6 class="mb-0"><i class="bi bi-key me-2"></i>Pengaturan Password</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Password Saat Ini</label>
                                            <div class="input-group">
                                                <input type="password" 
                                                       class="form-control bg-light" 
                                                       id="current-password" 
                                                       value="{{ $akun->password }}" 
                                                       readonly
                                                       style="font-family: monospace;">
                                                <button class="btn btn-outline-secondary" 
                                                        type="button" 
                                                        onclick="toggleCurrentPassword()"
                                                        title="Tampilkan/Sembunyikan Password">
                                                    <i class="bi bi-eye" id="eye-current"></i>
                                                </button>
                                            </div>
                                            <small class="text-muted">
                                                <i class="bi bi-info-circle me-1"></i>Password ini yang digunakan untuk login
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password Baru <small class="text-muted">(kosongkan jika tidak ingin mengubah)</small></label>
                                            <div class="input-group">
                                                <input type="password" 
                                                       class="form-control @error('password') is-invalid @enderror" 
                                                       id="password" 
                                                       name="password"
                                                       placeholder="Masukkan password baru">
                                                <button class="btn btn-outline-secondary" 
                                                        type="button" 
                                                        onclick="toggleNewPassword()"
                                                        title="Tampilkan/Sembunyikan Password">
                                                    <i class="bi bi-eye" id="eye-new"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">
                                                <i class="bi bi-shield-check me-1"></i>Minimal 6 karakter
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $akun->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="no_telp" class="form-label">No. Telepon</label>
                                    <input type="text" class="form-control @error('no_telp') is-invalid @enderror" 
                                           id="no_telp" name="no_telp" value="{{ old('no_telp', $akun->no_telp) }}">
                                    @error('no_telp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               {{ old('is_active', $akun->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Akun Aktif
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                      id="alamat" name="alamat" rows="3">{{ old('alamat', $akun->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.akun.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
console.log('Password toggle script loaded');

function toggleCurrentPassword() {
    console.log('toggleCurrentPassword called');
    const passwordField = document.getElementById('current-password');
    const eyeIcon = document.getElementById('eye-current');
    
    console.log('Password field:', passwordField);
    console.log('Eye icon:', eyeIcon);
    
    if (passwordField && eyeIcon) {
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.className = 'bi bi-eye-slash';
            console.log('Password shown');
        } else {
            passwordField.type = 'password';
            eyeIcon.className = 'bi bi-eye';
            console.log('Password hidden');
        }
    } else {
        console.error('Elements not found!');
    }
}

function toggleNewPassword() {
    console.log('toggleNewPassword called');
    const passwordField = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-new');
    
    console.log('Password field:', passwordField);
    console.log('Eye icon:', eyeIcon);
    
    if (passwordField && eyeIcon) {
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.className = 'bi bi-eye-slash';
            console.log('Password shown');
        } else {
            passwordField.type = 'password';
            eyeIcon.className = 'bi bi-eye';
            console.log('Password hidden');
        }
    } else {
        console.error('Elements not found!');
    }
}

// Test jika DOM sudah ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Ready - Password toggle ready');
    console.log('Current password field:', document.getElementById('current-password'));
    console.log('New password field:', document.getElementById('password'));
    
    // Monitor form submission
    const form = document.getElementById('form-update-akun');
    if (form) {
        form.addEventListener('submit', function(e) {
            const passwordField = document.getElementById('password');
            const passwordValue = passwordField ? passwordField.value : '';
            
            console.log('=== FORM SUBMITTED ===');
            console.log('Password field value:', passwordValue);
            console.log('Password field filled:', passwordValue !== '');
            console.log('Password length:', passwordValue.length);
            
            if (passwordValue !== '' && passwordValue.length < 6) {
                e.preventDefault();
                alert('Password minimal 6 karakter!');
                return false;
            }
            
            if (passwordValue !== '') {
                if (!confirm('Anda yakin ingin mengubah password menjadi: ' + passwordValue + ' ?')) {
                    e.preventDefault();
                    return false;
                }
            }
        });
    }
});
</script>
@endpush
