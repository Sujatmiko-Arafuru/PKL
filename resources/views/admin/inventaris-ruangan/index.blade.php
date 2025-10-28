@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 text-primary fw-bold">
                <i class="bi bi-building me-2"></i>Inventaris Ruangan
            </h2>
            <p class="text-muted mb-0">Kelola data inventaris ruangan</p>
        </div>
        <div>
            <a href="{{ route('admin.inventaris-ruangan.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-circle me-2"></i>Tambah Ruangan
            </a>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.inventaris-ruangan.index') }}" class="row g-3">
                <div class="col-md-6">
                    <label for="search" class="form-label">Cari Ruangan</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="Nama, kode, lokasi, atau deskripsi..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="tidak tersedia" {{ request('status') == 'tidak tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-2"></i>Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-building text-primary fs-4"></i>
                    </div>
                    <h4 class="mb-1 text-primary">{{ $totalRuangan }}</h4>
                    <p class="mb-0 text-muted">Total Ruangan</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-check-circle text-success fs-4"></i>
                    </div>
                    <h4 class="mb-1 text-success">{{ $ruanganTersedia }}</h4>
                    <p class="mb-0 text-muted">Tersedia</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-tools text-warning fs-4"></i>
                    </div>
                    <h4 class="mb-1 text-warning">{{ $ruangans->where('status', 'maintenance')->count() }}</h4>
                    <p class="mb-0 text-muted">Maintenance</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-x-circle text-danger fs-4"></i>
                    </div>
                    <h4 class="mb-1 text-danger">{{ $ruangans->where('status', 'dipinjam')->count() }}</h4>
                    <p class="mb-0 text-muted">Dipinjam</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ruangan List -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0">
            <h6 class="mb-0 text-primary fw-semibold">
                <i class="bi bi-table me-2"></i>Data Inventaris Ruangan
            </h6>
        </div>
        <div class="card-body p-0">
            @if($ruangans->count() > 0)
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table table-hover mb-0" style="min-width: 800px;">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold">Foto</th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold">Nama Ruangan</th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold">Lokasi</th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold">Status</th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold">Deskripsi</th>
                                <th class="border-0 px-3 py-3 text-muted small fw-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ruangans as $ruangan)
                            <tr class="border-bottom">
                                <td class="px-3 py-3">
                                    @if($ruangan->hasPhotos())
                                        <div class="position-relative">
                                            <img src="{{ Storage::url($ruangan->main_photo) }}" 
                                                 alt="{{ $ruangan->nama }}" 
                                                 class="rounded" 
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                            @if($ruangan->photo_count > 1)
                                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary" 
                                                      style="font-size: 0.6rem; transform: translate(-50%, -50%);">
                                                    +{{ $ruangan->photo_count - 1 }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                             style="width: 60px; height: 60px;">
                                            <i class="bi bi-building text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-3 py-3">
                                    <div class="fw-semibold text-dark">{{ $ruangan->nama }}</div>
                                </td>
                                <td class="px-3 py-3">
                                    <div class="text-muted">{{ $ruangan->lokasi ?? '-' }}</div>
                                </td>
                                <td class="px-3 py-3">
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
                                </td>
                                <td class="px-3 py-3">
                                    <div class="text-muted" title="{{ $ruangan->deskripsi }}">
                                        {{ Str::limit($ruangan->deskripsi, 40) }}
                                    </div>
                                </td>
                                <td class="px-3 py-3">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.inventaris-ruangan.show', $ruangan->id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.inventaris-ruangan.edit', $ruangan->id) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.inventaris-ruangan.destroy', $ruangan->id) }}" 
                                              method="POST" 
                                              class="d-inline" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus ruangan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($ruangans->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    <nav aria-label="Navigasi halaman inventaris ruangan">
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
                <div class="text-center text-muted mt-3">
                    <small>
                        Menampilkan {{ $ruangans->firstItem() }} - {{ $ruangans->lastItem() }} dari {{ $ruangans->total() }} ruangan 
                        (Halaman {{ $ruangans->currentPage() }} dari {{ $ruangans->lastPage() }})
                    </small>
                </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="bi bi-building text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3">Tidak ada ruangan</h5>
                    <p class="text-muted">Belum ada data ruangan yang ditambahkan.</p>
                    <a href="{{ route('admin.inventaris-ruangan.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Ruangan Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
