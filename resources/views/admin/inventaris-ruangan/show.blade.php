@extends('admin.layouts.app')

@section('head')
<link rel="stylesheet" href="{{ asset('assets/css/photo-gallery.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 text-primary fw-bold">
                <i class="bi bi-building me-2"></i>Detail Ruangan Inventaris
            </h2>
            <p class="text-muted mb-0">Informasi lengkap ruangan dan status pemakaian</p>
        </div>
        <div>
            <a href="{{ route('admin.inventaris-ruangan.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Inventaris
            </a>
        </div>
    </div>

    <!-- Main Content -->
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
                        @if($ruangan->photo_count > 1)
                        <button class="photo-nav-btn photo-nav-prev" onclick="changeMainPhoto('prev')">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button class="photo-nav-btn photo-nav-next" onclick="changeMainPhoto('next')">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                        @endif
                    </div>
                    @if($ruangan->photo_count > 1)
                    <div class="thumbnail-navigation">
                        @foreach($ruangan->photos as $index => $photo)
                        <div class="thumbnail-item {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">
                            <img src="{{ Storage::url($photo) }}" alt="Thumbnail {{ $index + 1 }}">
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Informasi Ruangan -->
        <div class="col-lg-{{ $ruangan->hasPhotos() ? '4' : '12' }}">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 text-primary fw-semibold">
                        <i class="bi bi-info-circle me-2"></i>Informasi Ruangan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="text-muted small">Nama Ruangan</div>
                            <div class="fs-5 fw-semibold">{{ $ruangan->nama }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Kode</div>
                            <div class="fs-6">{{ $ruangan->kode ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Kategori</div>
                            <div class="fs-6">{{ $ruangan->kategori ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Lokasi</div>
                            <div class="fs-6">{{ $ruangan->lokasi ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">Lantai</div>
                            <div class="fs-6">{{ $ruangan->lantai ?? '-' }}</div>
                        </div>
                        <div class="col-md-12">
                            <div class="text-muted small">Status</div>
                            <span class="badge rounded-pill fs-6
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
                        </div>
                        @if($ruangan->deskripsi)
                        <div class="col-md-12">
                            <div class="text-muted small">Deskripsi</div>
                            <p class="mb-0">{{ $ruangan->deskripsi }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Aksi -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0">
                    <h6 class="mb-0 text-primary fw-semibold">
                        <i class="bi bi-gear me-2"></i>Aksi
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="{{ route('admin.inventaris-ruangan.edit', ['inventaris_ruangan' => $ruangan->id]) }}" class="btn btn-teal btn-lg" style="background:#20c997;color:#fff;">
                            <i class="bi bi-pencil me-2"></i> Edit Ruangan
                        </a>
                        <form action="{{ route('admin.inventaris-ruangan.destroy', ['inventaris_ruangan' => $ruangan->id]) }}" method="POST" onsubmit="return confirm('Yakin hapus ruangan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-lg w-100">
                                <i class="bi bi-trash me-2"></i> Hapus Ruangan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6"></div>
    </div>
</div>

<style>
.btn-teal:hover{filter:brightness(0.95)}
</style>
@if($ruangan->hasPhotos())
<script>
    let currentPhotoIndex = 0;
    const totalPhotos = {{ $ruangan->photo_count }};
    const photos = @json($ruangan->photos);
    function changeMainPhoto(direction){
        if(typeof direction==='number'){
            currentPhotoIndex = direction;
        }else if(direction==='next'){
            currentPhotoIndex = (currentPhotoIndex + 1) % totalPhotos;
        }else if(direction==='prev'){
            currentPhotoIndex = (currentPhotoIndex - 1 + totalPhotos) % totalPhotos;
        }
        document.getElementById('mainPhotoImage').src = '/storage/' + photos[currentPhotoIndex];
        document.querySelectorAll('.thumbnail-item').forEach(t=>t.classList.remove('active'));
        const currentThumb = document.querySelector(`[data-index="${currentPhotoIndex}"]`);
        if(currentThumb){ currentThumb.classList.add('active'); }
    }
    document.addEventListener('DOMContentLoaded', function(){
        document.querySelectorAll('.thumbnail-item').forEach(thumb => {
            thumb.addEventListener('click', function(){
                const index = parseInt(this.dataset.index);
                changeMainPhoto(index);
            });
        });
    });
</script>
@endif
@endsection


