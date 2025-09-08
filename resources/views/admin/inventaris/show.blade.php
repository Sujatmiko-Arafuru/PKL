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
                <i class="bi bi-box-seam me-2"></i>Detail Barang Inventaris
            </h2>
            <p class="text-muted mb-0">Informasi lengkap barang dan stok inventaris</p>
        </div>
        <div>
            <a href="{{ route('admin.inventaris.index') }}" class="btn btn-outline-primary shadow-sm">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Inventaris
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Photo Section - Shopee Style -->
        @if($barang->hasPhotos())
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 text-primary fw-semibold">
                        <i class="bi bi-camera me-2"></i>Foto Barang ({{ $barang->photo_count }} foto)
                    </h6>
                </div>
                <div class="card-body p-0">
                    <!-- Main Photo Display -->
                    <div class="main-photo-container">
                        <div id="mainPhotoDisplay" class="main-photo">
                            <img src="{{ Storage::url($barang->photos[0]) }}" alt="Foto Utama" id="mainPhotoImage">
                        </div>
                        
                        <!-- Navigation Arrows -->
                        @if($barang->photo_count > 1)
                        <button class="photo-nav-btn photo-nav-prev" onclick="changeMainPhoto('prev')">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button class="photo-nav-btn photo-nav-next" onclick="changeMainPhoto('next')">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                        @endif
                    </div>
                    
                    <!-- Thumbnail Navigation -->
                    @if($barang->photo_count > 1)
                    <div class="thumbnail-navigation">
                        @foreach($barang->photos as $index => $photo)
                        <div class="thumbnail-item {{ $index === 0 ? 'active' : '' }}" 
                             onclick="changeMainPhoto({{ $index }})" 
                             data-index="{{ $index }}">
                            <img src="{{ Storage::url($photo) }}" alt="Thumbnail {{ $index + 1 }}">
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Information Section -->
        <div class="col-lg-{{ $barang->hasPhotos() ? '4' : '12' }}">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 text-primary fw-semibold">
                        <i class="bi bi-info-circle me-2"></i>Informasi Barang
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Nama Barang</label>
                            <p class="mb-0 fs-5">{{ $barang->nama }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Kategori</label>
                            <p class="mb-0">{{ $barang->kategori ?? 'Tidak ada kategori' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Satuan</label>
                            <p class="mb-0">{{ $barang->satuan ?? 'Tidak ada satuan' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Lokasi</label>
                            <p class="mb-0">{{ $barang->lokasi ?? 'Tidak ada lokasi' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Kondisi</label>
                            <p class="mb-0">{{ $barang->kondisi ?? 'Tidak ada kondisi' }}</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold text-muted">Deskripsi</label>
                            <p class="mb-0">{{ $barang->deskripsi ?: 'Tidak ada deskripsi' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Information -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 text-primary fw-semibold">
                        <i class="bi bi-boxes me-2"></i>Informasi Stok
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Total Stok</label>
                            <p class="mb-0 fs-4 fw-bold text-primary">{{ $barang->stok }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Status</label>
                            <span class="badge bg-{{ $barang->status == 'tersedia' ? 'success' : 'danger' }} fs-6">
                                {{ ucfirst($barang->status) }}
                            </span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Stok Tersedia</label>
                            <p class="mb-0 fs-5 fw-bold text-success">{{ $barang->stok_tersedia }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Stok Dipinjam</label>
                            <p class="mb-0 fs-5 fw-bold text-warning">{{ $barang->stok_dipinjam }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 text-primary fw-semibold">
                        <i class="bi bi-gear me-2"></i>Aksi
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.inventaris.edit', $barang->id) }}" class="btn btn-primary">
                            <i class="bi bi-pencil-square me-2"></i>Edit Barang
                        </a>
                        <form action="{{ route('admin.inventaris.destroy', $barang->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-trash me-2"></i>Hapus Barang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($barang->hasPhotos())
<script>
    let currentPhotoIndex = 0;
    const totalPhotos = {{ $barang->photo_count }};
    const photos = @json($barang->photos);
    
    function changeMainPhoto(direction) {
        if (typeof direction === 'number') {
            // Direct index selection
            currentPhotoIndex = direction;
        } else if (direction === 'next') {
            // Next photo
            currentPhotoIndex = (currentPhotoIndex + 1) % totalPhotos;
        } else if (direction === 'prev') {
            // Previous photo
            currentPhotoIndex = (currentPhotoIndex - 1 + totalPhotos) % totalPhotos;
        }
        
        // Update main photo
        const mainPhotoImage = document.getElementById('mainPhotoImage');
        mainPhotoImage.src = '/storage/' + photos[currentPhotoIndex];
        
        // Update thumbnail active state
        updateThumbnailActiveState();
    }
    
    function updateThumbnailActiveState() {
        // Remove active class from all thumbnails
        document.querySelectorAll('.thumbnail-item').forEach(thumb => {
            thumb.classList.remove('active');
        });
        
        // Add active class to current thumbnail
        const currentThumb = document.querySelector(`[data-index="${currentPhotoIndex}"]`);
        if (currentThumb) {
            currentThumb.classList.add('active');
        }
    }
    
    // Add click event listeners to thumbnails
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.thumbnail-item').forEach(thumb => {
            thumb.addEventListener('click', function() {
                const index = parseInt(this.dataset.index);
                changeMainPhoto(index);
            });
        });
    });
</script>
@endif
@endsection 