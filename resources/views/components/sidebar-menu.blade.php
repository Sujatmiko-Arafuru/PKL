<style>
    .alert-sm {
        padding: 0.5rem;
        font-size: 0.875rem;
    }
</style>

<div class="col-md-3 col-lg-2">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="card-title text-primary mb-3"><i class="bi bi-list"></i> Menu</h5>
            <div class="d-grid gap-2">
                
                <a href="{{ route('beranda') }}" class="btn {{ request()->routeIs('beranda') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-house me-2"></i>Beranda
                </a>
                <a href="{{ route('dashboard') }}" class="btn {{ request()->routeIs('dashboard') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-box-seam me-2"></i>List Barang
                </a>
                <a href="{{ route('ruangan.index') }}" class="btn {{ request()->routeIs('ruangan.*') ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-building me-2"></i>List Ruangan
                </a>
                <a href="{{ route('keranjang.index') }}" class="btn {{ request()->routeIs('keranjang.index') ? 'btn-primary' : 'btn-outline-primary' }} position-relative">
                    <i class="bi bi-cart3 me-2"></i>Daftar Peminjaman
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        @php
                            $cart = session('cart', []);
                            $totalItems = count($cart);
                        @endphp
                        {{ $totalItems }}
                    </span>
                </a>
                <a href="{{ route('list.peminjam') }}" class="btn {{ request()->routeIs('list.peminjam*') ? 'btn-info' : 'btn-outline-info' }}">
                    <i class="bi bi-people me-2"></i>List Peminjam
                </a>

            </div>
        </div>
    </div>
</div> 