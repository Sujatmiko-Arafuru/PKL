@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="bi bi-person me-2"></i>Detail Akun: {{ $akun->nama }}
                    </h3>
                    <div class="btn-group">
                        <a href="{{ route('admin.akun.edit', $akun->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </a>
                        <a href="{{ route('admin.akun.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="30%"><strong>Nama:</strong></td>
                                    <td>{{ $akun->nama }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tipe:</strong></td>
                                    <td>
                                        <span class="badge {{ $akun->tipe == 'ormawa' ? 'bg-info' : 'bg-success' }}">
                                            {{ ucfirst($akun->tipe) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $akun->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>No. Telepon:</strong></td>
                                    <td>{{ $akun->no_telp ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Password:</strong></td>
                                    <td>
                                        <div class="input-group" style="max-width: 300px;">
                                            <input type="password" 
                                                   class="form-control password-field text-success" 
                                                   value="{{ $akun->getPasswordForAdmin() }}" 
                                                   readonly 
                                                   id="password-detail"
                                                   style="font-family: monospace;">
                                            <button class="btn btn-outline-secondary" 
                                                    type="button" 
                                                    onclick="togglePasswordDetail()"
                                                    title="Tampilkan/Sembunyikan Password">
                                                <i class="bi bi-eye" id="eye-detail"></i>
                                            </button>
                                        </div>
                                        <small class="text-success">
                                            <i class="bi bi-check-circle"></i> Plain text password
                                        </small>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="30%"><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge {{ $akun->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $akun->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat:</strong></td>
                                    <td>{{ $akun->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Diupdate:</strong></td>
                                    <td>{{ $akun->updated_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($akun->alamat)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Alamat:</h5>
                            <p class="text-muted">{{ $akun->alamat }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.akun.reset-password', $akun->id) }}" class="btn btn-secondary">
                                    <i class="bi bi-key me-1"></i>Reset Password
                                </a>
                                <form action="{{ route('admin.akun.destroy', $akun->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash me-1"></i>Hapus Akun
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordDetail() {
    const passwordField = document.getElementById('password-detail');
    const eyeIcon = document.getElementById('eye-detail');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        eyeIcon.className = 'bi bi-eye-slash';
    } else {
        passwordField.type = 'password';
        eyeIcon.className = 'bi bi-eye';
    }
}
</script>
@endsection
