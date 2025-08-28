<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Create a new notification
     */
    public static function create($type, $title, $message, $peminjamanId = null, $userId = null, $data = [])
    {
        try {
            $notification = Notification::create([
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'status' => 'unread',
                'peminjaman_id' => $peminjamanId,
                'user_id' => $userId,
                'data' => $data
            ]);

            // Broadcast notification to all admin users if no specific user
            if (!$userId) {
                self::broadcastToAllAdmins($notification);
            }

            return $notification;
        } catch (\Exception $e) {
            Log::error('Failed to create notification: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create notification for new peminjaman
     */
    public static function notifyNewPeminjaman(Peminjaman $peminjaman)
    {
        $title = 'Peminjaman Baru';
        $message = "Mahasiswa {$peminjaman->nama} ({$peminjaman->nim_nip}) mengajukan peminjaman untuk kegiatan {$peminjaman->nama_kegiatan}";
        
        $data = [
            'peminjaman_id' => $peminjaman->id,
            'nama' => $peminjaman->nama,
            'nim_nip' => $peminjaman->nim_nip,
            'nama_kegiatan' => $peminjaman->nama_kegiatan,
            'tanggal_mulai' => $peminjaman->tanggal_mulai,
            'tanggal_selesai' => $peminjaman->tanggal_selesai
        ];

        return self::create('peminjaman_baru', $title, $message, $peminjaman->id, null, $data);
    }

    /**
     * Create notification for peminjaman status change
     */
    public static function notifyPeminjamanStatusChange(Peminjaman $peminjaman, $oldStatus, $newStatus)
    {
        $statusLabels = [
            'menunggu' => 'Menunggu Persetujuan',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'dipinjam' => 'Sedang Dipinjam',
            'dikembalikan' => 'Dikembalikan'
        ];

        $title = 'Status Peminjaman Berubah';
        $message = "Status peminjaman {$peminjaman->nama_kegiatan} berubah dari {$statusLabels[$oldStatus]} menjadi {$statusLabels[$newStatus]}";
        
        $data = [
            'peminjaman_id' => $peminjaman->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'nama_kegiatan' => $peminjaman->nama_kegiatan
        ];

        return self::create('status_berubah', $title, $message, $peminjaman->id, null, $data);
    }

    /**
     * Create notification for pengembalian
     */
    public static function notifyPengembalian(Peminjaman $peminjaman)
    {
        $title = 'Pengembalian Barang';
        $message = "Mahasiswa {$peminjaman->nama} telah mengembalikan barang untuk peminjaman {$peminjaman->nama_kegiatan}";
        
        $data = [
            'peminjaman_id' => $peminjaman->id,
            'nama' => $peminjaman->nama,
            'nama_kegiatan' => $peminjaman->nama_kegiatan
        ];

        return self::create('pengembalian', $title, $message, $peminjaman->id, null, $data);
    }

    /**
     * Get unread notifications count
     */
    public static function getUnreadCount($userId = null)
    {
        $query = Notification::unread();
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->count();
    }

    /**
     * Get unread notifications count by type
     */
    public static function getUnreadCountByType($type, $userId = null)
    {
        $query = Notification::unread()->ofType($type);
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->count();
    }

    /**
     * Get recent notifications
     */
    public static function getRecentNotifications($limit = 10, $userId = null)
    {
        $query = Notification::orderBy('created_at', 'desc');
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->limit($limit)->get();
    }

    /**
     * Mark notification as read
     */
    public static function markAsRead($notificationId, $userId = null)
    {
        $query = Notification::where('id', $notificationId);
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        $notification = $query->first();
        
        if ($notification) {
            $notification->markAsRead();
            return true;
        }
        
        return false;
    }

    /**
     * Mark all notifications as read
     */
    public static function markAllAsRead($userId = null)
    {
        $query = Notification::unread();
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->update([
            'status' => 'read',
            'read_at' => now()
        ]);
    }

    /**
     * Delete old notifications (older than 30 days)
     */
    public static function cleanupOldNotifications()
    {
        $thirtyDaysAgo = now()->subDays(30);
        
        return Notification::where('created_at', '<', $thirtyDaysAgo)
            ->where('status', 'read')
            ->delete();
    }

    /**
     * Broadcast notification to all admin users
     */
    private static function broadcastToAllAdmins($notification)
    {
        // This will be used for real-time notifications
        // For now, we'll just log it
        Log::info('Broadcasting notification to all admins', [
            'notification_id' => $notification->id,
            'type' => $notification->type
        ]);
    }

    /**
     * Get notification statistics
     */
    public static function getStatistics($userId = null)
    {
        $query = Notification::query();
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        return [
            'total' => $query->count(),
            'unread' => $query->unread()->count(),
            'read' => $query->read()->count(),
            'today' => $query->whereDate('created_at', today())->count(),
            'this_week' => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => $query->whereMonth('created_at', now()->month)->count()
        ];
    }
}
