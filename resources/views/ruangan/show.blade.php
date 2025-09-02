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
                <a href="{{ route('ruangan.index') }}" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Kembali ke List Ruangan</a>
            </div>
            
            <div class="alert alert-info mb-3">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Format Kode:</strong> NAMA-TANGGAL-URUTAN (Contoh: JOH-20241201-0001)
            </div>
            
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Photo Section - Shopee Style -->
                        @if($ruangan->hasPhotos())
                        <div class="col-lg-8 mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white border-0">
                                    <h6 class="mb-0 text-primary fw-semibold">
                                        <i class="bi bi-camera me-2"></i>Foto Ruangan ({{ $ruangan->photo_count }} foto)
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    <!-- Main Photo Display -->
                                    <div class="main-photo-container">
                                        <div id="mainPhotoDisplay" class="main-photo">
                                            <img src="{{ Storage::url($ruangan->photos[0]) }}" alt="Foto Utama" id="mainPhotoImage">
                                        </div>
                                        
                                        <!-- Navigation Arrows -->
                                        @if($ruangan->photo_count > 1)
                                        <button class="photo-nav-btn photo-nav-prev" onclick="changeMainPhoto('prev')">
                                            <i class="bi bi-chevron-left"></i>
                                        </button>
                                        <button class="photo-nav-btn photo-nav-next" onclick="changeMainPhoto('next')">
                                            <i class="bi bi-chevron-right"></i>
                                        </button>
                                        @endif
                                    </div>
                                    
                                    <!-- Thumbnail Navigation -->
                                    @if($ruangan->photo_count > 1)
                                    <div class="thumbnail-navigation">
                                        @foreach($ruangan->photos as $index => $photo)
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
                        <div class="col-lg-{{ $ruangan->hasPhotos() ? '4' : '12' }}">
                            <div class="text-center mb-4">
                                <h2 class="text-primary mb-3">{{ $ruangan->nama }}</h2>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-12 mb-3">
                                    <div class="card border-0 
                                        @if($ruangan->status == 'tersedia') bg-success bg-opacity-10
                                        @elseif($ruangan->status == 'maintenance') bg-warning bg-opacity-10
                                        @elseif($ruangan->status == 'dipinjam') bg-danger bg-opacity-10
                                        @else bg-secondary bg-opacity-10
                                        @endif">
                                        <div class="card-body text-center py-3">
                                            <div class="
                                                @if($ruangan->status == 'tersedia') bg-success bg-opacity-10
                                                @elseif($ruangan->status == 'maintenance') bg-warning bg-opacity-10
                                                @elseif($ruangan->status == 'dipinjam') bg-danger bg-opacity-10
                                                @else bg-secondary bg-opacity-10
                                                @endif rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                                                 style="width: 40px; height: 40px;">
                                                @if($ruangan->status == 'tersedia')
                                                    <i class="bi bi-check-circle text-success"></i>
                                                @elseif($ruangan->status == 'maintenance')
                                                    <i class="bi bi-tools text-warning"></i>
                                                @elseif($ruangan->status == 'dipinjam')
                                                    <i class="bi bi-x-circle text-danger"></i>
                                                @else
                                                    <i class="bi bi-question-circle text-secondary"></i>
                                                @endif
                                            </div>
                                            <h6 class="mb-0 
                                                @if($ruangan->status == 'tersedia') text-success
                                                @elseif($ruangan->status == 'maintenance') text-warning
                                                @elseif($ruangan->status == 'dipinjam') text-danger
                                                @else text-secondary
                                                @endif fw-semibold">Status Ruangan</h6>
                                            <p class="mb-0 text-muted small">
                                                @if($ruangan->status == 'tersedia') Tersedia untuk dipinjam
                                                @elseif($ruangan->status == 'maintenance') Sedang dalam perawatan
                                                @elseif($ruangan->status == 'dipinjam') Sedang dipinjam
                                                @else {{ ucfirst($ruangan->status) }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @if($ruangan->deskripsi)
                            <div class="mb-4">
                                <h6 class="text-muted small mb-2">Deskripsi</h6>
                                <div class="bg-light rounded p-3">
                                    <p class="mb-0">{{ $ruangan->deskripsi }}</p>
                                </div>
                            </div>
                            @endif

                            @if($ruangan->fasilitas)
                            <div class="mb-4">
                                <h6 class="text-muted small mb-2">Fasilitas</h6>
                                <div class="bg-light rounded p-3">
                                    <p class="mb-0">{{ $ruangan->fasilitas }}</p>
                                </div>
                            </div>
                            @endif

                            @if($ruangan->lokasi)
                            <div class="mb-4">
                                <h6 class="text-muted small mb-2">Lokasi</h6>
                                <div class="bg-light rounded p-3">
                                    <p class="mb-0">
                                        <i class="bi bi-geo-alt me-2"></i>{{ $ruangan->lokasi }}
                                        @if($ruangan->lantai)
                                            <br><i class="bi bi-layers me-2"></i>Lantai {{ $ruangan->lantai }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @endif
                            
                            <div class="mt-4">
                                <form action="{{ route('keranjang.tambah-ruangan') }}" method="POST" class="d-flex flex-column align-items-start gap-2">
                                    @csrf
                                    <input type="hidden" name="ruangan_id" value="{{ $ruangan->id }}">
                                    <button type="submit" class="btn btn-lg {{ $ruangan->status === 'tersedia' ? 'btn-primary' : 'btn-secondary' }}" {{ $ruangan->status !== 'tersedia' ? 'disabled' : '' }} style="{{ $ruangan->status !== 'tersedia' ? 'opacity: 0.6; cursor: not-allowed;' : '' }}" title="{{ $ruangan->status !== 'tersedia' ? 'Ruangan tidak tersedia untuk dipinjam. Status: ' . ucfirst($ruangan->status) : 'Klik untuk menambah ke keranjang' }}">
                                        <i class="bi bi-cart-plus me-2"></i>
                                        @if($ruangan->status === 'tersedia')
                                            Tambah ke Keranjang
                                        @else
                                            Tidak Tersedia
                                        @endif
                                    </button>
                                </form>
                                @if($ruangan->status !== 'tersedia')
                                    <small class="text-muted mt-1">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        @if($ruangan->status == 'maintenance')
                                            Ruangan sedang dalam perawatan
                                        @elseif($ruangan->status == 'dipinjam')
                                            Ruangan sedang dipinjam
                                        @else
                                            Ruangan tidak tersedia untuk dipinjam
                                        @endif
                                    </small>
                                    <div class="alert alert-warning mt-2" role="alert">
                                        <i class="bi bi-info-circle me-2"></i>
                                        <strong>Informasi:</strong> 
                                        @if($ruangan->status == 'maintenance')
                                            Ruangan ini sedang dalam perawatan dan tidak dapat dipinjam.
                                        @elseif($ruangan->status == 'dipinjam')
                                            Ruangan ini sedang dipinjam oleh pengguna lain.
                                        @else
                                            Ruangan ini tidak tersedia untuk dipinjam.
                                        @endif
                                    </div>
                                @else
                                    <div class="alert alert-info mt-2" role="alert">
                                        <i class="bi bi-info-circle me-2"></i>
                                        <strong>Info:</strong> Ruangan dipinjam sebagai satu kesatuan (seluruh ruangan).
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
        Ruangan berhasil ditambahkan ke keranjang!
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

@if($ruangan->hasPhotos())
<script>
    let currentPhotoIndex = 0;
    const totalPhotos = {{ $ruangan->photo_count }};
    const photos = @json($ruangan->photos);
    
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
    const form = document.querySelector('form[action*="keranjang/tambah-ruangan"]');
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
