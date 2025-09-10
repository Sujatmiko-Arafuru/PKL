@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="bi bi-people me-2"></i>Kelola Jurusan/Ormawa
                    </h3>
                    <a href="{{ route('admin.akun.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Akun
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Tipe</th>
                                    <th>Email</th>
                                    <th>No. Telp</th>
                                    <th>Password</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($akuns as $index => $akun)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $akun->nama }}</td>
                                    <td>
                                        <span class="badge {{ $akun->tipe == 'ormawa' ? 'bg-info' : 'bg-success' }}">
                                            {{ ucfirst($akun->tipe) }}
                                        </span>
                                    </td>
                                    <td>{{ $akun->email ?? '-' }}</td>
                                    <td>{{ $akun->no_telp ?? '-' }}</td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <input type="password" 
                                                   class="form-control password-field text-success" 
                                                   value="{{ $akun->getPasswordForAdmin() }}" 
                                                   readonly 
                                                   id="password-{{ $akun->id }}"
                                                   style="font-family: monospace;">
                                            <button class="btn btn-outline-secondary" 
                                                    type="button" 
                                                    onclick="togglePassword({{ $akun->id }})"
                                                    title="Tampilkan/Sembunyikan Password">
                                                <i class="bi bi-eye" id="eye-{{ $akun->id }}"></i>
                                            </button>
                                        </div>
                                        <small class="text-success">
                                            <i class="bi bi-check-circle"></i> Plain text
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $akun->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $akun->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.akun.show', $akun->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.akun.edit', $akun->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="{{ route('admin.akun.reset-password', $akun->id) }}" class="btn btn-sm btn-secondary" title="Reset Password">
                                                <i class="bi bi-key"></i>
                                            </a>
                                            <form action="{{ route('admin.akun.destroy', $akun->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data akun</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(id) {
    const passwordField = document.getElementById('password-' + id);
    const eyeIcon = document.getElementById('eye-' + id);
    
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
