@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Tambah Ruangan</h4>
        <a href="{{ route('admin.inventaris-ruangan.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.inventaris-ruangan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror" value="{{ old('lokasi') }}">
                        @error('lokasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="dipinjam" {{ old('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="tidak tersedia" {{ old('status') == 'tidak tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Foto Upload Section with Preview -->
                    <div class="col-12">
                        <div class="photo-upload-section">
                            <h6 class="text-primary fw-semibold mb-3">
                                <i class="bi bi-camera me-2"></i>Foto Barang (Maksimal 3 foto)
                            </h6>
                            <p class="text-muted small mb-3">
                                <i class="bi bi-info-circle me-1"></i>
                                Format yang didukung: JPG, JPEG, PNG. Maksimal ukuran: 2MB per foto. Foto 1 wajib diisi. Kosongkan field jika tidak ingin mengubah foto.
                            </p>
                            
                            <div class="row">
                                <!-- Foto 1 -->
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Foto 1 <span class="text-danger">*</span></label>
                                    <input type="file" name="foto1" class="form-control @error('foto1') is-invalid @enderror" accept="image/jpg,image/jpeg,image/png" onchange="previewImage(this, 'preview1')">
                                    @error('foto1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="mt-2 photo-preview-wrapper">
                                        <img id="preview1" src="{{ asset('assets/images/placeholder-image.svg') }}" alt="Preview Foto 1" class="photo-preview img-thumbnail" style="width: 100%; height: 200px; object-fit: cover;">
                                        <button type="button" class="btn btn-sm btn-danger photo-remove-btn d-none" onclick="removePhoto('preview1', 'foto1')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Foto 2 -->
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Foto 2</label>
                                    <input type="file" name="foto2" class="form-control @error('foto2') is-invalid @enderror" accept="image/jpg,image/jpeg,image/png" onchange="previewImage(this, 'preview2')">
                                    @error('foto2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="mt-2 photo-preview-wrapper">
                                        <img id="preview2" src="{{ asset('assets/images/placeholder-image.svg') }}" alt="Preview Foto 2" class="photo-preview img-thumbnail" style="width: 100%; height: 200px; object-fit: cover;">
                                        <button type="button" class="btn btn-sm btn-danger photo-remove-btn d-none" onclick="removePhoto('preview2', 'foto2')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Foto 3 -->
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Foto 3</label>
                                    <input type="file" name="foto3" class="form-control @error('foto3') is-invalid @enderror" accept="image/jpg,image/jpeg,image/png" onchange="previewImage(this, 'preview3')">
                                    @error('foto3')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="mt-2 photo-preview-wrapper">
                                        <img id="preview3" src="{{ asset('assets/images/placeholder-image.svg') }}" alt="Preview Foto 3" class="photo-preview img-thumbnail" style="width: 100%; height: 200px; object-fit: cover;">
                                        <button type="button" class="btn btn-sm btn-danger photo-remove-btn d-none" onclick="removePhoto('preview3', 'foto3')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Simpan
                    </button>
                    <a href="{{ route('admin.inventaris-ruangan.index') }}" class="btn btn-light">
                        <i class="bi bi-x-circle me-2"></i>Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.photo-preview-wrapper {
    position: relative;
    display: inline-block;
    width: 100%;
}

.photo-remove-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 10;
    opacity: 0.9;
}

.photo-remove-btn:hover {
    opacity: 1;
}

.photo-preview {
    border-radius: 8px;
    transition: all 0.3s ease;
}

.photo-preview:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
</style>

<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const removeBtn = preview.nextElementSibling;
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file type
        if (!file.type.match('image.*')) {
            alert('File yang dipilih bukan gambar!');
            input.value = '';
            return;
        }
        
        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 2MB.');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            if (removeBtn) {
                removeBtn.classList.remove('d-none');
            }
        };
        reader.readAsDataURL(file);
    }
}

function removePhoto(previewId, inputName) {
    const preview = document.getElementById(previewId);
    const input = document.querySelector(`input[name="${inputName}"]`);
    const removeBtn = preview.nextElementSibling;
    
    // Reset to placeholder
    preview.src = "{{ asset('assets/images/placeholder-image.svg') }}";
    
    // Clear input
    if (input) {
        input.value = '';
    }
    
    // Hide remove button
    if (removeBtn) {
        removeBtn.classList.add('d-none');
    }
}
</script>
@endsection