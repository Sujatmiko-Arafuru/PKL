<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Get unread notifications count
     */
    public function getUnreadCount(): JsonResponse
    {
        $count = NotificationService::getUnreadCount();
        $peminjamanCount = NotificationService::getUnreadCountByType('peminjaman_baru');
        
        return response()->json([
            'total_unread' => $count,
            'peminjaman_unread' => $peminjamanCount
        ]);
    }

    /**
     * Get recent notifications
     */
    public function getRecentNotifications(): JsonResponse
    {
        $notifications = NotificationService::getRecentNotifications(10);
        
        return response()->json([
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request): JsonResponse
    {
        $request->validate([
            'notification_id' => 'required|integer|exists:notifications,id'
        ]);

        $success = NotificationService::markAsRead($request->notification_id);
        
        if ($success) {
            $count = NotificationService::getUnreadCount();
            return response()->json([
                'success' => true,
                'message' => 'Notifikasi ditandai sebagai dibaca',
                'unread_count' => $count
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal menandai notifikasi'
        ], 400);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): JsonResponse
    {
        $count = NotificationService::markAllAsRead();
        
        return response()->json([
            'success' => true,
            'message' => "{$count} notifikasi ditandai sebagai dibaca",
            'unread_count' => 0
        ]);
    }

    /**
     * Get notification statistics
     */
    public function getStatistics(): JsonResponse
    {
        $stats = NotificationService::getStatistics();
        
        return response()->json([
            'statistics' => $stats
        ]);
    }

    /**
     * Get notifications for peminjaman menu
     */
    public function getPeminjamanNotifications(): JsonResponse
    {
        $peminjamanCount = NotificationService::getUnreadCountByType('peminjaman_baru');
        $recentPeminjaman = NotificationService::getRecentNotifications(5);
        
        return response()->json([
            'peminjaman_unread_count' => $peminjamanCount,
            'recent_peminjaman' => $recentPeminjaman
        ]);
    }
}
