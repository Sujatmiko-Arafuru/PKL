<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIMBARA - Poltekkes Denpasar</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}" onerror="this.href='{{ asset('favicon.ico') }}'">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/css/custom-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/components.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/table-improvements.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/horizontal-scroll.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/beranda.css') }}">
    
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .main-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Header/Navbar */
        .header {
            background: #20B2AA !important; /* main color */
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            padding: 0.5rem 0;
            border-bottom: none !important;
            box-shadow: none !important;
            z-index: 10;
            transition: all 0.3s ease;
        }
        
        .header:hover {
            background: #20B2AA !important; /* tetap main color saat hover */
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: 1.2rem;
            color: white !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }
        
        .navbar-brand:hover {
            color: white !important;
        }
        
        /* Override Bootstrap navbar colors */
        .navbar { 
            background-color: #20B2AA !important; 
            box-shadow: none !important; 
        }
        .navbar-nav { 
            background-color: #20B2AA !important; 
        }
        .navbar-nav .nav-link { 
            color: #ffffff !important; 
        }
        .navbar-nav .nav-link:hover {
            color: white !important;
        }
        
        .navbar-logo {
            height: 35px;
            width: auto;
            margin-right: 10px;
            object-fit: contain;
            background-color: transparent !important; /* hapus latar logo putih */
            padding: 0;
            border-radius: 0;
            display: inline-block !important;
            vertical-align: middle;
            max-width: 100px;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            border: none !important;
        }
        
        .btn-outline-light {
            border-color: rgba(255,255,255,0.05);
            color: white;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            padding: 0.4rem 1rem;
            background: rgba(255, 255, 255, 0.01);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }
        
        .btn-outline-light:hover {
            background-color: rgba(255, 255, 255, 0.08);
            color: white;
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }
        
        /* Hero Section */
        .hero-section {
            flex: 1;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            background: none; /* tampilkan video, tanpa gradient */
            overflow: hidden;
        }
        
        @keyframes gradientShift {
            0% {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            50% {
                background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            }
            100% {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
        }
        
        /* Video harus paling belakang tapi tetap di atas background container */
        .video-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0; /* di atas background container */
            min-width: 100%;
            min-height: 100%;
            opacity: 1;
            pointer-events: none;
        }
        
        .hero-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: transparent; /* hapus pola agar tidak menutupi video */
            opacity: 0; /* nonaktifkan */
            z-index: 0;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }
        
        /* Particles Animation */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 2;
        }
        
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: particleFloat 15s infinite linear;
        }
        
        .particle:nth-child(1) {
            left: 10%;
            animation-delay: 0s;
            animation-duration: 20s;
        }
        
        .particle:nth-child(2) {
            left: 20%;
            animation-delay: 2s;
            animation-duration: 25s;
        }
        
        .particle:nth-child(3) {
            left: 30%;
            animation-delay: 4s;
            animation-duration: 18s;
        }
        
        .particle:nth-child(4) {
            left: 40%;
            animation-delay: 6s;
            animation-duration: 22s;
        }
        
        .particle:nth-child(5) {
            left: 50%;
            animation-delay: 8s;
            animation-duration: 16s;
        }
        
        .particle:nth-child(6) {
            left: 60%;
            animation-delay: 10s;
            animation-duration: 24s;
        }
        
        @keyframes particleFloat {
            0% {
                transform: translateY(100vh) scale(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) scale(1);
                opacity: 0;
            }
        }
        
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.25); /* sedikit gelap agar teks terbaca */
            z-index: 1;
        }
        
        .hero-content {
            max-width: 700px;
            padding: 0 20px;
            z-index: 3; /* konten di atas overlay */
            position: relative;
        }
        
        .hero-title {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
            line-height: 1.2;
        }
        
        .hero-subtitle {
            font-size: 1.1rem;
            font-weight: 400;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
            line-height: 1.5;
            opacity: 0.95;
        }
        
        .cta-button {
            background: linear-gradient(45deg, #20B2AA, #008B8B);
            color: white;
            padding: 12px 30px;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 50px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(32, 178, 170, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(32, 178, 170, 0.4);
            color: white;
            text-decoration: none;
        }
        

        
        /* Footer */
        .footer {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            z-index: 10;
            background: transparent !important; /* pastikan tidak membentuk bar hijau */
            border: none !important;
            padding: 0;
        }
        
        .footer-text {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.85);
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
            font-weight: 400;
            letter-spacing: 1px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.2rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
            
            .cta-button {
                padding: 10px 25px;
                font-size: 0.9rem;
            }
            
            .navbar-brand {
                font-size: 1rem;
            }
            
            .navbar-logo {
                height: 28px;
                margin-right: 8px;
                padding: 2px 4px;
            }
            
            .feature-card {
                margin: 0 0.25rem;
                padding: 0.75rem;
            }
            
            .feature-icon {
                font-size: 1.5rem;
            }
            
            .feature-title {
                font-size: 0.8rem;
            }
            
            .feature-desc {
                font-size: 0.7rem;
            }
        }
        
        @media (max-width: 480px) {
            .hero-title {
                font-size: 1.8rem;
            }
            
            .hero-subtitle {
                font-size: 0.9rem;
            }
            
            .features-container {
                padding: 0 10px;
            }
            
            .feature-card {
                margin: 0 0.2rem;
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Header -->
        <nav class="navbar navbar-expand-lg header">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('beranda') }}">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo SIMBARA" class="navbar-logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                    <i class="bi bi-hospital me-2" style="display: none; color: white;"></i>
                    SIMBARA Poltekkes Denpasar
                </a>
                <div class="navbar-nav ms-auto">
                    <a class="btn btn-outline-light" href="{{ route('admin.login') }}">
                        <i class="bi bi-person-circle me-1"></i>Login Admin
                    </a>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="hero-section">
            <!-- Video Background -->
            <video class="video-background" autoplay muted loop playsinline preload="auto" poster="/assets/images/placeholder.jpg">
                <source src="/assets/videos/background.mp4" type="video/mp4">
                <!-- Fallback untuk browser yang tidak support video -->
            </video>
            
            <!-- Fallback Background -->
            <div class="hero-background"></div>
            <div class="hero-overlay"></div>
            
            <!-- Animated Particles -->
            <div class="particles">
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
            </div>
            
            <!-- Hero Content -->
            <div class="hero-content">
                <h1 class="hero-title">Selamat Datang di SIMBARA</h1>
                <p class="hero-subtitle">
                    Sistem Peminjaman Barang dan Ruangan Poltekkes Denpasar - 
                    Solusi digital untuk mengelola peminjaman peralatan dan inventaris dengan mudah dan efisien.
                </p>
                <a href="{{ route('auth.login') }}" class="cta-button">
                    <i class="bi bi-box-seam me-2"></i>Mulai Peminjaman
                </a>
            </div>
            

            
            <!-- Footer -->
            <div class="footer">
                <p class="footer-text">© 2025 POLTEKKES DENPASAR - SIMBARA</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/horizontal-scroll.js') }}"></script>
    
    <script>
        // Debug script untuk memastikan logo loading
        document.addEventListener('DOMContentLoaded', function() {
            const logoImg = document.querySelector('.navbar-logo');
            if (logoImg) {
                logoImg.onload = function() {
                    console.log('Logo loaded successfully:', this.src);
                };
                logoImg.onerror = function() {
                    console.error('Logo failed to load:', this.src);
                    this.style.display = 'none';
                    const fallbackIcon = this.nextElementSibling;
                    if (fallbackIcon) {
                        fallbackIcon.style.display = 'inline-block';
                    }
                };
                
                // Force reload if already loaded
                if (logoImg.complete && logoImg.naturalHeight === 0) {
                    logoImg.onerror();
                }
            }
            
            // Video background handling
            const video = document.querySelector('.video-background');
            if (video) {
                // Force muted for autoplay policy
                video.muted = true;
                video.playsInline = true;

                const firstSource = video.querySelector('source');
                if (firstSource) {
                    console.log('Video src:', firstSource.getAttribute('src'));
                }

                video.addEventListener('loadeddata', function() {
                    console.log('Video background loaded successfully');
                    console.log('Video dimensions:', this.videoWidth, 'x', this.videoHeight);
                });
                
                video.addEventListener('loadstart', function() {
                    console.log('Video loading started');
                });
                
                function tryPlay() {
                    video.play().then(function() {
                        console.log('Video playing successfully');
                    }).catch(function(error) {
                        console.warn('Video autoplay failed, retrying...', error);
                        setTimeout(() => {
                            video.play().catch(err => console.error('Retry failed:', err));
                        }, 500);
                    });
                }

                video.addEventListener('canplay', tryPlay);
                // Try immediately as well
                tryPlay();
                
                video.addEventListener('error', function(e) {
                    console.error('Video background failed to load:', e);
                    console.error('Video error details:', this.error);
                    // Fallback to gradient background
                    this.style.display = 'none';
                });
            } else {
                console.error('Video element not found');
            }
        });
    </script>
</body>
</html>
