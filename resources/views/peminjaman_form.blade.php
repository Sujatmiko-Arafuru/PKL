@extends('layouts.app')

@section('content')
<style>
#preview-image {
    border: 3px solid #20B2AA;
    border-radius: 8px;
}
</style>
<style>
.is-invalid {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: #dc3545;
}

.form-control:focus {
    border-color: #20B2AA;
    box-shadow: 0 0 0 0.2rem rgba(32, 178, 170, 0.25);
}
</style>

<div class="container py-4">
    <div class="row">
        <!-- Sidebar Menu -->
        @include('components.sidebar-menu')
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <h1 class="dashboard-title mb-3"><i class="bi bi-file-earmark-text me-2"></i>Form Peminjaman</h1>
            

            <!-- Barang yang Dipilih -->
            @if(!empty($barangItems))
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-box-seam me-2"></i>Barang yang Dipilih</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Foto</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Stok Tersedia</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barangItems as $key => $item)
                                <tr>
                                    <td>
                                        @if(isset($item['foto']))
                                            <img src="{{ Storage::url($item['foto']) }}" alt="{{ $item['nama'] }}" 
                                                 class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="bi bi-box-seam text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $item['nama'] }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $item['qty'] }}</span>
                                    </td>
                                    <td>
                                        <span class="text-success">{{ $item['stok_tersedia'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Tersedia</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Ruangan yang Dipilih -->
            @if(!empty($ruanganItems))
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-building me-2"></i>Ruangan yang Dipilih</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Foto</th>
                                    <th>Nama Ruangan</th>
                                    <th>Lokasi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ruanganItems as $key => $item)
                                <tr>
                                    <td>
                                        @if(isset($item['foto']))
                                            <img src="{{ Storage::url($item['foto']) }}" alt="{{ $item['nama'] }}" 
                                                 class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="bi bi-building text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $item['nama'] }}</strong>
                                    </td>
                                    <td>
                                        <small>{{ $item['lokasi'] ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($item['status'] == 'tersedia') bg-success
                                            @elseif($item['status'] == 'maintenance') bg-warning
                                            @elseif($item['status'] == 'dipinjam') bg-danger
                                            @else bg-secondary
                                            @endif">
                                            @if($item['status'] == 'tersedia') Tersedia
                                            @elseif($item['status'] == 'maintenance') Maintenance
                                            @elseif($item['status'] == 'dipinjam') Dipinjam
                                            @else {{ ucfirst($item['status']) }}
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
            

            
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i>Formulir Peminjaman</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('peminjaman.ajukan') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <!-- Foto Peminjam -->
                            <div class="col-12 mb-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary mb-3"><i class="bi bi-camera me-2"></i>Foto Peminjam <small class="text-muted">(Opsional)</small></h6>
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <div class="mb-3">
                                                        <img id="preview-foto" src="{{ asset('storage/dummy.jpg') }}" alt="Preview Foto" class="img-fluid rounded border" style="max-width: 200px; max-height: 200px; object-fit: cover; display: block !important;">
                                                    </div>
                                                    <div class="mb-3">
                                                        <input type="file" id="foto-peminjam-input" name="foto_peminjam" accept="image/jpg,image/jpeg,image/png" class="form-control" onchange="window.previewFoto(this)">
                                                    </div>
                                                    <div class="form-text">Upload foto peminjam (opsional)</div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Nama Lengkap</label>
                                                        <input type="text" name="nama" class="form-control" required value="{{ old('nama') }}" placeholder="Masukkan nama lengkap" minlength="3">
                                                        <div class="form-text">
                                                            <i class="bi bi-info-circle me-1"></i>
                                                            Minimal 3 karakter untuk generate kode unik (Format: NAMA-TANGGAL-URUTAN)
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">NIM/NIP</label>
                                                        <input type="text" name="nim_nip" class="form-control" required value="{{ old('nim_nip') }}" placeholder="Contoh: 2021001234 atau 19850101200101001">
                                                        <div class="form-text">
                                                            <i class="bi bi-info-circle me-1"></i>
                                                            Masukkan NIM untuk mahasiswa atau NIP untuk dosen/staff
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Jurusan / Ormawa</label>
                                                        <input type="text" name="unit" class="form-control" required value="{{ old('unit', session('user_nama')) }}" placeholder="Contoh: Teknik Informatika" readonly>
                                                        <div class="form-text">
                                                            <i class="bi bi-info-circle me-1"></i>
                                                            Field ini terisi otomatis berdasarkan akun yang login
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">No. Telepon</label>
                                                        <input type="text" name="no_telp" class="form-control" required value="{{ old('no_telp') }}" placeholder="Contoh: 08123456789">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Nama Kegiatan</label>
                                                        <input type="text" name="nama_kegiatan" class="form-control" required value="{{ old('nama_kegiatan') }}" placeholder="Contoh: Seminar Teknologi">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Informasi Peminjaman -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Dari Tanggal</label>
                                <input type="date" name="tanggal_mulai" class="form-control" required value="{{ old('tanggal_mulai') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Sampai Tanggal</label>
                                <input type="date" name="tanggal_selesai" class="form-control" required value="{{ old('tanggal_selesai') }}">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">File Bukti Kegiatan (PDF/JPG/PNG)</label>
                                <input type="file" name="bukti" class="form-control" accept="application/pdf,image/jpeg,image/png" required>
                                <div class="form-text">Upload bukti kegiatan atau surat pengantar</div>
                            </div>
                        </div>
                        

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success" id="submitBtn">
                                <i class="bi bi-send-check"></i> Ajukan Peminjaman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
window.previewFoto = function(input) {
    console.log('previewFoto called');
    if (input.files && input.files[0]) {
        const file = input.files[0];
        console.log('File selected:', file.name, file.type, file.size);
        
        // Validasi file
        if (!file.type.match('image.*')) {
            alert('File yang dipilih bukan gambar!');
            return;
        }
        
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 2MB.');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            console.log('FileReader loaded, setting image src');
            const previewImg = document.getElementById('preview-foto');
            if (previewImg) {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
                previewImg.style.maxWidth = '200px';
                previewImg.style.maxHeight = '200px';
                previewImg.style.objectFit = 'cover';
                previewImg.style.border = '2px solid #28a745';
                previewImg.style.borderRadius = '8px';
                console.log('Preview image updated successfully');
            } else {
                console.error('Preview image element not found!');
            }
        };
        reader.readAsDataURL(file);
    } else {
        console.log('No file selected');
    }
}






// Set default date values
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const tomorrowStr = tomorrow.toISOString().split('T')[0];
    
    const tanggalMulaiInput = document.querySelector('input[name="tanggal_mulai"]');
    const tanggalSelesaiInput = document.querySelector('input[name="tanggal_selesai"]');
    
    if (tanggalMulaiInput && !tanggalMulaiInput.value) {
        tanggalMulaiInput.value = today;
    }
    
    if (tanggalSelesaiInput && !tanggalSelesaiInput.value) {
        tanggalSelesaiInput.value = tomorrowStr;
    }
    
    // Setup photo preview event listener
    const fotoInput = document.getElementById('foto-peminjam-input');
    if (fotoInput) {
        fotoInput.addEventListener('change', function(e) {
            window.previewFoto(e.target);
        });
    }
    
    // Simple form handling
    const form = document.querySelector('form[action*="peminjaman/ajukan"]');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form) {
        
        form.addEventListener('submit', function(e) {
            // Disable submit button to prevent double submission
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Mengirim...';
            }
        });
    } else {
        console.error('Form not found!');
    }
});






// Show alert
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}


</script>





@endpush 