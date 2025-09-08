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
                <i class="bi bi-building me-2"></i>Detail Ruangan
            </h2>
            <p class="text-muted mb-0">Informasi lengkap ruangan</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.inventaris-ruangan.edit', $ruangan->id) }}" class="btn btn-warning shadow-sm">
                <i class="bi bi-pencil me-2"></i>Edit Ruangan
            </a>
            <a href="{{ route('admin.inventaris-ruangan.index') }}" class="btn btn-outline-primary shadow-sm">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Inventaris
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Room Information -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 text-primary fw-semibold">
                        <i class="bi bi-info-circle me-2"></i>Informasi Ruangan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Kode Ruangan</label>
                            <div class="fw-semibold text-dark">{{ $ruangan->kode ?? 'Tidak ada kode' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Nama Ruangan</label>
                            <div class="fw-semibold text-dark">{{ $ruangan->nama }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Lokasi</label>
                            <div class="text-dark">{{ $ruangan->lokasi ?? 'Tidak ada lokasi' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Status</label>
                            <div>
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
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold text-muted">Deskripsi</label>
                            <div class="text-dark">{{ $ruangan->deskripsi ?? 'Tidak ada deskripsi' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Room Photos -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 text-primary fw-semibold">
                        <i class="bi bi-camera me-2"></i>Foto Ruangan
                    </h6>
                </div>
                <div class="card-body">
                    @if($ruangan->hasPhotos())
                        <div class="row g-3">
                            @foreach($ruangan->photos as $index => $photo)
                                @if($photo)
                                <div class="col-12">
                                    <div class="position-relative">
                                        <img src="{{ Storage::url($photo) }}" 
                                             alt="Foto {{ $index + 1 }}" 
                                             class="img-fluid rounded shadow-sm" 
                                             style="width: 100%; height: 200px; object-fit: cover;">
                                        <div class="position-absolute top-0 start-0 m-2">
                                            <span class="badge bg-dark bg-opacity-75">Foto {{ $index + 1 }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-camera text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Tidak ada foto ruangan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 text-primary fw-semibold">
                        <i class="bi bi-clock-history me-2"></i>Informasi Tambahan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold text-muted">Tanggal Dibuat</label>
                            <div class="text-dark">{{ $ruangan->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold text-muted">Terakhir Diperbarui</label>
                            <div class="text-dark">{{ $ruangan->updated_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold text-muted">Jumlah Foto</label>
                            <div class="text-dark">{{ $ruangan->photo_count }} foto</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold text-muted">ID Ruangan</label>
                            <div class="text-dark">#{{ $ruangan->id }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


