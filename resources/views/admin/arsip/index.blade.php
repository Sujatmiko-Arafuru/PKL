@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 text-primary fw-bold">
                <i class="bi bi-archive me-2"></i>Arsip Peminjaman & Pengembalian
            </h2>
            <p class="text-muted mb-0">Kelola dan lihat riwayat peminjaman barang</p>
        </div>
        <div>
            <a href="{{ route('admin.arsip.export.pdf') }}{{ count(request()->all()) ? '?' . http_build_query(request()->all()) : '' }}" 
               class="btn btn-danger shadow-sm">
                <i class="bi bi-file-earmark-pdf me-2"></i>Download PDF
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0 text-primary">
                <i class="bi bi-funnel me-2"></i>Filter Data
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label text-muted small">Cari Nama/Kode</label>
                    <input type="text" name="search" class="form-control form-control-sm" 
                           placeholder="Cari nama/kode..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="menunggu" {{ request('status')=='menunggu'?'selected':'' }}>Menunggu</option>
                        <option value="disetujui" {{ request('status')=='disetujui'?'selected':'' }}>Disetujui</option>
                        <option value="pengembalian_diajukan" {{ request('status')=='pengembalian_diajukan'?'selected':'' }}>Pengembalian Diajukan</option>
                        <option value="dikembalikan" {{ request('status')=='dikembalikan'?'selected':'' }}>Dikembalikan</option>
                        <option value="ditolak" {{ request('status')=='ditolak'?'selected':'' }}>Ditolak</option>
                        <option value="pengembalian ditolak" {{ request('status')=='pengembalian ditolak'?'selected':'' }}>Pengembalian Ditolak</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control form-control-sm" 
                           value="{{ request('tanggal_mulai') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control form-control-sm" 
                           value="{{ request('tanggal_selesai') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small">Urutan</label>
                    <select name="urut" class="form-select form-select-sm">
                        <option value="terbaru" {{ request('urut')=='terbaru'?'selected':'' }}>Terbaru</option>
                        <option value="terlama" {{ request('urut')=='terlama'?'selected':'' }}>Terlama</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label text-muted small">&nbsp;</label>
                    <button class="btn btn-primary btn-sm w-100 shadow-sm">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="bi bi-star-fill text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-primary fw-semibold">Barang Terlaris</h6>
                            <p class="mb-0 text-muted small">
                                {{ $terlaris ? $terlaris->nama . ' (' . ($terlaris->peminjaman_details_count ?? 0) . 'x)' : 'Belum ada data' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-secondary bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="bi bi-box-seam text-secondary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-secondary fw-semibold">Barang Tidak Pernah Dipinjam</h6>
                            <p class="mb-0 text-muted small">
                                @if($tidakPernah && count($tidakPernah) > 0)
                                    {{ count($tidakPernah) }} item
                                @else
                                    Semua barang sudah dipinjam
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0">
            <h6 class="mb-0 text-primary fw-semibold">
                <i class="bi bi-table me-2"></i>Data Arsip Peminjaman
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">KODE UNIK</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">NAMA & UNIT</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">KEGIATAN</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">PERIODE</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">TANGGAL PENGAJUAN</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">STATUS</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">NO. HP</th>
                            <th class="border-0 px-3 py-3 text-muted small fw-semibold">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamans as $p)
                        <tr class="border-bottom">
                            <td class="px-3 py-3">
                                <span class="badge bg-dark">{{ $p->kode_peminjaman }}</span>
                            </td>
                            <td class="px-3 py-3">
                                <div class="fw-semibold text-dark">{{ $p->nama }}</div>
                                <div class="text-muted small">{{ $p->unit }}</div>
                            </td>
                            <td class="px-3 py-3">
                                <div class="fw-medium" title="{{ $p->nama_kegiatan }}">
                                    {{ Str::limit($p->nama_kegiatan, 30) }}
                                </div>
                            </td>
                            <td class="px-3 py-3">
                                <div class="small text-muted">
                                    <div>Tanggal Mulai {{ format_tanggal($p->tanggal_mulai) }}</div>
                                    <div>Tanggal Selesai {{ format_tanggal($p->tanggal_selesai) }}</div>
                                </div>
                            </td>
                            <td class="px-3 py-3">
                                <div class="small text-muted">
                                    <div>Tanggal Pengajuan {{ format_tanggal($p->created_at) }}</div>
                                    <div>{{ \Carbon\Carbon::parse($p->created_at)->format('H:i') }}</div>
                                </div>
                            </td>
                            <td class="px-3 py-3">
                                <span class="badge rounded-pill
                                    @if($p->status == 'dikembalikan') bg-success
                                    @elseif($p->status == 'disetujui') bg-success
                                    @elseif($p->status == 'pengembalian_diajukan') bg-warning text-dark
                                    @elseif($p->status == 'ditolak' || $p->status == 'pengembalian ditolak') bg-danger
                                    @else bg-secondary
                                    @endif
                                ">
                                    @if($p->status == 'pengembalian_diajukan')
                                        Pengembalian Diajukan
                                    @elseif($p->status == 'pengembalian ditolak')
                                        Pengembalian Ditolak
                                    @elseif($p->status == 'disetujui')
                                        Disetujui
                                    @else
                                        {{ ucfirst($p->status) }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-3 py-3">
                                <span class="text-muted">{{ $p->no_telp }}</span>
                            </td>
                            <td class="px-3 py-3">
                                <button type="button" class="btn btn-sm btn-outline-secondary shadow-sm detail-btn" 
                                        data-peminjaman-id="{{ $p->id }}">
                                    <i class="bi bi-eye me-1"></i>Detail
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1"></i>
                                    <p class="mb-0 mt-2">Tidak ada data arsip</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($peminjamans->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $peminjamans->withQueryString()->links() }}
    </div>
    @endif
</div>

<!-- Modal Detail Peminjaman -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="detailModalLabel">
                    <i class="bi bi-info-circle me-2"></i>Detail Peminjaman
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 0.75rem;
}

