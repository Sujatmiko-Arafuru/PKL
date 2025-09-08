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
            
            
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Photo Section - Always show, with placeholder if no photos -->
                        <div class="col-lg-8 mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-white border-0">
                                    <h6 class="mb-0 text-primary fw-semibold">
                                        <i class="bi bi-camera me-2"></i>Foto Ruangan 
                                        @if($ruangan->hasPhotos())
                                            ({{ $ruangan->photo_count }} foto)
                                        @else
                                            (Belum ada foto)
                                        @endif
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    @if($ruangan->hasPhotos())
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
                                    @else
                                        <!-- Placeholder when no photos -->
                                        <div class="main-photo-container">
                                            <div class="main-photo d-flex align-items-center justify-content-center bg-light" style="height: 400px;">
                                                <div class="text-center text-muted">
                                                    <i class="bi bi-camera fs-1 mb-3"></i>
                                                    <h5>Belum ada foto ruangan</h5>
                                                    <p class="mb-0">Foto ruangan akan ditampilkan di sini</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Information Section -->
                        <div class="col-lg-4">
                            <div class="text-center mb-4">
                                <h2 class="text-primary mb-3">{{ $ruangan->nama }}</h2>
                            </div>
                            
                            @if($ruangan->lokasi)
                            <div class="mb-4">
                                <h6 class="text-muted small mb-2">Lokasi</h6>
                                <div class="bg-light rounded p-3">
                                    <p class="mb-0">
                                        <i class="bi bi-geo-alt me-2"></i>{{ $ruangan->lokasi }}
                                    </p>
                                </div>
                            </div>
                            @endif
                            
                            @if($ruangan->deskripsi)
                            <div class="mb-4">
                                <h6 class="text-muted small mb-2">Deskripsi</h6>
                                <div class="bg-light rounded p-3">
                                    <p class="mb-0">{{ $ruangan->deskripsi }}</p>
                                </div>
                            </div>
                            @endif
                            
                            <div class="mt-4">
                                <form action="{{ route('keranjang.tambah-ruangan') }}" method="POST" class="d-flex flex-column align-items-start gap-2">
                                    @csrf
                                    <input type="hidden" name="ruangan_id" value="{{ $ruangan->id }}">
                                    @if($ruangan->bisaDipinjam())
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="bi bi-cart-plus me-2"></i>Tambah ke Keranjang
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-secondary btn-lg" disabled>
                                            <i class="bi bi-cart-plus me-2"></i>Tidak Tersedia
                                        </button>
                                    @endif
                                </form>
                                @if(!$ruangan->bisaDipinjam())
                                    <div class="text-warning mt-2">
                                        <i class="bi bi-exclamation-triangle me-2"></i>Ruangan tidak tersedia untuk dipinjam
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
