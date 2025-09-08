@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Tambah Ruangan</h4>
        <a href="{{ route('admin.inventaris-ruangan.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.inventaris-ruangan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Kategori</label>
                        <input type="text" name="kategori" class="form-control" value="{{ old('kategori') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Lantai</label>
                        <input type="text" name="lantai" class="form-control" value="{{ old('lantai') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi') }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Fasilitas</label>
                        <input type="text" name="fasilitas" class="form-control" value="{{ old('fasilitas') }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Foto 1</label>
                        <input type="file" name="foto1" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Foto 2</label>
                        <input type="file" name="foto2" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Foto 3</label>
                        <input type="file" name="foto3" class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('admin.inventaris-ruangan.index') }}" class="btn btn-light">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


