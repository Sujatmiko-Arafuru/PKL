@extends('admin.layouts.app')

@section('head')
<link rel="stylesheet" href="{{ asset('assets/css/photo-gallery.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 text-primary fw-bold">
                <i class="bi bi-building me-2"></i>Edit Ruangan Inventaris
            </h2>
            <p class="text-muted mb-0">Edit informasi ruangan inventaris</p>
        </div>
        <div>
            <a href="{{ route('admin.inventaris-ruangan.index') }}" class="btn btn-outline-primary shadow-sm">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Inventaris
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 text-primary fw-semibold">
                        <i class="bi bi-pencil-square me-2"></i>Form Edit Ruangan
                    </h6>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.inventaris-ruangan.update', $ruangan->id) }}" method="POST" class="mb-4" id="editForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Nama Ruangan</label>
                                <input type="text" name="nama" class="form-control" required value="{{ old('nama', $ruangan->nama) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Lokasi</label>
                                <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi', $ruangan->lokasi) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Lantai</label>
                                <input type="text" name="lantai" class="form-control" value="{{ old('lantai', $ruangan->lantai) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Kategori</label>
                                <input type="text" name="kategori" class="form-control" value="{{ old('kategori', $ruangan->kategori) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="tersedia" {{ old('status', $ruangan->status)=='tersedia'?'selected':'' }}>Tersedia</option>
                                    <option value="maintenance" {{ old('status', $ruangan->status)=='maintenance'?'selected':'' }}>Maintenance</option>
                                    <option value="dipinjam" {{ old('status', $ruangan->status)=='dipinjam'?'selected':'' }}>Dipinjam</option>
                                    <option value="tidak tersedia" {{ old('status', $ruangan->status)=='tidak tersedia'?'selected':'' }}>Tidak Tersedia</option>
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi ruangan...">{{ old('deskripsi', $ruangan->deskripsi) }}</textarea>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Fasilitas</label>
                                <textarea name="fasilitas" class="form-control" rows="3" placeholder="Fasilitas ruangan...">{{ old('fasilitas', $ruangan->fasilitas) }}</textarea>
                            </div>
                        </div>

                        <!-- Foto Upload Section -->
                        <div class="photo-upload-section mb-4">
                            <h6 class="text-primary fw-semibold mb-3">
                                <i class="bi bi-camera me-2"></i>Foto Ruangan (Maksimal 3 foto)
                            </h6>
                            
                            <!-- Dynamic Photo Upload -->
                            <div class="dynamic-photo-upload">
                                <div class="row" id="photoUploadContainer">
                                    <!-- Foto 1 -->
                                    <div class="col-md-4 mb-3 photo-upload-item">
                                        <label class="form-label fw-bold">Foto 1 <span class="text-danger">*</span></label>
                                        <div class="photo-upload-wrapper">
                                            <input type="file" name="foto1" class="form-control photo-input" accept="image/jpg,image/jpeg,image/png" onchange="previewImage(this, 'preview1')">
                                            <div class="photo-preview-container mt-2">
                                                @if($ruangan->foto1)
                                                    <img id="preview1" src="{{ Storage::url($ruangan->foto1) }}" alt="Foto 1" class="photo-preview">
                                                    <div class="photo-actions">
                                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removePhoto('preview1', 'foto1')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                @else
                                                    <img id="preview1" src="{{ asset('assets/images/placeholder-image.svg') }}" alt="Preview Foto 1" class="photo-preview">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Foto 2 -->
                                    <div class="col-md-4 mb-3 photo-upload-item">
                                        <label class="form-label fw-bold">Foto 2</label>
                                        <div class="photo-upload-wrapper">
                                            <input type="file" name="foto2" class="form-control photo-input" accept="image/jpg,image/jpeg,image/png" onchange="previewImage(this, 'preview2')">
                                            <div class="photo-preview-container mt-2">
                                                @if($ruangan->foto2)
                                                    <img id="preview2" src="{{ Storage::url($ruangan->foto2) }}" alt="Foto 2" class="photo-preview">
                                                    <div class="photo-actions">
                                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removePhoto('preview2', 'foto2')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                @else
                                                    <img id="preview2" src="{{ asset('assets/images/placeholder-image.svg') }}" alt="Preview Foto 2" class="photo-preview">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Foto 3 -->
                                    <div class="col-md-4 mb-3 photo-upload-item">
                                        <label class="form-label fw-bold">Foto 3</label>
                                        <div class="photo-upload-wrapper">
                                            <input type="file" name="foto3" class="form-control photo-input" accept="image/jpg,image/jpeg,image/png" onchange="previewImage(this, 'preview3')">
                                            <div class="photo-preview-container mt-2">
                                                @if($ruangan->foto3)
                                                    <img id="preview3" src="{{ Storage::url($ruangan->foto3) }}" alt="Foto 3" class="photo-preview">
                                                    <div class="photo-actions">
                                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removePhoto('preview3', 'foto3')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                @else
                                                    <img id="preview3" src="{{ asset('assets/images/placeholder-image.svg') }}" alt="Preview Foto 3" class="photo-preview">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Format yang didukung: JPG, JPEG, PNG. Maksimal ukuran: 2MB per foto. 
                                Foto 1 wajib diisi. Kosongkan field jika tidak ingin mengubah foto.
                            </small>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.inventaris-ruangan.show', $ruangan->id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-eye me-2"></i>Lihat Detail
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Preview Section -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 text-primary fw-semibold">
                        <i class="bi bi-eye me-2"></i>Preview Ruangan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-center mx-auto mb-3" 
                             style="width: 120px; height: 120px;">
                            <i class="bi bi-building text-muted" style="font-size: 3rem;"></i>
                        </div>
                        <h6 class="mb-1">{{ $ruangan->nama }}</h6>
                        <p class="text-muted mb-0">{{ $ruangan->kode ?? 'Tidak ada kode' }}</p>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <small class="text-muted">Lokasi</small>
                            <div class="fw-semibold">{{ $ruangan->lokasi ?? '-' }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Status</small>
                            <div>
                                <span class="badge 
                                    @if($ruangan->status == 'tersedia') bg-success
                                    @elseif($ruangan->status == 'maintenance') bg-warning
                                    @elseif($ruangan->status == 'dipinjam') bg-danger
                                    @else bg-secondary
                                    @endif rounded-pill fs-6">
                                    @if($ruangan->status == 'tersedia') Tersedia
                                    @elseif($ruangan->status == 'maintenance') Maintenance
                                    @elseif($ruangan->status == 'dipinjam') Dipinjam
                                    @else {{ ucfirst($ruangan->status) }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Deskripsi</small>
                        <div class="fw-semibold">{{ Str::limit($ruangan->deskripsi, 100) ?? 'Tidak ada deskripsi' }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Jumlah Foto</small>
                        <div class="fw-semibold">{{ $ruangan->photo_count }} foto</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removePhoto(previewId, inputName) {
    document.getElementById(previewId).src = "{{ asset('assets/images/placeholder-image.svg') }}";
    document.querySelector(`input[name="${inputName}"]`).value = '';
}
</script>
@endsection


