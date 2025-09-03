<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\KeranjangRuanganController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\InventarisController;
use App\Http\Controllers\Admin\InventarisRuanganController;
use App\Http\Controllers\Admin\PeminjamanController as AdminPeminjamanController;
use App\Http\Controllers\Admin\PengembalianController;
use App\Http\Controllers\Admin\ArsipController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', [BarangController::class, 'beranda'])->name('beranda');
Route::get('/list-barang', [BarangController::class, 'index'])->name('dashboard');
Route::get('/barang/{id}', [BarangController::class, 'show'])->name('barang.detail');
Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
Route::post('/keranjang/tambah', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
Route::post('/keranjang/tambah-ruangan', [KeranjangController::class, 'tambahRuangan'])->name('keranjang.tambah-ruangan');
Route::post('/keranjang/hapus/{id}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
Route::post('/keranjang/update-qty/{id}', [KeranjangController::class, 'updateQty'])->name('keranjang.update-qty');

Route::post('/keranjang/kosongkan-ruangan', [KeranjangController::class, 'kosongkanRuangan'])->name('keranjang.kosongkan-ruangan');

// Routes untuk Ruangan
Route::get('/list-ruangan', [RuanganController::class, 'index'])->name('ruangan.index');
Route::get('/ruangan/{id}', [RuanganController::class, 'show'])->name('ruangan.detail');
Route::get('/ruangan/search', [RuanganController::class, 'search'])->name('ruangan.search');

Route::get('/peminjaman/form', [PeminjamanController::class, 'form'])->name('peminjaman.form');
Route::post('/peminjaman/ajukan', [PeminjamanController::class, 'ajukan'])->name('peminjaman.ajukan');
Route::post('/pengembalian/ajukan/{id}', [PeminjamanController::class, 'ajukanPengembalian'])->name('pengembalian.ajukan');
Route::get('/cek-status', [PeminjamanController::class, 'cekStatusForm'])->name('cekStatus.form');
Route::post('/cek-status', [PeminjamanController::class, 'cekStatus'])->name('cekStatus.submit');
Route::get('/cek-status/search', [PeminjamanController::class, 'searchByKegiatan'])->name('cekStatus.search');
Route::get('/cek-status/detail/{id}', [PeminjamanController::class, 'detailPeminjaman'])->name('cekStatus.detail');
Route::get('/list-peminjam', [PeminjamanController::class, 'listPeminjam'])->name('list.peminjam');
Route::get('/list-peminjam/detail/{id}', [PeminjamanController::class, 'detailPeminjamPublic'])->name('list.peminjam.detail');
Route::get('/api/list-peminjam/detail/{id}', [PeminjamanController::class, 'getDetailPeminjamApi'])->name('api.list.peminjam.detail');

// Route login admin
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Route group admin dengan middleware
Route::middleware([\App\Http\Middleware\AdminAuth::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // CRUD Inventaris Barang
    Route::resource('inventaris', InventarisController::class);
    // CRUD Inventaris Ruangan
    Route::resource('inventaris-ruangan', InventarisRuanganController::class)->parameters([
        'inventaris-ruangan' => 'ruangan'
    ]);
    // Kelola Peminjaman
    Route::get('peminjaman', [AdminPeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('peminjaman/{id}', [AdminPeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::post('peminjaman/{id}/approve', [AdminPeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('peminjaman/{id}/reject', [AdminPeminjamanController::class, 'reject'])->name('peminjaman.reject');
    // Kelola Pengembalian
    Route::get('pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
    Route::get('pengembalian/{id}', [PengembalianController::class, 'show'])->name('pengembalian.show');
    Route::post('pengembalian/input-kode', [PengembalianController::class, 'inputKodePengembalian'])->name('pengembalian.input-kode');
    Route::post('pengembalian/{id}/bulk-update', [PengembalianController::class, 'bulkUpdatePengembalian'])->name('pengembalian.bulk-update');
    Route::post('pengembalian/{id}/return-room', [PengembalianController::class, 'returnRoom'])->name('pengembalian.return-room');
    Route::get('pengembalian/api/returnable', [PengembalianController::class, 'getPeminjamanForReturn'])->name('pengembalian.api.returnable');
    // Arsip
    Route::get('arsip', [ArsipController::class, 'index'])->name('arsip.index');
    Route::get('arsip/{id}', [ArsipController::class, 'show'])->name('arsip.show');
    Route::get('arsip/export/pdf', [ArsipController::class, 'exportPdf'])->name('arsip.export.pdf');
    
    // Notifikasi API
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/unread-count', [\App\Http\Controllers\Admin\NotificationController::class, 'getUnreadCount'])->name('unread-count');
        Route::get('/recent', [\App\Http\Controllers\Admin\NotificationController::class, 'getRecentNotifications'])->name('recent');
        Route::post('/mark-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::get('/statistics', [\App\Http\Controllers\Admin\NotificationController::class, 'getStatistics'])->name('statistics');
        Route::get('/peminjaman', [\App\Http\Controllers\Admin\NotificationController::class, 'getPeminjamanNotifications'])->name('peminjaman');
        
        // Test route
        Route::get('/test', function() {
            return response()->json([
                'message' => 'Notification system is working',
                'notifications_count' => \App\Models\Notification::count(),
                'unread_count' => \App\Models\Notification::where('status', 'unread')->count(),
                'peminjaman_unread' => \App\Models\Notification::where('status', 'unread')->where('type', 'peminjaman_baru')->count()
            ]);
        })->name('test');
    });
});




