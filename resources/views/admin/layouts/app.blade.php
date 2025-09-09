<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin SarPras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/custom-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/components.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/table-improvements.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/modal-improvements.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/photo-gallery.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/notification-system.css') }}" rel="stylesheet">
    <style>
        body { background: #E0FFFF; }
        .sidebar {
            min-height: 100vh;
            background: #20B2AA;
            color: #fff;
            width: 220px;
            position: fixed;
            left: 0; top: 0; bottom: 0;
            z-index: 100;
        }
        .sidebar .nav-link { color: #fff; font-weight: 500; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { background: #008B8B; color: #fff; }
        .sidebar .nav-link .bi { margin-right: 8px; }
        .main-content { margin-left: 220px; padding: 2rem 2rem 2rem 2rem; }
        .topbar { background: #fff; border-bottom: 1px solid #E0FFFF; padding: 1rem 2rem; margin-left: 220px; }
        
        /* Button styling for admin */
        .btn {
            transition: all 0.3s ease;
            border-radius: 0.5rem;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .btn-group .btn {
            margin: 0 2px;
        }
        
        .table {
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .table thead {
            background: linear-gradient(135deg, #20B2AA, #48D1CC);
            color: white;
        }
        
        .badge {
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
        }
        
        .nav-item {
            position: relative;
        }
        
        /* Fallback notification badge styling if CSS file fails to load */
        .notification-badge {
            position: absolute !important;
            top: -8px !important;
            right: -8px !important;
            background: #dc3545 !important;
            color: white !important;
            border-radius: 50% !important;
            width: 22px !important;
            height: 22px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 0.75rem !important;
            font-weight: bold !important;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.4) !important;
            border: 2px solid #fff !important;
            z-index: 1000 !important;
            transition: all 0.3s ease !important;
        }
        
        @media (max-width: 991px) {
            .sidebar, .main-content, .topbar { margin-left: 0 !important; }
            .sidebar { position: static; width: 100%; min-height: auto; }
        }
        
        /* Pagination Styling */
        .pagination {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 15px;
            overflow: hidden;
        }

        .pagination .page-link {
            border: none;
            color: #0d6efd;
            padding: 12px 16px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .pagination .page-link:hover {
            background-color: #e7f1ff;
            color: #0d6efd;
            transform: translateY(-2px);
        }

        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #f8f9fa;
        }

        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            border-radius: 0;
        }

        /* Responsive pagination text */
        @media (max-width: 576px) {
            .pagination .page-link {
                padding: 10px 12px;
                font-size: 0.9rem;
            }
        }

        /* Page info styling */
        .text-muted small {
            font-size: 0.875rem;
            font-weight: 500;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <div class="sidebar d-flex flex-column p-3">
        <h4 class="mb-4">SarPras Admin</h4>
        <ul class="nav nav-pills flex-column mb-auto">
            <li><a href="{{ route('admin.dashboard') }}" class="nav-link{{ request()->routeIs('admin.dashboard') ? ' active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li><a href="{{ route('admin.inventaris.index') }}" class="nav-link{{ request()->routeIs('admin.inventaris.*') ? ' active' : '' }}"><i class="bi bi-box-seam"></i> Inventaris Barang</a></li>
            <li><a href="{{ route('admin.inventaris-ruangan.index') }}" class="nav-link{{ request()->routeIs('admin.inventaris-ruangan.*') ? ' active' : '' }}"><i class="bi bi-building"></i> Inventaris Ruangan</a></li>
            <li class="nav-item">
                <a href="{{ route('admin.peminjaman.index') }}" class="nav-link{{ request()->routeIs('admin.peminjaman.*') ? ' active' : '' }}">
                    <i class="bi bi-journal-plus"></i> Peminjaman
                    <span id="peminjamanNotificationBadge" class="notification-badge" style="display: none;">0</span>
                </a>
            </li>
            <li><a href="{{ route('admin.pengembalian.index') }}" class="nav-link{{ request()->routeIs('admin.pengembalian.*') ? ' active' : '' }}"><i class="bi bi-arrow-repeat"></i> Pengembalian</a></li>
            <li><a href="{{ route('admin.arsip.index') }}" class="nav-link{{ request()->routeIs('admin.arsip.*') ? ' active' : '' }}"><i class="bi bi-archive"></i> Arsip</a></li>
            <li><a href="{{ route('admin.akun.index') }}" class="nav-link{{ request()->routeIs('admin.akun.*') ? ' active' : '' }}"><i class="bi bi-people"></i> Kelola Akun</a></li>
            <li>
                <form action="{{ route('admin.logout') }}" method="POST" class="mt-3">
                    @csrf
                    <button class="btn btn-danger w-100"><i class="bi bi-box-arrow-right"></i> Logout</button>
                </form>
            </li>
        </ul>
    </div>
    <div class="topbar d-flex align-items-center justify-content-between">
        <span>Selamat datang, Admin SarPras</span>
    </div>
    <main class="main-content">
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Notification System Script -->
    <script>
        // Reset badge immediately when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const badge = document.getElementById('peminjamanNotificationBadge');
            if (badge) {
                badge.textContent = '0';
                badge.style.display = 'none';
            }
        });
        
        class NotificationSystem {
            constructor() {
                this.badge = document.getElementById('peminjamanNotificationBadge');
                this.updateInterval = 30000; // Update every 30 seconds
                this.init();
            }
            
            init() {
                console.log('NotificationSystem initialized');
                console.log('Badge element:', this.badge);
                
                // Reset badge to ensure it's hidden initially
                if (this.badge) {
                    this.badge.textContent = '0';
                    this.badge.style.display = 'none';
                }
                
                this.updateNotificationCount();
                this.startAutoUpdate();
                this.setupEventListeners();
            }
            
            async updateNotificationCount() {
                try {
                    console.log('Updating notification count...');
                    const response = await fetch('/admin/notifications/peminjaman');
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const data = await response.json();
                    console.log('Notification data:', data);
                    
                    // Ensure we have a valid count
                    const count = parseInt(data.peminjaman_unread_count || 0);
                    
                    if (count > 0) {
                        console.log('Setting badge to show with count:', count);
                        this.badge.textContent = count;
                        this.badge.style.display = 'flex';
                        
                        // Add sound notification for new notifications
                        const currentCount = parseInt(this.badge.textContent || 0);
                        if (count > currentCount) {
                            this.playNotificationSound();
                        }
                    } else {
                        console.log('Hiding badge - no unread notifications');
                        this.badge.textContent = '0';
                        this.badge.style.display = 'none';
                    }
                } catch (error) {
                    console.error('Error updating notification count:', error);
                    // Hide badge on error to prevent showing stale data
                    this.badge.textContent = '0';
                    this.badge.style.display = 'none';
                }
            }
            
            startAutoUpdate() {
                console.log('Starting auto-update with interval:', this.updateInterval);
                setInterval(() => {
                    this.updateNotificationCount();
                }, this.updateInterval);
            }
            
            setupEventListeners() {
                // Update count when clicking on peminjaman menu
                const peminjamanLink = document.querySelector('a[href*="peminjaman"]');
                if (peminjamanLink) {
                    peminjamanLink.addEventListener('click', () => {
                        console.log('Peminjaman menu clicked, marking notifications as read');
                        // Mark peminjaman notifications as read when visiting the page
                        this.markPeminjamanNotificationsAsRead();
                    });
                }
                
                // Update count when page becomes visible
                document.addEventListener('visibilitychange', () => {
                    if (!document.hidden) {
                        console.log('Page became visible, updating notifications');
                        this.updateNotificationCount();
                    }
                });
            }
            
            async markPeminjamanNotificationsAsRead() {
                try {
                    console.log('Marking peminjaman notifications as read...');
                    await fetch('/admin/notifications/mark-all-read', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    // Update badge immediately
                    this.badge.style.display = 'none';
                    console.log('Notifications marked as read, badge hidden');
                } catch (error) {
                    console.error('Error marking notifications as read:', error);
                }
            }
            
            playNotificationSound() {
                // Create audio context for notification sound
                try {
                    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                    const oscillator = audioContext.createOscillator();
                    const gainNode = audioContext.createGain();
                    
                    oscillator.connect(gainNode);
                    gainNode.connect(audioContext.destination);
                    
                    oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
                    oscillator.frequency.setValueAtTime(600, audioContext.currentTime + 0.1);
                    
                    gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
                    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);
                    
                    oscillator.start(audioContext.currentTime);
                    oscillator.stop(audioContext.currentTime + 0.2);
                } catch (error) {
                    console.log('Audio notification not supported');
                }
            }
            
            // Method to manually update count (can be called from other parts of the app)
            forceUpdate() {
                console.log('Force updating notification count...');
                this.updateNotificationCount();
            }
        }
        
        // Initialize notification system when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing NotificationSystem...');
            window.notificationSystem = new NotificationSystem();
        });
        
        // Expose to global scope for external access
        window.NotificationSystem = NotificationSystem;
    </script>
</body>
</html> 