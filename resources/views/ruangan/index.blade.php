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
    
    /* Tombol tambah yang aktif */
    .btn-success {
        transition: all 0.3s ease;
    }
    
    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    /* Modal styling */
    .modal-content {
        border-radius: 1rem;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
    
    .modal-header {
        border-bottom: 2px solid #e9ecef;
        background: linear-gradient(135deg, #20B2AA, #48D1CC);
        color: white;
        border-radius: 1rem 1rem 0 0;
    }
    
    .modal-footer {
        border-top: 2px solid #e9ecef;
        background: #E0FFFF;
        border-radius: 0 0 1rem 1rem;
    }
    
    /* Notification styling */
    .alert.position-fixed {
        animation: slideInRight 0.5s ease-out;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border: none;
        border-radius: 0.5rem;
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .alert-success {
        background: linear-gradient(135deg, #2E8B57, #3CB371);
        color: white;
    }
    
    .alert-danger {
        background: linear-gradient(135deg, #dc3545, #fd7e14);
        color: white;
    }
    
    .alert .btn-close {
        filter: invert(1);
    }

    /* Photo carousel styling */
    .photo-carousel {
        height: 180px;
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .photo-carousel .carousel-item img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .photo-placeholder {
        height: 180px;
        border-radius: 0.5rem;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #dee2e6;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Menu -->
        @include('components.sidebar-menu')
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="dashboard-title mb-0">Daftar Ruangan Tersedia</h1>
            </div>
            
            
            <!-- Search Form -->
            <form method="GET" action="{{ route('ruangan.search') }}" class="mb-4">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Cari nama ruangan..." value="{{ request('q') }}">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Cari</button>
                    @if(request('q'))
                        <a href="{{ route('ruangan.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i> Reset</a>
                    @endif
                </div>
                @if(request('q'))
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="bi bi-search me-1"></i>
                            Mencari: "<strong>{{ request('q') }}</strong>" 
                            ({{ $ruangans->total() }} hasil ditemukan)
                        </small>
                    </div>
                @endif
            </form>

            <!-- Ruangan List -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @forelse($ruangans as $ruangan)
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <!-- Photo Section -->
                        @if($ruangan->hasPhotos())
                            @if($ruangan->photo_count > 1)
                                <div id="ruanganPhotoCarousel{{ $ruangan->id }}" class="carousel slide photo-carousel photo-gallery dashboard" data-bs-ride="carousel">
                                    <div class="carousel-indicators">
                                        @foreach($ruangan->photos as $index => $photo)
                                        <button type="button" data-bs-target="#ruanganPhotoCarousel{{ $ruangan->id }}" data-bs-slide-to="{{ $index }}" 
                                                class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" 
                                                aria-label="Slide {{ $index + 1 }}"></button>
                                        @endforeach
                                    </div>
                                    <div class="carousel-inner">
                                        @foreach($ruangan->photos as $index => $photo)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                            <img src="{{ Storage::url($photo) }}" class="d-block w-100" alt="Foto {{ $index + 1 }}">
                                        </div>
                                        @endforeach
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#ruanganPhotoCarousel{{ $ruangan->id }}" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#ruanganPhotoCarousel{{ $ruangan->id }}" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>
                            @else
                                <img src="{{ Storage::url($ruangan->main_photo) }}" alt="{{ $ruangan->nama }}" class="photo-carousel dashboard">
                            @endif
                        @else
                            <div class="photo-placeholder dashboard">
                                <i class="bi bi-building text-secondary" style="font-size:2.5rem;"></i>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-primary mb-1">{{ $ruangan->nama }}</h5>
                            <p class="card-text mb-1">{{ Str::limit($ruangan->deskripsi, 60) }}</p>
                            <p>Status: 
                                <span class="badge 
                                    @if($ruangan->bisaDipinjam()) bg-success
                                    @else bg-warning text-dark
                                    @endif">
                                    @if($ruangan->bisaDipinjam()) Tersedia
                                    @else Tidak Tersedia
                                    @endif
                                </span>
                            </p>
                            @if(!$ruangan->bisaDipinjam())
                                <small class="text-muted">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Tidak tersedia untuk dipinjam
                                </small>
                            @endif
                            <div class="mt-auto d-flex gap-2">
                                <a href="{{ route('ruangan.detail', $ruangan->id) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-info-circle"></i> Detail</a>
                                <button class="btn btn-sm {{ $ruangan->bisaDipinjam() ? 'btn-success' : 'btn-secondary' }}" 
                                        {{ !$ruangan->bisaDipinjam() ? 'disabled' : '' }}
                                        style="{{ !$ruangan->bisaDipinjam() ? 'opacity: 0.6; cursor: not-allowed;' : '' }}"
                                        data-id="{{ $ruangan->id }}"
                                        data-nama="{{ $ruangan->nama }}"
                                        data-deskripsi="{{ $ruangan->deskripsi }}"
                                        data-status="{{ $ruangan->effective_status }}"
                                        title="{{ !$ruangan->bisaDipinjam() ? 'Ruangan tidak tersedia untuk dipinjam' : 'Klik untuk menambah ke keranjang' }}">
                                    <i class="bi bi-cart-plus"></i> 
                                    {{ $ruangan->bisaDipinjam() ? 'Tambah' : 'Tidak Tersedia' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info">Ruangan tidak ditemukan.</div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($ruangans->hasPages())
            <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Navigasi halaman daftar ruangan">
                    <ul class="pagination pagination-lg">
                        {{-- Previous Page Link --}}
                        @if ($ruangans->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="bi bi-chevron-left"></i>
                                    <span class="d-none d-sm-inline">Sebelumnya</span>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $ruangans->previousPageUrl() }}" rel="prev">
                                    <i class="bi bi-chevron-left"></i>
                                    <span class="d-none d-sm-inline">Sebelumnya</span>
                                </a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($ruangans->getUrlRange(1, $ruangans->lastPage()) as $page => $url)
                            @if ($page == $ruangans->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($ruangans->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $ruangans->nextPageUrl() }}" rel="next">
                                    <span class="d-none d-sm-inline">Selanjutnya</span>
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <span class="d-none d-sm-inline">Selanjutnya</span>
                                    <i class="bi bi-chevron-right"></i>
                                </span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>

            <!-- Page Info -->
            <div class="text-center text-muted mb-3">
                <small>
                    Menampilkan {{ $ruangans->firstItem() ?? 0 }} - {{ $ruangans->lastItem() ?? 0 }} 
                    dari {{ $ruangans->total() }} ruangan 
                    (Halaman {{ $ruangans->currentPage() }} dari {{ $ruangans->lastPage() }})
                </small>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Tambah ke Keranjang -->
<div class="modal fade" id="modalTambahKeranjang" tabindex="-1" aria-labelledby="modalTambahKeranjangLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTambahKeranjangLabel">Tambah ke Keranjang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="text-center mb-3">
          <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
            <i class="bi bi-building text-secondary" style="font-size: 2.5rem;"></i>
          </div>
        </div>
        <h5 id="modalNamaRuangan" class="text-primary mb-2"></h5>
        <div class="mb-2"><span id="modalDeskripsiRuangan"></span></div>
        <div class="mb-2">Status: <span id="modalStatusRuangan" class="badge"></span></div>
        <div class="alert alert-info">
          <i class="bi bi-info-circle me-2"></i>
          <strong>Info:</strong> Ruangan dipinjam sebagai satu kesatuan (seluruh ruangan).
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-success" id="btnKonfirmasiTambah">Tambah ke Keranjang</button>
      </div>
    </div>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let selectedRuangan = {};
        var modalTambah = new bootstrap.Modal(document.getElementById('modalTambahKeranjang'));
        
        // Inisialisasi tooltip Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Event listener untuk tombol tambah
        document.addEventListener('click', function(e) {
            if (e.target.closest('button[data-status="tersedia"]')) {
                const btn = e.target.closest('button[data-status="tersedia"]');
                if (btn.getAttribute('data-status') === 'tersedia') {
                    selectedRuangan = {
                        id: btn.getAttribute('data-id'),
                        nama: btn.getAttribute('data-nama'),
                        deskripsi: btn.getAttribute('data-deskripsi'),
                        status: btn.getAttribute('data-status')
                    };
                    document.getElementById('modalNamaRuangan').innerText = selectedRuangan.nama;
                    document.getElementById('modalDeskripsiRuangan').innerText = selectedRuangan.deskripsi;
                    let statusSpan = document.getElementById('modalStatusRuangan');
                    if (selectedRuangan.status === 'tersedia') {
                        statusSpan.innerText = 'Tersedia';
                        statusSpan.className = 'badge bg-success';
                    } else if (selectedRuangan.status === 'maintenance') {
                        statusSpan.innerText = 'Maintenance';
                        statusSpan.className = 'badge bg-warning';
                    } else if (selectedRuangan.status === 'dipinjam') {
                        statusSpan.innerText = 'Dipinjam';
                        statusSpan.className = 'badge bg-danger';
                    } else {
                        statusSpan.innerText = selectedRuangan.status.charAt(0).toUpperCase() + selectedRuangan.status.slice(1);
                        statusSpan.className = 'badge bg-secondary';
                    }
                    modalTambah.show();
                }
            }
        });
        
        document.getElementById('btnKonfirmasiTambah').addEventListener('click', function() {
            // Disable button selama proses
            const btn = this;
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Memproses...';
            
            // Ambil CSRF token dari meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch("{{ route('keranjang.tambah-ruangan') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ 
                    ruangan_id: selectedRuangan.id
                })
            })
            .then(response => {
                // Cek content type untuk memastikan response adalah JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Server mengembalikan response non-JSON. Kemungkinan ada masalah dengan CSRF token atau session.');
                }
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                return response.json();
            })
            .then(data => {
                if(data.success) {
                    // Update cart count
                    const cartCountElement = document.getElementById('cart-count');
                    if (cartCountElement) {
                        cartCountElement.innerText = data.cart_count;
                    }
                    modalTambah.hide();
                    
                    // Tampilkan notifikasi sukses yang lebih baik
                    showSuccessNotification('Ruangan "' + selectedRuangan.nama + '" berhasil ditambahkan ke keranjang!');
                } else {
                    throw new Error(data.message || 'Gagal menambahkan ke keranjang');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Jika error terkait CSRF, refresh halaman
                if (error.message.includes('CSRF') || error.message.includes('non-JSON')) {
                    showErrorNotification('Session telah berakhir. Halaman akan di-refresh...');
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    showErrorNotification('Terjadi kesalahan saat menambahkan ke keranjang: ' + error.message);
                }
            })
            .finally(() => {
                // Re-enable button
                btn.disabled = false;
                btn.innerHTML = 'Tambah ke Keranjang';
            });
        });
        
        // Fungsi untuk menampilkan notifikasi sukses
        function showSuccessNotification(message) {
            const notification = document.createElement('div');
            notification.className = 'alert alert-success position-fixed';
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                <i class="bi bi-check-circle me-2"></i>
                ${message}
                <button type="button" class="btn-close ms-2" onclick="this.parentElement.remove()"></button>
            `;
            document.body.appendChild(notification);
            
            // Auto remove setelah 5 detik
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }
        
        // Fungsi untuk menampilkan notifikasi error
        function showErrorNotification(message) {
            const notification = document.createElement('div');
            notification.className = 'alert alert-danger position-fixed';
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                <i class="bi bi-exclamation-triangle me-2"></i>
                ${message}
                <button type="button" class="btn-close ms-2" onclick="this.parentElement.remove()"></button>
            `;
            document.body.appendChild(notification);
            
            // Auto remove setelah 8 detik
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 8000);
        }
    });
</script>

<style>
/* Pagination Styling */
.pagination {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-radius: 15px;
    overflow: hidden;
}

.pagination .page-link {
    border: none;
    color: #20c997;
    padding: 12px 16px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background-color: #e6f7f2;
    color: #20c997;
    transform: translateY(-2px);
}

.pagination .page-item.active .page-link {
    background-color: #20c997;
    border-color: #20c997;
    color: white;
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
    background-color: #f8f9fa;
}

.pagination .page-item:first-child .page-link,
.pagination .page-item:last-child .page-link {
    border-radius: 0;
}

/* Responsive pagination text */
@media (max-width: 576px) {
    .pagination .page-link {
        padding: 10px 12px;
        font-size: 0.9rem;
    }
}

/* Page info styling */
.text-muted small {
    font-size: 0.875rem;
    font-weight: 500;
}

/* Grid Layout Optimization for 3x4 */
.row-cols-lg-3 > .col {
    flex: 0 0 auto;
    width: 33.333333%;
}

@media (max-width: 991.98px) {
    .row-cols-md-2 > .col {
        flex: 0 0 auto;
        width: 50%;
    }
}

@media (max-width: 575.98px) {
    .row-cols-1 > .col {
        flex: 0 0 auto;
        width: 100%;
    }
}

/* Ensure consistent card heights */
.card.h-100 {
    height: 100% !important;
}

/* Search form styling */
.input-group .btn-outline-secondary {
    border-color: #6c757d;
    color: #6c757d;
}

.input-group .btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}
</style>
@endsection
