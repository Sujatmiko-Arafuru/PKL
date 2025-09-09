@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar Menu -->
        @include('components.sidebar-menu')
        
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <h1 class="dashboard-title mb-3"><i class="bi bi-cart3 me-2"></i>Daftar Peminjaman</h1>
            
            
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            <!-- Barang Section -->
            @if(count($barangItems) > 0)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Daftar Barang yang Dipilih</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Foto</th>
                                    <th>Nama Barang</th>
                                    <th>Stok Tersedia</th>
                                    <th>Stok Dipinjam</th>
                                    <th>Status</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barangItems as $key => $item)
                                <tr>
                                    <td style="width:80px">
                                        @php
                                            $fotoUtama = null;
                                            if(isset($item['foto'])) {
                                                if(is_array($item['foto'])) {
                                                    $fotoUtama = count($item['foto']) > 0 ? $item['foto'][0] : null;
                                                } else {
                                                    $fotoUtama = $item['foto'];
                                                }
                                            }
                                        @endphp
                                        @if($fotoUtama)
                                        <img src="{{ asset('storage/' . $fotoUtama) }}" alt="{{ $item['nama'] }}" class="rounded" style="max-width:60px;max-height:60px;">
                                        @else
                                        <span class="text-muted"><i class="bi bi-image"></i></span>
                                        @endif
                                    </td>
                                    <td class="fw-semibold">{{ $item['nama'] }}</td>
                                    <td><span class="badge bg-success">{{ $item['stok_tersedia'] ?? 0 }}</span></td>
                                    <td><span class="badge bg-warning text-dark">{{ $item['stok_dipinjam'] ?? 0 }}</span></td>
                                    <td><span class="badge {{ $item['status'] == 'tersedia' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($item['status']) }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <button class="btn btn-outline-secondary btn-sm me-2" onclick="updateQty('{{ $key }}', 'decrease')" {{ $item['qty'] <= 1 ? 'disabled' : '' }}>
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <span class="fw-bold" id="qty-{{ $key }}">{{ $item['qty'] }}</span>
                                            <button class="btn btn-outline-secondary btn-sm ms-2" onclick="updateQty('{{ $key }}', 'increase')" {{ $item['qty'] >= ($item['stok_tersedia'] ?? $item['stok']) ? 'disabled' : '' }}>
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        <form action="{{ route('keranjang.hapus', $key) }}" method="POST" onsubmit="return confirm('Hapus barang dari keranjang?')">
                                            @csrf
                                            <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Ruangan Section -->
            @if(count($ruanganItems) > 0)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-building me-2"></i>Daftar Ruangan yang Dipilih</h5>
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
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ruanganItems as $key => $item)
                                <tr>
                                    <td>
                                        @if(isset($item['foto']))
                                            <img src="{{ Storage::url($item['foto']) }}" 
                                                 alt="{{ $item['nama'] }}" 
                                                 class="rounded" 
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 60px; height: 60px;">
                                                <i class="bi bi-building text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $item['nama'] }}</strong>
                                        </div>
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

                                    <td>
                                        <form action="{{ route('keranjang.hapus', $key) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Yakin ingin menghapus ruangan ini dari keranjang?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <form action="{{ route('keranjang.kosongkan-ruangan') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary" 
                                        onclick="return confirm('Yakin ingin mengosongkan keranjang ruangan?')">
                                    <i class="bi bi-trash me-2"></i>Kosongkan Daftar Ruangan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(count($barangItems) == 0 && count($ruanganItems) == 0)
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i> Daftar peminjaman masih kosong.
            </div>
            @else
            <div class="d-flex justify-content-end mt-3">
                <a href="{{ route('peminjaman.form') }}" class="btn btn-success">
                    <i class="bi bi-arrow-right-circle me-1"></i>Lanjutkan Peminjaman
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Toast Notifikasi -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1055;">
    <div id="toastKeranjang" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastKeranjangMsg">
                Jumlah berhasil diperbarui!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateQty(itemId, action) {
    // Disable button selama proses
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="bi bi-hourglass-split"></i>';
    
    fetch(`/keranjang/update-qty/${itemId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            action: action
        })
    })
    .then(response => {
        // Cek content type untuk memastikan response adalah JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Server mengembalikan response non-JSON. Kemungkinan ada masalah dengan CSRF token atau session.');
        }
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        return response.json();
    })
    .then(data => {
        if (data.success) {
            if (data.removed) {
                // Item dihapus dari keranjang, reload halaman
                showAlert('success', data.message);
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
                return;
            }
            
            // Update the quantity display
            document.getElementById(`qty-${itemId}`).textContent = data.newQty;
            
            // Update button states
            const decreaseBtn = document.querySelector(`button[onclick="updateQty('${itemId}', 'decrease')"]`);
            const increaseBtn = document.querySelector(`button[onclick="updateQty('${itemId}', 'increase')"]`);
            
            // Disable/enable decrease button
            if (data.newQty <= 1) {
                decreaseBtn.disabled = true;
            } else {
                decreaseBtn.disabled = false;
            }
            
            // Disable/enable increase button based on stock/capacity
            const item = document.querySelector(`[data-cart-key="${itemId}"]`);
            if (item) {
                const maxValue = item.getAttribute('max');
                if (data.newQty >= maxValue) {
                increaseBtn.disabled = true;
            } else {
                increaseBtn.disabled = false;
                }
            }
            
            // Show success message
            showAlert('success', data.message);
        } else {
            throw new Error(data.message || 'Gagal memperbarui jumlah');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Jika error terkait CSRF, refresh halaman
        if (error.message.includes('CSRF') || error.message.includes('non-JSON')) {
            showAlert('danger', 'Session telah berakhir. Halaman akan di-refresh...');
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            showAlert('danger', 'Terjadi kesalahan saat memperbarui jumlah: ' + error.message);
        }
    })
    .finally(() => {
        // Re-enable button
        button.disabled = false;
        button.innerHTML = originalContent;
    });
}



function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Insert at the top of the main content area
    const mainContent = document.querySelector('.col-md-9.col-lg-10');
    mainContent.insertBefore(alertDiv, mainContent.firstChild);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 3000);
}
</script>
@endpush 