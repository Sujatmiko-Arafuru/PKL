@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 text-primary fw-bold">
                <i class="bi bi-people me-2"></i>Kelola Peminjaman
            </h2>
            <p class="text-muted mb-0">Kelola dan monitor semua peminjaman barang</p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Filter Section -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0 text-primary">
                <i class="bi bi-funnel me-2"></i>Filter Data
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3" id="filterForm">
                <div class="col-md-4">
                    <label class="form-label text-muted small">Cari Kegiatan</label>
                    <input type="text" name="search" class="form-control form-control-sm" 
                           placeholder="Cari nama kegiatan..." value="{{ request('search') }}" id="searchInput">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small">Status</label>
                    <select name="status" class="form-select form-select-sm" id="statusSelect">
                        <option value="semua" {{ request('status')=='semua'?'selected':'' }}>Semua Status</option>
                        <option value="menunggu" {{ request('status')=='menunggu'?'selected':'' }}>Menunggu</option>
                        <option value="disetujui" {{ request('status')=='disetujui'?'selected':'' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status')=='ditolak'?'selected':'' }}>Ditolak</option>
                        <option value="pengembalian_diajukan" {{ request('status')=='pengembalian_diajukan'?'selected':'' }}>Pengembalian Diajukan</option>
                        <option value="dikembalikan" {{ request('status')=='dikembalikan'?'selected':'' }}>Dikembalikan</option>
                        <option value="pengembalian ditolak" {{ request('status')=='pengembalian ditolak'?'selected':'' }}>Pengembalian Ditolak</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small">Urutan</label>
                    <select name="urut" class="form-select form-select-sm" id="sortSelect">
                        <option value="terbaru" {{ request('urut')=='terbaru'?'selected':'' }}>Terbaru</option>
                        <option value="terlama" {{ request('urut')=='terlama'?'selected':'' }}>Terlama</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small">&nbsp;</label>
                    <button type="submit" class="btn btn-primary btn-sm w-100 shadow-sm" id="filterBtn">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Peminjaman Cards Section -->
    <div class="row" id="peminjamanCards">
        @forelse($peminjamans as $p)
        <div class="col-lg-6 col-xl-4 mb-4">
            <div class="card shadow-sm border-0 h-100 peminjaman-card">
                <div class="card-header bg-white border-bottom-0 pb-0">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <span class="badge bg-dark mb-2">{{ $p->kode_peminjaman }}</span>
                            <h6 class="mb-1 fw-semibold text-dark">{{ $p->nama }}</h6>
                            <small class="text-muted">{{ $p->unit }}</small>
                        </div>
                        <div class="text-end">
                            @if($p->status == 'menunggu')
                                <span class="badge bg-warning text-dark rounded-pill">
                                    <i class="bi bi-clock me-1"></i>Menunggu
                                </span>
                            @elseif($p->status == 'disetujui')
                                <span class="badge bg-success rounded-pill">
                                    <i class="bi bi-check-circle me-1"></i>Disetujui
                                </span>
                            @elseif($p->status == 'ditolak')
                                <span class="badge bg-danger rounded-pill">
                                    <i class="bi bi-x-circle me-1"></i>Ditolak
                                </span>
                            @elseif($p->status == 'pengembalian_diajukan')
                                <span class="badge bg-info rounded-pill">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Pengembalian Diajukan
                                </span>
                            @elseif($p->status == 'dikembalikan')
                                <span class="badge bg-secondary rounded-pill">
                                    <i class="bi bi-check2-all me-1"></i>Dikembalikan
                                </span>
                            @elseif($p->status == 'pengembalian ditolak')
                                <span class="badge bg-warning text-dark rounded-pill">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Pengembalian Ditolak
                                </span>
                            @else
                                <span class="badge bg-secondary rounded-pill">{{ ucfirst($p->status) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="card-body pt-2">
                    <!-- Kegiatan -->
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">
                            <i class="bi bi-calendar-event me-1"></i>Kegiatan
                        </small>
                        <div class="fw-medium text-dark" title="{{ $p->nama_kegiatan }}">
                            {{ Str::limit($p->nama_kegiatan, 60) }}
                        </div>
                    </div>

                    <!-- Periode -->
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">
                            <i class="bi bi-calendar-range me-1"></i>Periode
                        </small>
                        <div class="row g-2">
                            <div class="col-6">
                                <small class="text-muted">Mulai</small>
                                <div class="fw-medium">{{ format_tanggal($p->tanggal_mulai) }}</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Selesai</small>
                                <div class="fw-medium">{{ format_tanggal($p->tanggal_selesai) }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Info Tambahan -->
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <small class="text-muted d-block">
                                <i class="bi bi-telephone me-1"></i>No. HP
                            </small>
                            <div class="fw-medium">{{ $p->no_telp }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">
                                <i class="bi bi-clock me-1"></i>Pengajuan
                            </small>
                            <div class="fw-medium">{{ format_tanggal($p->created_at) }}</div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($p->created_at)->format('H:i') }}</small>
                        </div>
                    </div>

                    <!-- Item yang Dipinjam -->
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">
                            <i class="bi bi-box-seam me-1"></i>Barang & Ruangan
                        </small>
                        <div class="row g-1">
                            @if($p->details->count() > 0)
                                <div class="col-12">
                                    <small class="badge bg-primary">{{ $p->details->count() }} Barang</small>
                                </div>
                            @endif
                            @if($p->detailsRuangan->count() > 0)
                                <div class="col-12">
                                    <small class="badge bg-info">{{ $p->detailsRuangan->count() }} Ruangan</small>
                                </div>
                            @endif
                            @if($p->details->count() == 0 && $p->detailsRuangan->count() == 0)
                                <div class="col-12">
                                    <small class="text-muted">Tidak ada item</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Card Footer - Actions -->
                <div class="card-footer bg-white border-top-0 pt-0">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('admin.peminjaman.show', $p->id) }}" 
                           class="btn btn-sm btn-outline-primary flex-fill">
                            <i class="bi bi-eye me-1"></i>Detail
                        </a>
                        
                        @if($p->status == 'menunggu')
                            <form action="{{ route('admin.peminjaman.approve', $p->id) }}" method="POST" class="flex-fill">
                                @csrf
                                <button class="btn btn-sm btn-success w-100" 
                                        onclick="return confirm('Approve peminjaman ini?')">
                                    <i class="bi bi-check-lg me-1"></i>Approve
                                </button>
                            </form>
                            <form action="{{ route('admin.peminjaman.reject', $p->id) }}" method="POST" class="flex-fill">
                                @csrf
                                <button class="btn btn-sm btn-danger w-100" 
                                        onclick="return confirm('Tolak peminjaman ini?')">
                                    <i class="bi bi-x-lg me-1"></i>Reject
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <div class="text-muted">
                        <i class="bi bi-inbox fs-1"></i>
                        <p class="mb-0 mt-2">Tidak ada data peminjaman</p>
                    </div>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($peminjamans->hasPages())
    <div class="d-flex justify-content-center mt-4">
        <nav aria-label="Pagination">
            {{ $peminjamans->links() }}
        </nav>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('searchInput');
    const statusSelect = document.getElementById('statusSelect');
    const sortSelect = document.getElementById('sortSelect');

    let searchTimeout;

    // Auto-submit on search input change
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterForm.submit();
        }, 500);
    });

    // Auto-submit on select change
    statusSelect.addEventListener('change', function() {
        filterForm.submit();
    });

    sortSelect.addEventListener('change', function() {
        filterForm.submit();
    });
});
</script>

<style>
.card {
    border-radius: 0.75rem;
    transition: all 0.3s ease;
}

.peminjaman-card {
    border: 1px solid #e9ecef;
}

.peminjaman-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.badge {
    font-size: 0.75rem;
}

.btn-sm {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}

.card-footer {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .col-lg-6 {
        margin-bottom: 1rem;
    }
}

@media (max-width: 576px) {
    .card-body {
        padding: 1rem;
    }
    
    .btn-sm {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
}

/* Animation for cards */
.peminjaman-card {
    animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Status badge colors */
.badge.bg-warning {
    background-color: #ffc107 !important;
    color: #000 !important;
}

.badge.bg-success {
    background-color: #198754 !important;
}

.badge.bg-danger {
    background-color: #dc3545 !important;
}

.badge.bg-info {
    background-color: #0dcaf0 !important;
}

.badge.bg-secondary {
    background-color: #6c757d !important;
}
</style>
@endsection 