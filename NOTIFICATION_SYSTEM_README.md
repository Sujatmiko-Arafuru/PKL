# Sistem Notifikasi Real-Time - Admin SarPras

## Overview
Sistem notifikasi real-time yang memberikan informasi kepada admin ketika ada mahasiswa yang mengajukan peminjaman, perubahan status peminjaman, dan pengembalian barang. Sistem ini menggunakan badge merah bulat pada menu peminjaman dengan update otomatis setiap 30 detik.

## Fitur Utama

### 1. **Badge Notifikasi Real-Time**
- **Lokasi**: Pojok kanan atas menu "Peminjaman" di sidebar admin
- **Desain**: Badge merah bulat dengan animasi pulse
- **Update**: Otomatis setiap 30 detik
- **Sound**: Notifikasi audio untuk peminjaman baru

### 2. **Tipe Notifikasi**
- **Peminjaman Baru**: Saat mahasiswa mengajukan peminjaman
- **Status Berubah**: Saat admin approve/reject peminjaman
- **Pengembalian**: Saat mahasiswa mengembalikan barang

### 3. **Sistem Auto-Update**
- **Real-time**: Update otomatis tanpa refresh halaman
- **Smart Refresh**: Update saat tab menjadi aktif
- **Performance**: Optimized dengan interval yang dapat diatur

## Struktur Database

### Tabel `notifications`
```sql
CREATE TABLE notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(255) NOT NULL, -- 'peminjaman_baru', 'status_berubah', 'pengembalian'
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('unread', 'read') DEFAULT 'unread',
    peminjaman_id BIGINT UNSIGNED NULL,
    user_id BIGINT UNSIGNED NULL,
    data JSON NULL,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (peminjaman_id) REFERENCES peminjamans(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    
    INDEX idx_status_created (status, created_at),
    INDEX idx_type_status (type, status)
);
```

## Arsitektur Sistem

### 1. **Models**
- `Notification`: Model utama untuk notifikasi
- `Peminjaman`: Model peminjaman dengan relasi notifikasi
- `User`: Model user/admin

### 2. **Services**
- `NotificationService`: Service utama untuk mengelola notifikasi
- Methods:
  - `create()`: Membuat notifikasi baru
  - `notifyNewPeminjaman()`: Notifikasi peminjaman baru
  - `notifyPeminjamanStatusChange()`: Notifikasi perubahan status
  - `notifyPengembalian()`: Notifikasi pengembalian
  - `getUnreadCount()`: Mendapatkan jumlah notifikasi unread
  - `markAsRead()`: Menandai notifikasi sebagai dibaca

### 3. **Controllers**
- `NotificationController`: API endpoints untuk notifikasi
- `PeminjamanController`: Controller peminjaman dengan integrasi notifikasi

### 4. **Routes**
```php
Route::prefix('admin/notifications')->name('notifications.')->group(function () {
    Route::get('/unread-count', 'getUnreadCount');
    Route::get('/recent', 'getRecentNotifications');
    Route::post('/mark-read', 'markAsRead');
    Route::post('/mark-all-read', 'markAllAsRead');
    Route::get('/statistics', 'getStatistics');
    Route::get('/peminjaman', 'getPeminjamanNotifications');
});
```

## Implementasi Frontend

### 1. **JavaScript Class: NotificationSystem**
```javascript
class NotificationSystem {
    constructor() {
        this.badge = document.getElementById('peminjamanNotificationBadge');
        this.updateInterval = 30000; // 30 detik
        this.init();
    }
    
    // Methods untuk update, auto-refresh, dan event handling
}
```

### 2. **CSS Styling**
- **File**: `public/assets/css/notification-system.css`
- **Features**: 
  - Animasi pulse untuk badge
  - Responsive design
  - Dark mode support
  - Smooth transitions

### 3. **HTML Structure**
```html
<li class="nav-item">
    <a href="{{ route('admin.peminjaman.index') }}" class="nav-link">
        <i class="bi bi-journal-plus"></i> Peminjaman
        <span id="peminjamanNotificationBadge" class="notification-badge" style="display: none;">0</span>
    </a>
</li>
```

## Flow Sistem

### 1. **Peminjaman Baru**
```
Mahasiswa Submit Form → PeminjamanController::ajukan() → 
NotificationService::notifyNewPeminjaman() → 
Database Notification → Badge Update (30s) → Admin Melihat Badge
```

### 2. **Update Status**
```
Admin Approve/Reject → PeminjamanController::approve/reject() → 
NotificationService::notifyPeminjamanStatusChange() → 
Database Notification → Badge Update
```

### 3. **Real-time Update**
```
JavaScript Interval (30s) → API Call → Update Badge → 
Visual Feedback (Pulse Animation + Sound)
```

## API Endpoints

