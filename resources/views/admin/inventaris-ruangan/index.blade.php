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
                <div class="col-md-4">
                    <label for="search" class="form-label">Cari Ruangan</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="Nama, kode, atau kategori..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="tidak tersedia" {{ request('status') == 'tidak tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="kategori" class="form-label">Kategori</label>
                    <select class="form-select" id="kategori" name="kategori">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori }}" {{ request('kategori') == $kategori ? 'selected' : '' }}>
                                {{ $kategori }}
                            </option>
                        @endforeach
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
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">Foto</th>
                                <th class="border-0">Nama Ruangan</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Deskripsi</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ruangans as $ruangan)
                            <tr>
                                <td class="align-middle">
                                    @if($ruangan->hasPhotos())
                                        <img src="{{ Storage::url($ruangan->main_photo) }}" 
                                             alt="{{ $ruangan->nama }}" 
                                             class="rounded" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <i class="bi bi-building text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <div class="fw-semibold text-dark">{{ $ruangan->nama }}</div>
                                </td>
                                <td class="align-middle">
                                    <span class="badge 
                                        @if($ruangan->status == 'tersedia') bg-success
                                        @elseif($ruangan->status == 'maintenance') bg-warning
                                        @elseif($ruangan->status == 'dipinjam') bg-danger
                                        @else bg-secondary
                                        @endif">
                                        @if($ruangan->status == 'tersedia') Tersedia
                                        @elseif($ruangan->status == 'maintenance') Maintenance
                                        @elseif($ruangan->status == 'dipinjam') Dipinjam
                                        @else {{ ucfirst($ruangan->status) }}
                                        @endif
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <div class="text-muted" title="{{ $ruangan->deskripsi }}">
                                        {{ Str::limit($ruangan->deskripsi, 40) }}
                                    </div>
                                </td>
                                
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="card-footer bg-white border-0">
                    <div class="d-flex justify-content-center">
                        {{ $ruangans->links() }}
                    </div>
                </div>
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
