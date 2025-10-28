<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\KeranjangRuanganController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\InventarisController;
use App\Http\Controllers\Admin\InventarisRuanganController;
use App\Http\Controllers\Admin\PeminjamanController as AdminPeminjamanController;
use App\Http\Controllers\Admin\PengembalianController;
use App\Http\Controllers\Admin\ArsipController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AkunController;
use App\Http\Controllers\FotoUploadController;

Route::get('/', [BarangController::class, 'beranda'])->name('beranda');
Route::get('/panduan', function () {
    return view('panduan');
})->name('panduan');
Route::get('/list-barang', [BarangController::class, 'index'])->name('dashboard');
Route::get('/barang/{id}', [BarangController::class, 'show'])->name('barang.detail');
// Routes that require user authentication - temporarily without middleware for testing
Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
Route::post('/keranjang/tambah', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
Route::post('/keranjang/tambah-ruangan', [KeranjangController::class, 'tambahRuangan'])->name('keranjang.tambah-ruangan');
Route::post('/keranjang/hapus/{id}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
Route::post('/keranjang/update-qty/{id}', [KeranjangController::class, 'updateQty'])->name('keranjang.update-qty')->where('id', '.*');
Route::post('/keranjang/kosongkan-ruangan', [KeranjangController::class, 'kosongkanRuangan'])->name('keranjang.kosongkan-ruangan');

Route::get('/peminjaman/form', [PeminjamanController::class, 'form'])->name('peminjaman.form');
Route::post('/peminjaman/ajukan', [PeminjamanController::class, 'ajukan'])->name('peminjaman.ajukan');
Route::post('/pengembalian/ajukan/{id}', [PeminjamanController::class, 'ajukanPengembalian'])->name('pengembalian.ajukan');

// Routes untuk Ruangan
Route::get('/list-ruangan', [RuanganController::class, 'index'])->name('ruangan.index');
Route::get('/ruangan/{ruangan}', [RuanganController::class, 'show'])->name('ruangan.detail');
Route::get('/ruangan/search', [RuanganController::class, 'search'])->name('ruangan.search');

// Foto Upload Routes (untuk modal di form peminjaman)
Route::post('/foto/upload', [FotoUploadController::class, 'upload'])->name('foto.upload');
Route::get('/foto/get', [FotoUploadController::class, 'getFoto'])->name('foto.get');
Route::delete('/foto/delete', [FotoUploadController::class, 'deleteFoto'])->name('foto.delete');

Route::get('/cek-status', [PeminjamanController::class, 'cekStatusForm'])->name('cekStatus.form');
Route::post('/cek-status', [PeminjamanController::class, 'cekStatus'])->name('cekStatus.submit');
Route::get('/cek-status/search', [PeminjamanController::class, 'searchByKegiatan'])->name('cekStatus.search');
Route::get('/cek-status/detail/{id}', [PeminjamanController::class, 'detailPeminjaman'])->name('cekStatus.detail');
Route::get('/list-peminjam', [PeminjamanController::class, 'listPeminjam'])->name('list.peminjam');
Route::get('/list-peminjam/detail/{id}', [PeminjamanController::class, 'detailPeminjamPublic'])->name('list.peminjam.detail');
Route::get('/api/list-peminjam/detail/{id}', [PeminjamanController::class, 'getDetailPeminjamApi'])->name('api.list.peminjam.detail');

// Route login user
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Route login admin
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

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
    Route::post('peminjaman/{id}/adjust', [AdminPeminjamanController::class, 'adjust'])->name('peminjaman.adjust');
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
    Route::get('arsip/{id}/export/pdf', [ArsipController::class, 'exportPeminjamanPdf'])->name('arsip.peminjaman.export.pdf');
    
    // Kelola Akun
    Route::resource('akun', AkunController::class);
    Route::get('akun/{id}/reset-password', [AkunController::class, 'resetPassword'])->name('akun.reset-password');
    Route::post('akun/{id}/update-password', [AkunController::class, 'updatePassword'])->name('akun.update-password');
    
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