### 1. **GET /admin/notifications/peminjaman**
```json
{
    "peminjaman_unread_count": 3,
    "recent_peminjaman": [
        {
            "id": 1,
            "type": "peminjaman_baru",
            "title": "Peminjaman Baru",
            "message": "Mahasiswa John Doe (12345) mengajukan peminjaman...",
            "created_at": "2025-01-20T10:30:00Z"
        }
    ]
}
```

### 2. **POST /admin/notifications/mark-all-read**
```json
{
    "success": true,
    "message": "3 notifikasi ditandai sebagai dibaca",
    "unread_count": 0
}
```

## Konfigurasi

### 1. **Update Interval**
```javascript
this.updateInterval = 30000; // 30 detik (dapat diubah)
```

### 2. **Badge Styling**
```css
.notification-badge {
    width: 22px;
    height: 22px;
    background: linear-gradient(135deg, #dc3545, #c82333);
    animation: notificationPulse 2s infinite;
}
```

### 3. **Sound Notification**
```javascript
playNotificationSound() {
    // Audio context untuk notifikasi sound
    // Frequency: 800Hz → 600Hz
    // Duration: 0.2 detik
}
```

## Fitur Tambahan

### 1. **Auto-Mark as Read**
- Badge otomatis hilang saat admin klik menu peminjaman
- Semua notifikasi peminjaman ditandai sebagai dibaca

### 2. **Smart Refresh**
- Update saat tab menjadi aktif
- Update saat halaman di-refresh
- Update saat navigasi antar halaman

### 3. **Performance Optimization**
- Lazy loading untuk notifikasi
- Batch update untuk multiple notifications
- Efficient database queries dengan indexing

## Troubleshooting

### 1. **Badge Tidak Muncul**
- Check browser console untuk JavaScript errors
- Verify API endpoint `/admin/notifications/peminjaman`
- Check database table `notifications`

### 2. **Update Tidak Real-time**
- Verify JavaScript interval (30 detik)
- Check network requests di browser dev tools
- Verify CSRF token untuk POST requests

### 3. **Sound Tidak Berfungsi**
- Check browser audio permissions
- Verify AudioContext support
- Check console untuk audio errors

## Maintenance

### 1. **Cleanup Old Notifications**
```php
// Otomatis hapus notifikasi > 30 hari
NotificationService::cleanupOldNotifications();
```

### 2. **Database Optimization**
```sql
-- Index untuk performa query
CREATE INDEX idx_status_created ON notifications(status, created_at);
CREATE INDEX idx_type_status ON notifications(type, status);
```

### 3. **Monitoring**
- Log semua notifikasi yang dibuat
- Track performance API calls
- Monitor database query performance

## Future Enhancements

### 1. **Push Notifications**
- Browser push notifications
- Email notifications
- SMS notifications

### 2. **Advanced Filtering**
- Filter by notification type
- Filter by date range
- Search notifications

### 3. **User Preferences**
- Custom notification sounds
- Notification frequency settings
- Do not disturb mode

### 4. **Real-time WebSocket**
- WebSocket connection untuk instant updates
- Live chat notifications
- Real-time collaboration features

## Keamanan

### 1. **CSRF Protection**
- Semua POST requests menggunakan CSRF token
- Validasi input untuk mencegah XSS

### 2. **Access Control**
- Hanya admin yang dapat mengakses notifikasi
- Validasi user permissions

### 3. **Data Validation**
- Sanitasi input untuk mencegah injection
- Validasi file uploads

## Testing

### 1. **Unit Tests**
```php
// Test NotificationService
public function test_notify_new_peminjaman()
{
    $peminjaman = Peminjaman::factory()->create();
    $notification = NotificationService::notifyNewPeminjaman($peminjaman);
    
    $this->assertNotNull($notification);
    $this->assertEquals('peminjaman_baru', $notification->type);
}
```

### 2. **Integration Tests**
```php
// Test API endpoints
public function test_get_peminjaman_notifications()
{
    $response = $this->get('/admin/notifications/peminjaman');
    $response->assertStatus(200);
    $response->assertJsonStructure(['peminjaman_unread_count', 'recent_peminjaman']);
}
```

## Deployment

### 1. **Database Migration**
```bash
php artisan migrate
```

### 2. **File Permissions**
```bash
chmod 644 public/assets/css/notification-system.css
chmod 644 app/Services/NotificationService.php
```

### 3. **Cache Clear**
```bash
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

## Support

### 1. **Log Files**
- Check `storage/logs/laravel.log` untuk error logs
- Monitor notification creation logs

### 2. **Debug Mode**
```php
// Enable debug mode untuk development
APP_DEBUG=true
```

### 3. **Performance Monitoring**
- Monitor API response times
- Track database query performance
- Monitor memory usage

Sistem notifikasi ini memberikan pengalaman yang seamless bagi admin untuk selalu mengetahui status terbaru dari peminjaman barang, dengan update real-time dan visual feedback yang jelas.