.table th {
    font-size: 0.875rem;
    font-weight: 600;
    white-space: nowrap;
}

.table td {
    vertical-align: middle;
    white-space: nowrap;
}

.table-borderless td {
    border: none;
}

.font-monospace {
    font-family: 'Courier New', monospace;
}

.progress {
    border-radius: 0.5rem;
    background-color: #f8f9fa;
}

.progress-bar {
    border-radius: 0.5rem;
    transition: width 0.6s ease;
}

.badge {
    font-size: 0.75rem;
}

.btn-sm {
    font-size: 0.875rem;
}

.modal-content {
    border-radius: 1rem;
}

.list-group-item {
    border-radius: 0.5rem;
    margin-bottom: 0.5rem;
}

.pagination {
    --bs-pagination-border-radius: 0.5rem;
}

/* Table responsive improvements */
.table-responsive {
    overflow-x: auto;
    min-width: 100%;
}

.table {
    min-width: 1200px; /* Ensure minimum width for all columns */
    width: 100%;
}

/* Column widths */
.table th:nth-child(1), .table td:nth-child(1) { /* KODE UNIK */
    width: 150px;
    min-width: 150px;
}

.table th:nth-child(2), .table td:nth-child(2) { /* NAMA & UNIT */
    width: 180px;
    min-width: 180px;
}

.table th:nth-child(3), .table td:nth-child(3) { /* KEGIATAN */
    width: 200px;
    min-width: 200px;
}

.table th:nth-child(4), .table td:nth-child(4) { /* PERIODE */
    width: 200px;
    min-width: 200px;
}

.table th:nth-child(5), .table td:nth-child(5) { /* TANGGAL PENGAJUAN */
    width: 180px;
    min-width: 180px;
}

.table th:nth-child(6), .table td:nth-child(6) { /* STATUS */
    width: 150px;
    min-width: 150px;
}

.table th:nth-child(7), .table td:nth-child(7) { /* NO. HP */
    width: 140px;
    min-width: 140px;
}

.table th:nth-child(8), .table td:nth-child(8) { /* AKSI */
    width: 120px;
    min-width: 120px;
}

/* Modal improvements */
.modal.fade .modal-dialog {
    transition: transform 0.3s ease-out;
}

.modal.show .modal-dialog {
    transform: none;
}

