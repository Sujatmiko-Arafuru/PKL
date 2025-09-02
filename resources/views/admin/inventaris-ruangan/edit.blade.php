@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Edit Ruangan</h4>
        <a href="{{ route('admin.inventaris-ruangan.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.inventaris-ruangan.update', ['inventaris_ruangan' => $ruangan->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama', $ruangan->nama) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Kode</label>
                        <input type="text" name="kode" class="form-control" value="{{ old('kode', $ruangan->kode) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Kategori</label>
                        <input type="text" name="kategori" class="form-control" value="{{ old('kategori', $ruangan->kategori) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Lantai</label>
                        <input type="text" name="lantai" class="form-control" value="{{ old('lantai', $ruangan->lantai) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi', $ruangan->lokasi) }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Fasilitas</label>
                        <input type="text" name="fasilitas" class="form-control" value="{{ old('fasilitas', $ruangan->fasilitas) }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi', $ruangan->deskripsi) }}</textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Foto 1</label>
                        <input type="file" name="foto1" class="form-control" accept="image/*">
                        @if($ruangan->foto1)
                            <small class="text-muted d-block mt-1">Foto saat ini:</small>
                            <img src="{{ Storage::url($ruangan->foto1) }}" alt="foto1" style="height:60px;" class="rounded">
                        @endif
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Foto 2</label>
                        <input type="file" name="foto2" class="form-control" accept="image/*">
                        @if($ruangan->foto2)
                            <small class="text-muted d-block mt-1">Foto saat ini:</small>
                            <img src="{{ Storage::url($ruangan->foto2) }}" alt="foto2" style="height:60px;" class="rounded">
                        @endif
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Foto 3</label>
                        <input type="file" name="foto3" class="form-control" accept="image/*">
                        @if($ruangan->foto3)
                            <small class="text-muted d-block mt-1">Foto saat ini:</small>
                            <img src="{{ Storage::url($ruangan->foto3) }}" alt="foto3" style="height:60px;" class="rounded">
                        @endif
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('admin.inventaris-ruangan.index') }}" class="btn btn-light">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


