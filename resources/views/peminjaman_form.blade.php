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
                                        @if(isset($item['lantai']))
                                            <br><small class="text-muted">Lantai {{ $item['lantai'] }}</small>
                                        @endif
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
                                        <h6 class="card-title text-primary mb-3"><i class="bi bi-camera me-2"></i>Foto Peminjam</h6>
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <div class="mb-3">
                                                        @if(session('foto_peminjam_path'))
                                                            <img id="preview-foto" src="{{ Storage::url(session('foto_peminjam_path')) }}" alt="Preview Foto" class="img-fluid rounded" style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                                        @else
                                                            <img id="preview-foto" src="{{ asset('storage/dummy.jpg') }}" alt="Preview Foto" class="img-fluid rounded" style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                                        @endif
                                                    </div>
                                                    <div class="mb-3">
                                                        <input type="file" id="foto-input-direct" accept="image/jpg,image/jpeg,image/png" class="form-control d-none" onchange="handleFileSelectDirect(event)">
                                                        <button type="button" class="btn btn-primary" onclick="document.getElementById('foto-input-direct').click()">
                                                            <i class="bi bi-camera me-1"></i>Upload Foto
                                                        </button>
                                                        @if(session('foto_peminjam_path'))
                                                            <input type="hidden" name="foto_peminjam_path" value="{{ session('foto_peminjam_path') }}">
                                                        @endif
                                                    </div>
                                                    <div class="form-text">Klik tombol di atas untuk upload foto</div>
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
                                                        <input type="text" name="unit" class="form-control" required value="{{ old('unit') }}" placeholder="Contoh: Teknik Informatika">
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
                                <label class="form-label fw-bold">Lampiran Bukti (PDF/JPG/PNG)</label>
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
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('preview-foto').src = e.target.result;
        };
        
        reader.readAsDataURL(input.files[0]);
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
    
    // Form validation and debugging
    const form = document.querySelector('form[action*="peminjaman/ajukan"]');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form) {
        console.log('Form found:', form);
        
        form.addEventListener('submit', function(e) {
            console.log('Form submission started...');
            
            // Disable submit button to prevent double submission
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Mengirim...';
            }
            
            // Check if all required fields are filled
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            let emptyFields = [];
            
            requiredFields.forEach(function(field) {
                console.log('Checking field:', field.name, 'Value:', field.value);
                if (!field.value.trim()) {
                    console.error('Required field empty:', field.name);
                    emptyFields.push(field.name);
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            // Check if cart has items
            const cartTable = document.querySelector('table tbody');
            if (cartTable && cartTable.children.length === 0) {
                console.error('Cart is empty');
                alert('Keranjang kosong! Silakan tambahkan barang terlebih dahulu.');
                e.preventDefault();
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-send-check"></i> Ajukan Peminjaman';
                }
                return false;
            }
            
            if (!isValid) {
                e.preventDefault();
                alert('Mohon lengkapi semua field yang wajib diisi: ' + emptyFields.join(', '));
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-send-check"></i> Ajukan Peminjaman';
                }
                return false;
            }
            
            console.log('Form validation passed, submitting...');
            // Allow form to submit
        });
    } else {
        console.error('Form not found!');
    }
});

// Foto upload functionality
let capturedImageData = null;

// Handle file selection (for modal)
function handleFileSelect(event) {
    const file = event.target.files[0];
    if (file) {
        if (!file.type.match('image.*')) {
            alert('File yang dipilih bukan gambar!');
            return;
        }
        
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 2MB.');
            return;
        }
        
        capturedImageData = file;
        showPreview(URL.createObjectURL(file));
    }
}

// Handle file selection directly (for main form)
function handleFileSelectDirect(event) {
    const file = event.target.files[0];
    if (file) {
        if (!file.type.match('image.*')) {
            alert('File yang dipilih bukan gambar!');
            return;
        }
        
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 2MB.');
            return;
        }
        
        // Show preview immediately
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-foto').src = e.target.result;
        };
        reader.readAsDataURL(file);
        
        // Upload file via AJAX
        uploadFileDirect(file);
    }
}

// Upload file directly without modal
function uploadFileDirect(file) {
    const formData = new FormData();
    formData.append('foto_peminjam', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    // Show loading indicator
    const uploadBtn = document.querySelector('button[onclick*="foto-input-direct"]');
    const originalText = uploadBtn.innerHTML;
    uploadBtn.disabled = true;
    uploadBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Uploading...';
    
    fetch('{{ route("foto.upload") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Update hidden input
            let hiddenInput = document.querySelector('input[name="foto_peminjam_path"]');
            if (!hiddenInput) {
                hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'foto_peminjam_path';
                document.querySelector('form').appendChild(hiddenInput);
            }
            hiddenInput.value = data.foto_path;
            
            // Show success message
            showAlert('Foto berhasil diupload!', 'success');
            
            // Update preview image without reload
            document.getElementById('preview-foto').src = data.foto_url;
        } else {
            throw new Error(data.message || 'Gagal upload foto');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Reset button
        uploadBtn.disabled = false;
        uploadBtn.innerHTML = originalText;
        
        // Show specific error message
        if (error.message.includes('HTTP 419')) {
            alert('Session telah berakhir. Silakan refresh halaman dan coba lagi.');
        } else if (error.message.includes('HTTP 422')) {
            alert('File tidak valid. Pastikan file adalah gambar (JPG, JPEG, PNG) dan ukuran maksimal 2MB.');
        } else if (error.message.includes('HTTP 500')) {
            alert('Terjadi kesalahan server. Silakan coba lagi nanti.');
        } else {
            alert('Terjadi kesalahan saat upload foto: ' + error.message);
        }
    });
}





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