.modal-backdrop {
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-header {
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

/* Table improvements */
.table-hover tbody tr:hover {
    background-color: rgba(32, 178, 170, 0.05);
}

.table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    border-bottom: 1px solid #f1f3f4;
}

/* Badge improvements */
.badge.bg-dark {
    background-color: #343a40 !important;
    color: white !important;
}

.badge.bg-success {
    background-color: #28a745 !important;
    color: white !important;
}

.badge.bg-primary {
    background-color: #007bff !important;
    color: white !important;
}

.badge.bg-warning {
    background-color: #ffc107 !important;
    color: #212529 !important;
}

.badge.bg-danger {
    background-color: #dc3545 !important;
    color: white !important;
}

.badge.bg-secondary {
    background-color: #6c757d !important;
    color: white !important;
}

.badge.bg-light {
    background-color: #f8f9fa !important;
    color: #212529 !important;
}

.badge.bg-info {
    background-color: #17a2b8 !important;
    color: white !important;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .badge {
        font-size: 0.7rem;
    }
    
    .btn-sm {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
    
    .table {
        min-width: 1000px; /* Smaller minimum width for mobile */
    }
}

/* Debug styles to ensure columns are visible */
.table th, .table td {
    border: 1px solid #dee2e6;
    padding: 0.75rem;
}

.table th {
    background-color: #e9ecef !important;
    color: #495057 !important;
    font-weight: 600 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const detailButtons = document.querySelectorAll('.detail-btn');
    
    detailButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const peminjamanId = this.getAttribute('data-peminjaman-id');
            showDetailModal(peminjamanId);
        });
    });
    
    function showDetailModal(peminjamanId) {
        // Show loading state
        document.getElementById('modalBody').innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Memuat data...</p>
            </div>
        `;
        
        // Show modal first
        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
        modal.show();
        
        // Fetch data from API
        fetch(`/api/list-peminjam/detail/${peminjamanId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const peminjaman = data.data;
                    const modalContent = `
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 text-primary">
                                            <i class="bi bi-person me-2"></i>Data Peminjam
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <small class="text-muted">Kode Peminjaman</small>
                                            <div class="fw-semibold text-primary">${peminjaman.kode_peminjaman}</div>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Nama</small>
                                            <div class="fw-semibold">${peminjaman.nama}</div>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">NIM/NIP</small>
                                            <div class="fw-semibold">${peminjaman.nim_nip || 'Tidak tersedia'}</div>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Unit/Jurusan</small>
                                            <div class="fw-semibold">${peminjaman.unit}</div>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">No HP</small>
                                            <div class="fw-semibold">${peminjaman.no_telp}</div>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Nama Kegiatan</small>
                                            <div class="fw-semibold">${peminjaman.nama_kegiatan}</div>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Periode Peminjaman</small>
                                            <div class="fw-semibold">${formatTanggal(peminjaman.tanggal_mulai)} - ${formatTanggal(peminjaman.tanggal_selesai)}</div>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Tanggal Pengajuan</small>
                                            <div class="fw-semibold">${formatTanggal(peminjaman.created_at, true)}</div>
                                        </div>
                                        <div class="mb-0">
                                            <small class="text-muted">Status</small>
                                            <div>
                                                <span class="badge rounded-pill
                                                    ${peminjaman.status == 'dikembalikan' ? 'bg-success' :
                                                      peminjaman.status == 'disetujui' ? 'bg-success' :
                                                      peminjaman.status == 'pengembalian_diajukan' ? 'bg-warning text-dark' :
                                                      peminjaman.status == 'ditolak' || peminjaman.status == 'pengembalian ditolak' ? 'bg-danger' :
                                                      'bg-secondary'}">
                                                    ${peminjaman.status == 'pengembalian_diajukan' ? 'Pengembalian Diajukan' :
                                                      peminjaman.status == 'pengembalian ditolak' ? 'Pengembalian Ditolak' :
                                                      peminjaman.status == 'disetujui' ? 'Disetujui' :
                                                      peminjaman.status.charAt(0).toUpperCase() + peminjaman.status.slice(1)}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 text-primary">
                                            <i class="bi bi-box-seam me-2"></i>Barang yang Dipinjam
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        ${peminjaman.details && peminjaman.details.length > 0 ? `
                                            <div class="table-responsive">
                                                <table class="table table-sm table-borderless mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th class="text-muted small fw-semibold" style="width: 40%">Nama Barang</th>
                                                            <th class="text-muted small fw-semibold text-center" style="width: 20%">Dipinjam</th>
                                                            <th class="text-muted small fw-semibold text-center" style="width: 20%">Dikembalikan</th>
                                                            <th class="text-muted small fw-semibold text-center" style="width: 20%">Kode</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        ${peminjaman.details.map(detail => `
                                                            <tr class="border-bottom">
                                                                <td class="py-2">
                                                                    <div class="fw-semibold text-dark">${detail.barang.nama}</div>
                                                                    <small class="text-muted">${detail.barang.kategori}</small>
                                                                </td>
                                                                <td class="text-center py-2">
                                                                    <span class="badge bg-primary rounded-pill">${detail.jumlah}</span>
                                                                </td>
                                                                <td class="text-center py-2">
                                                                    ${detail.jumlah_dikembalikan > 0 ? 
                                                                        `<span class="badge bg-success rounded-pill">${detail.jumlah_dikembalikan}</span>` :
                                                                        `<span class="badge bg-warning text-dark rounded-pill">0</span>`
                                                                    }
                                                                </td>
                                                                <td class="text-center py-2">
                                                                    <small class="text-muted font-monospace">${detail.barang.kode}</small>
                                                                </td>
                                                            </tr>
                                                        `).join('')}
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="mt-2 text-center">
                                                <small class="text-muted">
                                                    <i class="bi bi-info-circle me-1"></i>
                                                    Total: ${peminjaman.details.length} jenis barang
                                                </small>
                                                <div class="mt-1">
                                                    <small class="text-muted">
                                                        <i class="bi bi-box me-1"></i>
                                                        Total dipinjam: ${peminjaman.details.reduce((sum, detail) => sum + detail.jumlah, 0)} unit
                                                    </small>
                                                </div>
                                                <div class="mt-1">
                                                    <small class="text-muted">
                                                        <i class="bi bi-check-circle me-1"></i>
                                                        Total dikembalikan: ${peminjaman.details.reduce((sum, detail) => sum + detail.jumlah_dikembalikan, 0)} unit
                                                    </small>
                                                </div>
                                                <div class="mt-1">
                                                    <small class="text-muted">
                                                        <i class="bi bi-clock me-1"></i>
                                                        Sisa: ${peminjaman.details.reduce((sum, detail) => sum + detail.sisa_belum_dikembalikan, 0)} unit
                                                    </small>
                                                </div>
                                                <div class="mt-2">
                                                    ${(() => {
                                                        const totalDipinjam = peminjaman.details.reduce((sum, detail) => sum + detail.jumlah, 0);
                                                        const totalDikembalikan = peminjaman.details.reduce((sum, detail) => sum + detail.jumlah_dikembalikan, 0);
                                                        const percentage = totalDipinjam > 0 ? Math.round((totalDikembalikan / totalDipinjam) * 100) : 0;
                                                        return `
                                                            <div class="progress mb-1" style="height: 8px;">
                                                                <div class="progress-bar bg-success" role="progressbar" 
                                                                     style="width: ${percentage}%" 
                                                                     aria-valuenow="${percentage}" 
                                                                     aria-valuemin="0" aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                            <small class="text-muted">
                                                                <i class="bi bi-percent me-1"></i>
                                                                Progress pengembalian: ${percentage}%
                                                            </small>
                                                        `;
                                                    })()}
                                                </div>
                                                <div class="mt-2">
                                                    <a href="/admin/arsip/${peminjaman.id}" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye me-1"></i>Lihat Detail Lengkap
                                                    </a>
                                                </div>
                                            </div>
                                        ` : `
                                            <div class="text-center text-muted py-3">
                                                <i class="bi bi-exclamation-triangle fs-1"></i>
                                                <p class="mb-0 mt-2">Tidak ada barang yang dipinjam</p>
                                                <small>Data barang tidak tersedia atau belum diinput</small>
                                                <div class="mt-2">
                                                    <a href="/admin/arsip/${peminjaman.id}" class="btn btn-sm btn-outline-secondary">
                                                        <i class="bi bi-eye me-1"></i>Lihat Detail Lengkap
                                                    </a>
                                                </div>
                                            </div>
                                        `}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    document.getElementById('modalBody').innerHTML = modalContent;
                } else {
                    document.getElementById('modalBody').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Gagal memuat data peminjaman.
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('modalBody').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Terjadi kesalahan saat memuat data.
                    </div>
                `;
            });
    }
    
    function formatTanggal(dateString, includeTime = false) {
        const date = new Date(dateString);
        const day = date.getDate().toString().padStart(2, '0');
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const year = date.getFullYear();
        
        if (includeTime) {
            const hours = date.getHours().toString().padStart(2, '0');
            const minutes = date.getMinutes().toString().padStart(2, '0');
            return `${day}/${month}/${year} ${hours}:${minutes}`;
        }
        
        return `${day}/${month}/${year}`;
    }
    
    document.getElementById('detailModal').addEventListener('click', function(e) {
        if (e.target === this) {
            const modal = bootstrap.Modal.getInstance(this);
            modal.hide();
        }
    });
});
</script>
@endsection 