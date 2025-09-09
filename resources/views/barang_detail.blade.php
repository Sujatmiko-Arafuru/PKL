@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="{{ asset('assets/css/photo-gallery.css') }}">
@endsection

@section('content')
<style>
    .btn:disabled {
        opacity: 0.6 !important;
        cursor: not-allowed !important;
        pointer-events: none !important;
    }
    
    .btn-secondary:disabled {
        background-color: #6c757d !important;
        border-color: #6c757d !important;
        color: #fff !important;
    }
    
    .btn-secondary:disabled:hover {
        background-color: #6c757d !important;
        border-color: #6c757d !important;
    }
    
    .form-control:disabled {
        background-color: #e9ecef !important;
        opacity: 0.6 !important;
    }
</style>

<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar Menu -->
        @include('components.sidebar-menu')
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            
            <div class="mb-4">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
            </div>
            
            
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
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
                            <div class="text-center mb-4">
                                <h2 class="text-primary mb-3">{{ $barang->nama }}</h2>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 bg-primary bg-opacity-10">
                                        <div class="card-body text-center py-3">
                                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="bi bi-boxes text-primary"></i>
                                            </div>
                                            <h6 class="mb-0 text-primary fw-semibold">Stok Tersedia</h6>
                                            <p class="mb-0 text-muted small">{{ $barang->stok_tersedia }} unit</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 bg-warning bg-opacity-10">
                                        <div class="card-body text-center py-3">
                                            <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="bi bi-arrow-repeat text-warning"></i>
                                            </div>
                                            <h6 class="mb-0 text-warning fw-semibold">Stok Dipinjam</h6>
                                            <p class="mb-0 text-muted small">{{ $barang->stok_dipinjam }} unit</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <h6 class="text-muted small mb-2">Deskripsi</h6>
                                <div class="bg-light rounded p-3">
                                    @if($barang->deskripsi)
                                        <p class="mb-0">{{ $barang->deskripsi }}</p>
                                    @else
                                        <p class="mb-0 text-muted">Tidak ada deskripsi</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <h6 class="text-muted small mb-2">Status</h6>
                                <span class="badge rounded-pill fs-6
                                    {{ $barang->status == 'tersedia' ? 'bg-success' : 'bg-secondary' }}">
                                    <i class="bi bi-{{ $barang->status == 'tersedia' ? 'check-circle' : 'right' }} me-1"></i>
                                    {{ ucfirst($barang->status) }}
                                </span>
                            </div>
                            
                            <div class="mt-4">
                                <form action="{{ route('keranjang.tambah') }}" method="POST" class="d-flex flex-column align-items-start gap-2">
                                    @csrf
                                    <div class="input-group mb-2" style="max-width:200px;">
                                        <span class="input-group-text">Jumlah</span>
                                        <input type="number" name="jumlah" class="form-control" min="1" max="{{ $barang->stok_tersedia }}" value="1" required {{ $barang->status !== 'tersedia' ? 'disabled' : '' }}>
                                    </div>
                                    <input type="hidden" name="barang_id" value="{{ $barang->id }}">
                                    <button type="submit" class="btn btn-lg {{ $barang->status === 'tersedia' ? 'btn-primary' : 'btn-secondary' }}" {{ $barang->status !== 'tersedia' ? 'disabled' : '' }} style="{{ $barang->status !== 'tersedia' ? 'opacity: 0.6; cursor: not-allowed;' : '' }}" title="{{ $barang->status !== 'tersedia' ? 'Barang tidak tersedia untuk dipinjam (Stok: ' . $barang->stok_tersedia . ')' : 'Klik untuk menambah ke daftar peminjaman' }}">
                                        <i class="bi bi-cart-plus me-2"></i>
                                        @if($barang->status === 'tersedia')
                                            Tambah ke Daftar Peminjaman
                                        @else
                                            Tidak Tersedia
                                        @endif
                                    </button>
                                </form>
                                @if($barang->status !== 'tersedia')
                                    <small class="text-muted mt-1">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        Barang tidak tersedia untuk dipinjam saat ini
                                    </small>
                                    <div class="alert alert-warning mt-2" role="alert">
                                        <i class="bi bi-info-circle me-2"></i>
                                        <strong>Informasi:</strong> Barang ini sedang tidak tersedia untuk dipinjam. 
                                        Stok tersedia: <strong>{{ $barang->stok_tersedia }}</strong> dari total <strong>{{ $barang->stok }}</strong>.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notifikasi -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1055;">
  <div id="toastKeranjang" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="toastKeranjangMsg">
        Barang berhasil ditambahkan ke keranjang!
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi tooltip Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Handle form submission untuk tambah ke keranjang
    const form = document.querySelector('form[action*="keranjang/tambah"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            // Disable button dan ubah text
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Menambahkan...';
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Tampilkan notifikasi sukses
                    const toast = document.getElementById('toastKeranjang');
                    const toastMsg = document.getElementById('toastKeranjangMsg');
                    toastMsg.textContent = data.message;
                    toast.classList.remove('text-bg-danger');
                    toast.classList.add('text-bg-success');
                    
                    const bsToast = new bootstrap.Toast(toast);
                    bsToast.show();
                    
                    // Reset form
                    form.reset();
                    form.querySelector('input[name="jumlah"]').value = '1';
                    
                    // Update cart count jika ada
                    const cartCountElement = document.getElementById('cart-count');
                    if (cartCountElement && data.cart_count !== undefined) {
                        cartCountElement.innerText = data.cart_count;
                    }
                } else {
                    throw new Error(data.message || 'Gagal menambahkan ke keranjang');
                }
            })
            .catch(error => {
                // Tampilkan notifikasi error
                const toast = document.getElementById('toastKeranjang');
                const toastMsg = document.getElementById('toastKeranjangMsg');
                toastMsg.textContent = error.message || 'Terjadi kesalahan saat menambahkan ke keranjang';
                toast.classList.remove('text-bg-success');
                toast.classList.add('text-bg-danger');
                
                const bsToast = new bootstrap.Toast(toast);
                bsToast.show();
            })
            .finally(() => {
                // Re-enable button dan kembalikan text
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            });
        });
    }
});
</script>
@endsection 