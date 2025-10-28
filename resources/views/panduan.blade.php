<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panduan Penggunaan - SIMBARA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets/css/custom-theme.css') }}" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #E0FFFF 0%, #B0E0E6 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        
        .guide-header {
            background: linear-gradient(135deg, #20B2AA 0%, #008B8B 100%);
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .guide-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .guide-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-bottom: 30px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .guide-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }
        
        .guide-card-header {
            background: linear-gradient(135deg, #20B2AA 0%, #48D1CC 100%);
            color: white;
            padding: 25px 30px;
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }
        
        .guide-card-header i {
            font-size: 2rem;
            margin-right: 15px;
        }
        
        .guide-card-body {
            padding: 30px;
        }
        
        .step-item {
            background: #f8f9fa;
            border-left: 4px solid #20B2AA;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .step-item:hover {
            background: #e8f5f5;
            border-left-width: 6px;
        }
        
        .step-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: #20B2AA;
            color: white;
            border-radius: 50%;
            font-weight: bold;
            font-size: 1.2rem;
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        .step-content {
            flex: 1;
        }
        
        .step-title {
            font-weight: 600;
            font-size: 1.1rem;
            color: #008B8B;
            margin-bottom: 8px;
        }
        
        .step-description {
            color: #666;
            line-height: 1.6;
            margin: 0;
        }
        
        .info-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .info-box i {
            color: #ffc107;
            font-size: 1.5rem;
            margin-right: 10px;
        }
        
        .tips-box {
            background: #d1ecf1;
            border-left: 4px solid #0dcaf0;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .tips-box i {
            color: #0dcaf0;
            font-size: 1.5rem;
            margin-right: 10px;
        }
        
        .btn-back {
            background: linear-gradient(135deg, #20B2AA 0%, #008B8B 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(32, 178, 170, 0.3);
        }
        
        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(32, 178, 170, 0.4);
            color: white;
        }
        
        .feature-badge {
            display: inline-block;
            background: #e8f5f5;
            color: #008B8B;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            margin: 5px 5px 5px 0;
        }
        
        .toc-list {
            list-style: none;
            padding: 0;
        }
        
        .toc-list li {
            padding: 10px 0;
        }
        
        .toc-list a {
            color: #20B2AA;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .toc-list a:hover {
            color: #008B8B;
            padding-left: 10px;
        }
        
        .screenshot-placeholder {
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            color: #6c757d;
            margin: 20px 0;
        }
        
        @keyframes pulse-red {
            0%, 100% {
                box-shadow: 0 4px 20px rgba(238, 90, 36, 0.4);
            }
            50% {
                box-shadow: 0 4px 30px rgba(238, 90, 36, 0.7), 0 0 0 8px rgba(238, 90, 36, 0.2);
            }
        }
        
        .floating-home-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            background: linear-gradient(135deg, #FF6B6B 0%, #EE5A24 100%);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(238, 90, 36, 0.4);
            text-decoration: none;
            font-size: 1.5rem;
            transition: all 0.3s ease, opacity 0.4s ease, visibility 0.4s ease;
            z-index: 1050;
            opacity: 1;
            visibility: visible;
            border: 3px solid white;
            animation: pulse-red 2s ease-in-out infinite;
        }
        
        .floating-home-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 25px rgba(238, 90, 36, 0.6);
            color: white;
            background: linear-gradient(135deg, #EE5A24 0%, #C23616 100%);
            animation: none;
        }
        
        .floating-home-btn i {
            transition: transform 0.3s ease;
        }
        
        .floating-home-btn:hover i {
            transform: scale(1.2);
        }
        
        @media (max-width: 768px) {
            .floating-home-btn {
                width: 50px;
                height: 50px;
                top: 15px;
                left: 15px;
                font-size: 1.2rem;
                border: 2px solid white;
            }
            .guide-header {
                padding: 40px 0;
            }
            
            .guide-card-header {
                font-size: 1.2rem;
                padding: 20px;
            }
            
            .guide-card-body {
                padding: 20px;
            }
            
            .step-item {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="guide-header">
        <div class="guide-container">
            <div class="text-center">
                <h1 class="mb-3">
                    <i class="bi bi-book-half me-3"></i>
                    Panduan Penggunaan SIMBARA
                </h1>
                <p class="lead mb-0">
                    Sistem Peminjaman Barang dan Ruangan Poltekkes Denpasar
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="guide-container">
        
        <!-- Table of Contents -->
        <div class="guide-card">
            <div class="guide-card-header">
                <i class="bi bi-list-ul"></i>
                Daftar Isi
            </div>
            <div class="guide-card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-primary mb-3">Panduan Peminjam</h5>
                        <ul class="toc-list">
                            <li><a href="#peminjam-login">1. Login Sistem</a></li>
                            <li><a href="#peminjam-browse">2. Melihat Barang & Ruangan</a></li>
                            <li><a href="#peminjam-cart">3. Menambahkan ke Keranjang</a></li>
                            <li><a href="#peminjam-form">4. Mengisi Form Peminjaman</a></li>
                            <li><a href="#peminjam-tracking">5. Tracking Status Peminjaman</a></li>
                            <li><a href="#peminjam-return">6. Pengembalian Barang</a></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-danger mb-3">Panduan Admin</h5>
                        <ul class="toc-list">
                            <li><a href="#admin-login">1. Login Admin</a></li>
                            <li><a href="#admin-dashboard">2. Dashboard Admin</a></li>
                            <li><a href="#admin-kelola-barang">3. Kelola Inventaris Barang</a></li>
                            <li><a href="#admin-kelola-ruangan">4. Kelola Inventaris Ruangan</a></li>
                            <li><a href="#admin-kelola-peminjaman">5. Kelola Peminjaman</a></li>
                            <li><a href="#admin-kelola-akun">6. Kelola Akun Pengguna</a></li>
                            <li><a href="#admin-arsip">7. Kelola Arsip & Download PDF</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- PANDUAN PEMINJAM -->
        <div class="guide-card" id="peminjam">
            <div class="guide-card-header">
                <i class="bi bi-person-check"></i>
                Panduan untuk Peminjam
            </div>
            <div class="guide-card-body">
                
                <!-- Login -->
                <div id="peminjam-login" class="mb-5">
                    <h3 class="text-primary mb-4">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        1. Login Sistem
                    </h3>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">1</span>
                        <div class="step-content">
                            <div class="step-title">Akses Halaman Beranda</div>
                            <p class="step-description">
                                Buka browser dan akses halaman beranda SIMBARA. Klik tombol <strong>"Mulai Peminjaman"</strong> atau menu <strong>"Login"</strong>.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">2</span>
                        <div class="step-content">
                            <div class="step-title">Masukkan Kredensial</div>
                            <p class="step-description">
                                Masukkan <strong>Nama Ormawa/Jurusan</strong> Anda (misalnya: "Farmasi", "HIMA Gizi") dan <strong>Password</strong> yang telah diberikan oleh admin.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">3</span>
                        <div class="step-content">
                            <div class="step-title">Klik Login</div>
                            <p class="step-description">
                                Setelah memasukkan kredensial yang benar, klik tombol <strong>"Login"</strong>. Anda akan diarahkan ke Dashboard Peminjam.
                            </p>
                        </div>
                    </div>
                    
                    <div class="info-box d-flex align-items-center">
                        <i class="bi bi-info-circle-fill"></i>
                        <div>
                            <strong>Catatan:</strong> Jika Anda lupa password, silakan hubungi admin untuk reset password.
                        </div>
                    </div>
                </div>
                
                <!-- Browse -->
                <div id="peminjam-browse" class="mb-5">
                    <h3 class="text-primary mb-4">
                        <i class="bi bi-search me-2"></i>
                        2. Melihat Barang & Ruangan Tersedia
                    </h3>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">1</span>
                        <div class="step-content">
                            <div class="step-title">Akses Dashboard</div>
                            <p class="step-description">
                                Setelah login, Anda akan melihat Dashboard dengan 2 tab utama: <span class="feature-badge"><i class="bi bi-box-seam me-1"></i>Barang</span> dan <span class="feature-badge"><i class="bi bi-building me-1"></i>Ruangan</span>.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">2</span>
                        <div class="step-content">
                            <div class="step-title">Lihat Daftar Barang</div>
                            <p class="step-description">
                                Pada tab <strong>"Barang"</strong>, Anda dapat melihat semua barang yang tersedia untuk dipinjam. Setiap kartu barang menampilkan:
                                <ul class="mt-2 mb-0">
                                    <li>📸 Foto barang (swipe untuk lihat foto lainnya)</li>
                                    <li>📦 Nama barang</li>
                                    <li>📊 Stok tersedia</li>
                                    <li>✅ Status (Tersedia/Maintenance/Dipinjam)</li>
                                    <li>🔢 Input jumlah yang ingin dipinjam</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">3</span>
                        <div class="step-content">
                            <div class="step-title">Lihat Daftar Ruangan</div>
                            <p class="step-description">
                                Pada tab <strong>"Ruangan"</strong>, Anda dapat melihat semua ruangan yang tersedia. Setiap kartu ruangan menampilkan:
                                <ul class="mt-2 mb-0">
                                    <li>📸 Foto ruangan (swipe untuk lihat foto lainnya)</li>
                                    <li>🏢 Nama ruangan</li>
                                    <li>📍 Lokasi</li>
                                    <li>✅ Status (Tersedia/Maintenance/Dipinjam)</li>
                                    <li>📝 Deskripsi</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">4</span>
                        <div class="step-content">
                            <div class="step-title">Gunakan Fitur Pencarian</div>
                            <p class="step-description">
                                Gunakan kolom pencarian untuk mencari barang/ruangan berdasarkan nama. Ketik keyword dan tekan Enter atau klik tombol cari.
                            </p>
                        </div>
                    </div>
                    
                    <div class="tips-box d-flex align-items-center">
                        <i class="bi bi-lightbulb-fill"></i>
                        <div>
                            <strong>Tips:</strong> Klik pada foto barang/ruangan untuk melihat galeri foto lengkap. Gunakan tombol navigasi atau swipe untuk berganti foto.
                        </div>
                    </div>
                </div>
                
                <!-- Cart -->
                <div id="peminjam-cart" class="mb-5">
                    <h3 class="text-primary mb-4">
                        <i class="bi bi-cart-plus me-2"></i>
                        3. Menambahkan ke Keranjang
                    </h3>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">1</span>
                        <div class="step-content">
                            <div class="step-title">Pilih Barang atau Ruangan</div>
                            <p class="step-description">
                                Cari barang atau ruangan yang ingin Anda pinjam. Pastikan statusnya <span class="badge bg-success">Tersedia</span>.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">2</span>
                        <div class="step-content">
                            <div class="step-title">Tentukan Jumlah (Khusus Barang)</div>
                            <p class="step-description">
                                Untuk barang, masukkan jumlah yang ingin dipinjam menggunakan tombol <strong>"-"</strong> dan <strong>"+"</strong>. Jumlah tidak boleh melebihi stok tersedia.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">3</span>
                        <div class="step-content">
                            <div class="step-title">Klik Tambah ke Keranjang</div>
                            <p class="step-description">
                                Klik tombol <button class="btn btn-sm btn-primary"><i class="bi bi-cart-plus"></i> Keranjang</button> untuk menambahkan item ke keranjang.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">4</span>
                        <div class="step-content">
                            <div class="step-title">Lihat Keranjang</div>
                            <p class="step-description">
                                Klik icon <i class="bi bi-cart3"></i> keranjang di pojok kanan atas untuk melihat semua item yang telah ditambahkan. Badge angka menunjukkan jumlah item.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">5</span>
                        <div class="step-content">
                            <div class="step-title">Kelola Keranjang</div>
                            <p class="step-description">
                                Di halaman keranjang, Anda dapat:
                                <ul class="mt-2 mb-0">
                                    <li>➕➖ Mengubah jumlah barang dengan tombol +/-</li>
                                    <li>🗑️ Menghapus item dari keranjang</li>
                                    <li>👁️ Melihat detail lengkap setiap item</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    
                    <div class="info-box d-flex align-items-center">
                        <i class="bi bi-info-circle-fill"></i>
                        <div>
                            <strong>Penting:</strong> Anda dapat meminjam barang dan ruangan sekaligus dalam satu pengajuan peminjaman.
                        </div>
                    </div>
                </div>
                
                <!-- Form -->
                <div id="peminjam-form" class="mb-5">
                    <h3 class="text-primary mb-4">
                        <i class="bi bi-file-earmark-text me-2"></i>
                        4. Mengisi Form Peminjaman
                    </h3>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">1</span>
                        <div class="step-content">
                            <div class="step-title">Klik Lanjut ke Form</div>
                            <p class="step-description">
                                Setelah memastikan semua item sudah benar di keranjang, klik tombol <button class="btn btn-sm btn-success"><i class="bi bi-file-earmark-text"></i> Lanjut ke Form Peminjaman</button>.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">2</span>
                        <div class="step-content">
                            <div class="step-title">Upload Foto Peminjam (Opsional)</div>
                            <p class="step-description">
                                Anda dapat mengupload foto peminjam jika diperlukan. Klik "Choose File" dan pilih foto (format: JPG, JPEG, PNG, maksimal 10MB). Preview foto akan muncul setelah dipilih.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">3</span>
                        <div class="step-content">
                            <div class="step-title">Isi Data Peminjam</div>
                            <p class="step-description">
                                Lengkapi semua field yang wajib diisi (ditandai <span class="text-danger">*</span>):
                                <ul class="mt-2 mb-0">
                                    <li>📝 <strong>Nama Lengkap</strong> - Minimal 3 karakter</li>
                                    <li>🆔 <strong>NIM/NIP</strong> - Masukkan nomor induk</li>
                                    <li>🏛️ <strong>Jurusan/Ormawa</strong> - Otomatis terisi sesuai akun yang login</li>
                                    <li>📞 <strong>No. Telepon</strong> - Nomor yang dapat dihubungi</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">4</span>
                        <div class="step-content">
                            <div class="step-title">Isi Detail Kegiatan</div>
                            <p class="step-description">
                                Masukkan detail kegiatan peminjaman:
                                <ul class="mt-2 mb-0">
                                    <li>🎯 <strong>Nama Kegiatan</strong> - Contoh: "Seminar Kesehatan", "Praktikum Kimia"</li>
                                    <li>📅 <strong>Tanggal Mulai</strong> - Tanggal mulai peminjaman</li>
                                    <li>📅 <strong>Tanggal Selesai</strong> - Tanggal pengembalian (tidak boleh lebih awal dari tanggal mulai)</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">5</span>
                        <div class="step-content">
                            <div class="step-title">Upload Bukti Kegiatan</div>
                            <p class="step-description">
                                <strong>Wajib!</strong> Upload bukti kegiatan seperti surat peminjaman, proposal, atau dokumen pendukung lainnya. Format: PDF, JPG, JPEG, PNG (maksimal 10MB).
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">6</span>
                        <div class="step-content">
                            <div class="step-title">Review Item Peminjaman</div>
                            <p class="step-description">
                                Di bagian bawah form, Anda dapat melihat ringkasan barang dan ruangan yang akan dipinjam. Pastikan semuanya sudah benar.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">7</span>
                        <div class="step-content">
                            <div class="step-title">Ajukan Peminjaman</div>
                            <p class="step-description">
                                Klik tombol <button class="btn btn-sm btn-success"><i class="bi bi-send"></i> Ajukan Peminjaman</button>. Jika berhasil, Anda akan melihat Kode Peminjaman yang dapat digunakan untuk tracking.
                            </p>
                        </div>
                    </div>
                    
                    <div class="info-box d-flex align-items-center">
                        <i class="bi bi-info-circle-fill"></i>
                        <div>
                            <strong>Catatan:</strong> Simpan Kode Peminjaman Anda dengan baik! Kode ini digunakan untuk tracking status dan pengambilan barang.
                        </div>
                    </div>
                </div>
                
                <!-- Tracking -->
                <div id="peminjam-tracking" class="mb-5">
                    <h3 class="text-primary mb-4">
                        <i class="bi bi-search me-2"></i>
                        5. Tracking Status Peminjaman
                    </h3>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">1</span>
                        <div class="step-content">
                            <div class="step-title">Akses Halaman Tracking</div>
                            <p class="step-description">
                                Dari beranda atau dashboard, klik menu <strong>"Cek Status"</strong> atau <strong>"Tracking"</strong>.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">2</span>
                        <div class="step-content">
                            <div class="step-title">Masukkan Kode atau Nama Kegiatan</div>
                            <p class="step-description">
                                Masukkan <strong>Kode Peminjaman</strong> atau <strong>Nama Kegiatan</strong> Anda di kolom pencarian, lalu klik <button class="btn btn-sm btn-primary">Cek Status</button>.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">3</span>
                        <div class="step-content">
                            <div class="step-title">Lihat Detail Status</div>
                            <p class="step-description">
                                Sistem akan menampilkan status peminjaman Anda:
                                <ul class="mt-2 mb-0">
                                    <li>🟡 <span class="badge bg-warning">Menunggu</span> - Menunggu persetujuan admin</li>
                                    <li>🟢 <span class="badge bg-success">Disetujui</span> - Peminjaman disetujui, silakan ambil barang</li>
                                    <li>🟠 <span class="badge bg-info">Sedang Dipinjam</span> - Barang sedang digunakan</li>
                                    <li>🔵 <span class="badge bg-primary">Dikembalikan</span> - Barang sudah dikembalikan</li>
                                    <li>🔴 <span class="badge bg-danger">Ditolak</span> - Peminjaman ditolak oleh admin</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    
                    <div class="tips-box d-flex align-items-center">
                        <i class="bi bi-lightbulb-fill"></i>
                        <div>
                            <strong>Tips:</strong> Anda akan mendapat notifikasi di dashboard jika ada perubahan status peminjaman Anda.
                        </div>
                    </div>
                </div>
                
                <!-- Return -->
                <div id="peminjam-return" class="mb-5">
                    <h3 class="text-primary mb-4">
                        <i class="bi bi-arrow-return-left me-2"></i>
                        6. Pengembalian Barang
                    </h3>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">1</span>
                        <div class="step-content">
                            <div class="step-title">Kembalikan Tepat Waktu</div>
                            <p class="step-description">
                                Pastikan Anda mengembalikan barang/ruangan sesuai tanggal yang telah ditentukan. Keterlambatan dapat berdampak pada peminjaman berikutnya.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">2</span>
                        <div class="step-content">
                            <div class="step-title">Hubungi Admin</div>
                            <p class="step-description">
                                Datang ke ruang admin dengan membawa barang yang dipinjam. Sebutkan <strong>Kode Peminjaman</strong> atau <strong>Nama Anda</strong>.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">3</span>
                        <div class="step-content">
                            <div class="step-title">Verifikasi Pengembalian</div>
                            <p class="step-description">
                                Admin akan memeriksa kondisi barang dan mengupdate status peminjaman menjadi <span class="badge bg-primary">Dikembalikan</span> di sistem.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">4</span>
                        <div class="step-content">
                            <div class="step-title">Konfirmasi di Sistem (Jika Ada)</div>
                            <p class="step-description">
                                Jika sistem memiliki fitur konfirmasi pengembalian, Anda dapat mengklik tombol <button class="btn btn-sm btn-primary"><i class="bi bi-arrow-return-left"></i> Ajukan Pengembalian</button> di dashboard.
                            </p>
                        </div>
                    </div>
                    
                    <div class="info-box d-flex align-items-center">
                        <i class="bi bi-info-circle-fill"></i>
                        <div>
                            <strong>Penting:</strong> Kembalikan barang dalam kondisi baik. Jika ada kerusakan, segera laporkan kepada admin.
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

        <!-- PANDUAN ADMIN -->
        <div class="guide-card" id="admin">
            <div class="guide-card-header">
                <i class="bi bi-shield-check"></i>
                Panduan untuk Admin
            </div>
            <div class="guide-card-body">
                
                <!-- Admin Login -->
                <div id="admin-login" class="mb-5">
                    <h3 class="text-danger mb-4">
                        <i class="bi bi-key me-2"></i>
                        1. Login Admin
                    </h3>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">1</span>
                        <div class="step-content">
                            <div class="step-title">Akses Halaman Login Admin</div>
                            <p class="step-description">
                                Dari halaman beranda, klik tombol <button class="btn btn-sm btn-outline-light">
                                    <i class="bi bi-person-circle me-1"></i>Login Admin
                                </button> di pojok kanan atas.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">2</span>
                        <div class="step-content">
                            <div class="step-title">Masukkan Kredensial Admin</div>
                            <p class="step-description">
                                Masukkan <strong>Email Admin</strong> dan <strong>Password</strong> yang telah diberikan.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">3</span>
                        <div class="step-content">
                            <div class="step-title">Akses Dashboard Admin</div>
                            <p class="step-description">
                                Setelah login berhasil, Anda akan diarahkan ke Dashboard Admin dengan menu navigasi lengkap.
                            </p>
                        </div>
                    </div>
                    
                    <div class="info-box d-flex align-items-center">
                        <i class="bi bi-info-circle-fill"></i>
                        <div>
                            <strong>Keamanan:</strong> Jangan membagikan kredensial admin kepada siapapun. Selalu logout setelah selesai menggunakan sistem.
                        </div>
                    </div>
                </div>
                
                <!-- Admin Dashboard -->
                <div id="admin-dashboard" class="mb-5">
                    <h3 class="text-danger mb-4">
                        <i class="bi bi-speedometer2 me-2"></i>
                        2. Dashboard Admin
                    </h3>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">1</span>
                        <div class="step-content">
                            <div class="step-title">Statistik Ringkasan</div>
                            <p class="step-description">
                                Dashboard menampilkan kartu statistik:
                                <ul class="mt-2 mb-0">
                                    <li>📦 Total Inventaris Barang</li>
                                    <li>🏢 Total Inventaris Ruangan</li>
                                    <li>📋 Total Peminjaman (Aktif/Selesai)</li>
                                    <li>👥 Total Akun Pengguna</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">2</span>
                        <div class="step-content">
                            <div class="step-title">Menu Navigasi</div>
                            <p class="step-description">
                                Sidebar kiri berisi menu navigasi:
                                <ul class="mt-2 mb-0">
                                    <li><i class="bi bi-speedometer2 text-primary"></i> Dashboard</li>
                                    <li><i class="bi bi-box-seam text-info"></i> Kelola Inventaris Barang</li>
                                    <li><i class="bi bi-building text-success"></i> Kelola Inventaris Ruangan</li>
                                    <li><i class="bi bi-clipboard-check text-warning"></i> Kelola Peminjaman</li>
                                    <li><i class="bi bi-people text-secondary"></i> Kelola Akun</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">3</span>
                        <div class="step-content">
                            <div class="step-title">Grafik & Laporan</div>
                            <p class="step-description">
                                Dashboard menampilkan visualisasi data seperti grafik peminjaman bulanan, item paling sering dipinjam, dan statistik lainnya.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Kelola Barang -->
                <div id="admin-kelola-barang" class="mb-5">
                    <h3 class="text-danger mb-4">
                        <i class="bi bi-box-seam me-2"></i>
                        3. Kelola Inventaris Barang
                    </h3>
                    
                    <h5 class="text-secondary mb-3">Melihat Daftar Barang</h5>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">1</span>
                        <div class="step-content">
                            <div class="step-title">Akses Menu Inventaris Barang</div>
                            <p class="step-description">
                                Klik menu <strong>"Kelola Inventaris Barang"</strong> di sidebar untuk melihat tabel semua barang.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">2</span>
                        <div class="step-content">
                            <div class="step-title">Fitur Pencarian & Filter</div>
                            <p class="step-description">
                                Gunakan kolom pencarian untuk mencari barang berdasarkan nama atau kode. Gunakan dropdown untuk filter berdasarkan status atau kategori.
                            </p>
                        </div>
                    </div>
                    
                    <h5 class="text-secondary mb-3 mt-4">Menambah Barang Baru</h5>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">1</span>
                        <div class="step-content">
                            <div class="step-title">Klik Tambah Barang</div>
                            <p class="step-description">
                                Klik tombol <button class="btn btn-sm btn-success"><i class="bi bi-plus-circle"></i> Tambah Barang</button> di pojok kanan atas.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">2</span>
                        <div class="step-content">
                            <div class="step-title">Isi Form Data Barang</div>
                            <p class="step-description">
                                Lengkapi semua field (yang wajib ditandai <span class="text-danger">*</span>):
                                <ul class="mt-2 mb-0">
                                    <li>📝 <strong>Nama Barang</strong> - Nama lengkap barang</li>
                                    <li>📦 <strong>Stok</strong> - Jumlah total barang</li>
                                    <li>📊 <strong>Kondisi</strong> - Baik/Rusak/Maintenance</li>
                                    <li>✅ <strong>Status</strong> - Tersedia/Tidak Tersedia</li>
                                    <li>📝 <strong>Deskripsi</strong> (opsional)</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">3</span>
                        <div class="step-content">
                            <div class="step-title">Upload Foto Barang</div>
                            <p class="step-description">
                                Upload hingga 3 foto barang (Foto 1, Foto 2, Foto 3). Format: JPG, PNG (max 2MB). Preview akan muncul setelah memilih file. Anda dapat menghapus foto dengan klik tombol <i class="bi bi-trash text-danger"></i>.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">4</span>
                        <div class="step-content">
                            <div class="step-title">Simpan Data</div>
                            <p class="step-description">
                                Klik tombol <button class="btn btn-sm btn-success"><i class="bi bi-save"></i> Simpan</button> untuk menyimpan data barang baru.
                            </p>
                        </div>
                    </div>
                    
                    <h5 class="text-secondary mb-3 mt-4">Mengedit Barang</h5>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">1</span>
                        <div class="step-content">
                            <div class="step-title">Klik Tombol Edit</div>
                            <p class="step-description">
                                Di tabel barang, klik tombol <button class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></button> pada baris barang yang ingin diedit.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">2</span>
                        <div class="step-content">
                            <div class="step-title">Ubah Data yang Diperlukan</div>
                            <p class="step-description">
                                Form edit akan tampil dengan data yang sudah terisi. Ubah data yang diperlukan, termasuk foto jika perlu.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">3</span>
                        <div class="step-content">
                            <div class="step-title">Simpan Perubahan</div>
                            <p class="step-description">
                                Klik <button class="btn btn-sm btn-success"><i class="bi bi-save"></i> Simpan Perubahan</button> untuk menyimpan.
                            </p>
                        </div>
                    </div>
                    
                    <h5 class="text-secondary mb-3 mt-4">Menghapus Barang</h5>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">1</span>
                        <div class="step-content">
                            <div class="step-title">Klik Tombol Hapus</div>
                            <p class="step-description">
                                Klik tombol <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button> pada baris barang yang ingin dihapus.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">2</span>
                        <div class="step-content">
                            <div class="step-title">Konfirmasi Penghapusan</div>
                            <p class="step-description">
                                Konfirmasi penghapusan dengan klik <strong>"Ya, Hapus"</strong> pada dialog konfirmasi. Data yang dihapus tidak dapat dikembalikan.
                            </p>
                        </div>
                    </div>
                    
                    <div class="info-box d-flex align-items-center">
                        <i class="bi bi-info-circle-fill"></i>
                        <div>
                            <strong>Penting:</strong> Pastikan stok barang selalu terupdate. Barang dengan stok 0 tidak dapat dipinjam.
                        </div>
                    </div>
                </div>
                
                <!-- Kelola Ruangan -->
                <div id="admin-kelola-ruangan" class="mb-5">
                    <h3 class="text-danger mb-4">
                        <i class="bi bi-building me-2"></i>
                        4. Kelola Inventaris Ruangan
                    </h3>
                    
                    <p class="text-muted mb-4">Proses pengelolaan ruangan sama dengan pengelolaan barang, dengan perbedaan field yang diisi.</p>
                    
                    <h5 class="text-secondary mb-3">Menambah Ruangan Baru</h5>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">1</span>
                        <div class="step-content">
                            <div class="step-title">Klik Tambah Ruangan</div>
                            <p class="step-description">
                                Klik tombol <button class="btn btn-sm btn-success"><i class="bi bi-plus-circle"></i> Tambah Ruangan</button>.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">2</span>
                        <div class="step-content">
                            <div class="step-title">Isi Form Data Ruangan</div>
                            <p class="step-description">
                                Field yang perlu diisi:
                                <ul class="mt-2 mb-0">
                                    <li>🏢 <strong>Nama Ruangan</strong> - Contoh: "Lab Komputer 1"</li>
                                    <li>📍 <strong>Lokasi</strong> - Contoh: "Gedung A Lantai 2"</li>
                                    <li>✅ <strong>Status</strong> - Tersedia/Maintenance/Dipinjam/Tidak Tersedia</li>
                                    <li>📝 <strong>Deskripsi</strong> - Fasilitas dan kapasitas ruangan</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">3</span>
                        <div class="step-content">
                            <div class="step-title">Upload Foto Ruangan</div>
                            <p class="step-description">
                                Upload hingga 3 foto ruangan dengan preview real-time. Gunakan foto yang jelas dan representatif.
                            </p>
                        </div>
                    </div>
                    
                    <div class="tips-box d-flex align-items-center">
                        <i class="bi bi-lightbulb-fill"></i>
                        <div>
                            <strong>Tips:</strong> Tambahkan deskripsi lengkap tentang fasilitas ruangan (proyektor, AC, kapasitas, dll) agar peminjam lebih mudah memilih.
                        </div>
                    </div>
                </div>
                
                <!-- Kelola Peminjaman -->
                <div id="admin-kelola-peminjaman" class="mb-5">
                    <h3 class="text-danger mb-4">
                        <i class="bi bi-clipboard-check me-2"></i>
                        5. Kelola Peminjaman
                    </h3>
                    
                    <h5 class="text-secondary mb-3">Melihat Daftar Peminjaman</h5>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">1</span>
                        <div class="step-content">
                            <div class="step-title">Akses Menu Peminjaman</div>
                            <p class="step-description">
                                Klik menu <strong>"Kelola Peminjaman"</strong> untuk melihat tabel semua peminjaman.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">2</span>
                        <div class="step-content">
                            <div class="step-title">Filter Berdasarkan Status</div>
                            <p class="step-description">
                                Gunakan tab atau dropdown untuk filter:
                                <ul class="mt-2 mb-0">
                                    <li>🟡 <span class="badge bg-warning">Menunggu Persetujuan</span></li>
                                    <li>🟢 <span class="badge bg-success">Disetujui</span></li>
                                    <li>🟠 <span class="badge bg-info">Sedang Dipinjam</span></li>
                                    <li>🔵 <span class="badge bg-primary">Dikembalikan</span></li>
                                    <li>🔴 <span class="badge bg-danger">Ditolak</span></li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    
                    <h5 class="text-secondary mb-3 mt-4">Menyetujui Peminjaman</h5>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">1</span>
                        <div class="step-content">
                            <div class="step-title">Klik Detail Peminjaman</div>
                            <p class="step-description">
                                Klik tombol <button class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Detail</button> pada peminjaman dengan status <span class="badge bg-warning">Menunggu</span>.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">2</span>
                        <div class="step-content">
                            <div class="step-title">Review Informasi Peminjaman</div>
                            <p class="step-description">
                                Periksa detail peminjaman:
                                <ul class="mt-2 mb-0">
                                    <li>📝 Data peminjam (Nama, NIM/NIP, Kontak)</li>
                                    <li>🎯 Detail kegiatan</li>
                                    <li>📅 Tanggal mulai dan selesai</li>
                                    <li>📦 Daftar barang/ruangan yang dipinjam</li>
                                    <li>📄 Bukti kegiatan yang diupload</li>
                                    <li>📸 Foto peminjam (jika ada)</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">3</span>
                        <div class="step-content">
                            <div class="step-title">Cek Ketersediaan</div>
                            <p class="step-description">
                                Pastikan semua barang/ruangan yang diminta tersedia pada tanggal yang diminta. Sistem biasanya sudah melakukan validasi otomatis.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">4</span>
                        <div class="step-content">
                            <div class="step-title">Setujui atau Tolak</div>
                            <p class="step-description">
                                Klik tombol:
                                <ul class="mt-2 mb-0">
                                    <li><button class="btn btn-sm btn-success"><i class="bi bi-check-circle"></i> Setujui</button> jika peminjaman layak disetujui</li>
                                    <li><button class="btn btn-sm btn-danger"><i class="bi bi-x-circle"></i> Tolak</button> jika tidak memenuhi syarat (berikan alasan penolakan)</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    
                    <h5 class="text-secondary mb-3 mt-4">Proses Pengembalian</h5>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">1</span>
                        <div class="step-content">
                            <div class="step-title">Terima Barang dari Peminjam</div>
                            <p class="step-description">
                                Ketika peminjam datang mengembalikan barang, cari peminjaman mereka berdasarkan Kode Peminjaman atau Nama.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">2</span>
                        <div class="step-content">
                            <div class="step-title">Periksa Kondisi Barang</div>
                            <p class="step-description">
                                Periksa kondisi fisik barang. Pastikan jumlah dan kondisi sesuai saat dipinjam.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">3</span>
                        <div class="step-content">
                            <div class="step-title">Update Status ke Dikembalikan</div>
                            <p class="step-description">
                                Di halaman detail peminjaman, klik tombol <button class="btn btn-sm btn-primary"><i class="bi bi-arrow-return-left"></i> Konfirmasi Pengembalian</button>. Stok barang akan otomatis dikembalikan.
                            </p>
                        </div>
                    </div>
                    
                    <div class="info-box d-flex align-items-center">
                        <i class="bi bi-info-circle-fill"></i>
                        <div>
                            <strong>Catatan:</strong> Jika ada barang yang rusak atau hilang, catat dalam sistem dan hubungi peminjam untuk penyelesaian.
                        </div>
                    </div>
                </div>
                
                <!-- Kelola Akun -->
                <div id="admin-kelola-akun" class="mb-5">
                    <h3 class="text-danger mb-4">
                        <i class="bi bi-people me-2"></i>
                        6. Kelola Akun Pengguna
                    </h3>
                    
                    <h5 class="text-secondary mb-3">Melihat Daftar Akun</h5>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">1</span>
                        <div class="step-content">
                            <div class="step-title">Akses Menu Kelola Akun</div>
                            <p class="step-description">
                                Klik menu <strong>"Kelola Akun"</strong> untuk melihat tabel semua akun ormawa/jurusan.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">2</span>
                        <div class="step-content">
                            <div class="step-title">Lihat Detail Akun</div>
                            <p class="step-description">
                                Tabel menampilkan informasi:
                                <ul class="mt-2 mb-0">
                                    <li>👤 Nama Ormawa/Jurusan</li>
                                    <li>🏷️ Tipe (Ormawa/Jurusan/Dosen/Staff)</li>
                                    <li>📧 Email</li>
                                    <li>📞 No. Telepon</li>
                                    <li>🔑 Password (plain text, dapat ditampilkan/disembunyikan)</li>
                                    <li>✅ Status (Aktif/Tidak Aktif)</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    
                    <h5 class="text-secondary mb-3 mt-4">Menambah Akun Baru</h5>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">1</span>
                        <div class="step-content">
                            <div class="step-title">Klik Tambah Akun</div>
                            <p class="step-description">
                                Klik tombol <button class="btn btn-sm btn-success"><i class="bi bi-plus-circle"></i> Tambah Akun</button>.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">2</span>
                        <div class="step-content">
                            <div class="step-title">Isi Form Akun Baru</div>
                            <p class="step-description">
                                Lengkapi field:
                                <ul class="mt-2 mb-0">
                                    <li>👤 <strong>Nama</strong> - Nama ormawa/jurusan</li>
                                    <li>🏷️ <strong>Tipe</strong> - Ormawa/Jurusan/Dosen/Staff</li>
                                    <li>🔑 <strong>Password</strong> - Password plain text (akan disimpan as-is)</li>
                                    <li>📧 <strong>Email</strong></li>
                                    <li>📞 <strong>No. Telepon</strong></li>
                                    <li>📍 <strong>Alamat</strong></li>
                                    <li>✅ <strong>Status</strong> - Aktif/Tidak Aktif</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">3</span>
                        <div class="step-content">
                            <div class="step-title">Simpan Akun</div>
                            <p class="step-description">
                                Klik <button class="btn btn-sm btn-success"><i class="bi bi-save"></i> Simpan</button>. Informasikan kredensial login kepada pengguna baru.
                            </p>
                        </div>
                    </div>
                    
                    <h5 class="text-secondary mb-3 mt-4">Mengedit Akun</h5>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">1</span>
                        <div class="step-content">
                            <div class="step-title">Edit Data Akun</div>
                            <p class="step-description">
                                Klik tombol <button class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></button> untuk mengubah data akun, termasuk reset password.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">2</span>
                        <div class="step-content">
                            <div class="step-title">Aktifkan/Nonaktifkan Akun</div>
                            <p class="step-description">
                                Ubah status akun menjadi <strong>"Tidak Aktif"</strong> untuk menonaktifkan akses login tanpa menghapus data.
                            </p>
                        </div>
                    </div>
                    
                    <h5 class="text-secondary mb-3 mt-4">Melihat Password</h5>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">1</span>
                        <div class="step-content">
                            <div class="step-title">Toggle Password</div>
                            <p class="step-description">
                                Password ditampilkan sebagai plain text yang tersembunyi. Klik tombol <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></button> untuk menampilkan/menyembunyikan password.
                            </p>
                        </div>
                    </div>
                    
                    <div class="info-box d-flex align-items-center">
                        <i class="bi bi-info-circle-fill"></i>
                        <div>
                            <strong>Keamanan:</strong> Password disimpan sebagai plain text di sistem ini untuk memudahkan admin dalam membantu pengguna yang lupa password. Pastikan akses admin dijaga dengan ketat.
                        </div>
                    </div>
                </div>
                
                <!-- Kelola Arsip -->
                <div id="admin-arsip" class="mb-5">
                    <h3 class="text-danger mb-4">
                        <i class="bi bi-archive me-2"></i>
                        7. Kelola Arsip & Download PDF
                    </h3>
                    
                    <p class="text-muted mb-4">
                        Fitur Arsip menyimpan semua riwayat peminjaman yang telah selesai. Anda dapat melihat, mencari, dan mengunduh laporan dalam format PDF untuk keperluan dokumentasi dan pelaporan.
                    </p>
                    
                    <h5 class="text-secondary mb-3">Mengakses Halaman Arsip</h5>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">1</span>
                        <div class="step-content">
                            <div class="step-title">Akses Menu Arsip</div>
                            <p class="step-description">
                                Di sidebar admin, klik menu <strong><i class="bi bi-archive"></i> Arsip</strong> untuk melihat semua peminjaman yang sudah selesai (status: <span class="badge bg-primary">Dikembalikan</span>).
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">2</span>
                        <div class="step-content">
                            <div class="step-title">Tampilan Halaman Arsip</div>
                            <p class="step-description">
                                Halaman arsip menampilkan tabel lengkap dengan kolom:
                                <ul class="mt-2 mb-0">
                                    <li>📋 <strong>Kode Peminjaman</strong> - ID unik setiap peminjaman</li>
                                    <li>👤 <strong>Nama Peminjam</strong> - Nama lengkap peminjam</li>
                                    <li>🆔 <strong>NIM/NIP</strong> - Nomor identitas</li>
                                    <li>🎯 <strong>Nama Kegiatan</strong> - Tujuan peminjaman</li>
                                    <li>📅 <strong>Tanggal Pinjam</strong> - Tanggal mulai peminjaman</li>
                                    <li>📅 <strong>Tanggal Kembali</strong> - Tanggal selesai/pengembalian</li>
                                    <li>⚙️ <strong>Aksi</strong> - Tombol detail dan download PDF</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    
                    <h5 class="text-secondary mb-3 mt-4">Mencari Data Arsip</h5>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">1</span>
                        <div class="step-content">
                            <div class="step-title">Gunakan Kolom Pencarian</div>
                            <p class="step-description">
                                Di bagian atas tabel, terdapat kolom pencarian. Anda dapat mencari berdasarkan:
                                <ul class="mt-2 mb-0">
                                    <li>🔍 Kode Peminjaman (contoh: "SUJ-20251101-0001")</li>
                                    <li>🔍 Nama Peminjam</li>
                                    <li>🔍 Nama Kegiatan</li>
                                    <li>🔍 NIM/NIP</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">2</span>
                        <div class="step-content">
                            <div class="step-title">Filter Berdasarkan Tanggal</div>
                            <p class="step-description">
                                Gunakan filter tanggal untuk mencari arsip dalam rentang waktu tertentu:
                                <ul class="mt-2 mb-0">
                                    <li>📅 <strong>Dari Tanggal</strong> - Pilih tanggal awal</li>
                                    <li>📅 <strong>Sampai Tanggal</strong> - Pilih tanggal akhir</li>
                                    <li>🔎 Klik tombol <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i> Filter</button></li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">3</span>
                        <div class="step-content">
                            <div class="step-title">Reset Filter</div>
                            <p class="step-description">
                                Untuk menampilkan semua data arsip kembali, klik tombol <button class="btn btn-sm btn-secondary"><i class="bi bi-x-circle"></i> Reset</button> atau hapus kata kunci pencarian.
                            </p>
                        </div>
                    </div>
                    
                    <h5 class="text-secondary mb-3 mt-4">Melihat Detail Arsip</h5>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">1</span>
                        <div class="step-content">
                            <div class="step-title">Klik Tombol Detail</div>
                            <p class="step-description">
                                Pada kolom Aksi di setiap baris, klik tombol <button class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Detail</button> untuk melihat informasi lengkap peminjaman.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">2</span>
                        <div class="step-content">
                            <div class="step-title">Informasi yang Ditampilkan</div>
                            <p class="step-description">
                                Halaman detail menampilkan informasi lengkap:
                                <ul class="mt-2 mb-0">
                                    <li><strong>📋 Informasi Peminjaman:</strong>
                                        <ul>
                                            <li>Kode Peminjaman</li>
                                            <li>Status</li>
                                            <li>Tanggal Pengajuan</li>
                                            <li>Tanggal Mulai & Selesai</li>
                                            <li>Durasi Peminjaman</li>
                                        </ul>
                                    </li>
                                    <li><strong>👤 Informasi Peminjam:</strong>
                                        <ul>
                                            <li>Nama Lengkap</li>
                                            <li>NIM/NIP</li>
                                            <li>Jurusan/Ormawa</li>
                                            <li>No. Telepon</li>
                                            <li>Foto Peminjam (jika ada)</li>
                                        </ul>
                                    </li>
                                    <li><strong>🎯 Detail Kegiatan:</strong>
                                        <ul>
                                            <li>Nama Kegiatan</li>
                                            <li>Bukti Kegiatan (dapat didownload)</li>
                                        </ul>
                                    </li>
                                    <li><strong>📦 Daftar Barang yang Dipinjam:</strong>
                                        <ul>
                                            <li>Foto barang (carousel jika ada multiple foto)</li>
                                            <li>Nama barang</li>
                                            <li>Jumlah yang dipinjam</li>
                                            <li>Kondisi saat dipinjam</li>
                                            <li>Status pengembalian</li>
                                        </ul>
                                    </li>
                                    <li><strong>🏢 Daftar Ruangan yang Dipinjam:</strong>
                                        <ul>
                                            <li>Foto ruangan (carousel)</li>
                                            <li>Nama ruangan</li>
                                            <li>Lokasi</li>
                                            <li>Deskripsi</li>
                                            <li>Status pengembalian</li>
                                        </ul>
                                    </li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">3</span>
                        <div class="step-content">
                            <div class="step-title">Lihat Foto dengan Carousel</div>
                            <p class="step-description">
                                Jika barang/ruangan memiliki lebih dari 1 foto, akan ditampilkan carousel. Gunakan:
                                <ul class="mt-2 mb-0">
                                    <li>⬅️ <strong>Tombol Prev</strong> - Foto sebelumnya</li>
                                    <li>➡️ <strong>Tombol Next</strong> - Foto berikutnya</li>
                                    <li>⚪ <strong>Indicator Dots</strong> - Klik untuk langsung ke foto tertentu</li>
                                    <li>📱 <strong>Swipe</strong> - Geser pada perangkat touch screen</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    
                    <h5 class="text-secondary mb-3 mt-4">Download PDF - Detail Peminjaman</h5>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">1</span>
                        <div class="step-content">
                            <div class="step-title">Download dari Tabel Arsip</div>
                            <p class="step-description">
                                Di halaman arsip (tabel), klik tombol <button class="btn btn-sm btn-danger"><i class="bi bi-file-pdf"></i> PDF</button> pada baris peminjaman yang ingin didownload.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">2</span>
                        <div class="step-content">
                            <div class="step-title">Download dari Halaman Detail</div>
                            <p class="step-description">
                                Atau, buka halaman detail peminjaman, lalu klik tombol <button class="btn btn-danger"><i class="bi bi-file-pdf"></i> Download PDF</button> di bagian atas halaman.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">3</span>
                        <div class="step-content">
                            <div class="step-title">Isi PDF yang Didownload</div>
                            <p class="step-description">
                                File PDF berisi laporan lengkap peminjaman dengan format profesional:
                                <ul class="mt-2 mb-0">
                                    <li>📄 <strong>Header</strong> - Logo & judul "Laporan Peminjaman SIMBARA"</li>
                                    <li>🔢 <strong>Kode Peminjaman</strong> - Sebagai referensi unik</li>
                                    <li>📋 <strong>Informasi Peminjaman</strong> - Status, tanggal, durasi</li>
                                    <li>👤 <strong>Data Peminjam</strong> - Lengkap dengan foto (jika ada)</li>
                                    <li>🎯 <strong>Detail Kegiatan</strong> - Nama & tujuan</li>
                                    <li>📦 <strong>Tabel Barang</strong> - Daftar lengkap dengan foto, nama, jumlah, kondisi</li>
                                    <li>🏢 <strong>Tabel Ruangan</strong> - Daftar lengkap dengan foto, nama, lokasi</li>
                                    <li>📅 <strong>Timeline</strong> - Tanggal pengajuan, persetujuan, pengembalian</li>
                                    <li>🖊️ <strong>Catatan</strong> - Jika ada catatan khusus</li>
                                    <li>📄 <strong>Footer</strong> - Tanggal cetak & watermark SIMBARA</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">4</span>
                        <div class="step-content">
                            <div class="step-title">Nama File PDF</div>
                            <p class="step-description">
                                File akan otomatis tersimpan dengan nama format: 
                                <code>Laporan_Peminjaman_[KODE]_[TANGGAL].pdf</code>
                                <br>Contoh: <code>Laporan_Peminjaman_SUJ-20251101-0001_2025-11-01.pdf</code>
                            </p>
                        </div>
                    </div>
                    
                    <h5 class="text-secondary mb-3 mt-4">Download PDF - Laporan Keseluruhan</h5>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">1</span>
                        <div class="step-content">
                            <div class="step-title">Export Semua Data Arsip</div>
                            <p class="step-description">
                                Di bagian atas halaman arsip, klik tombol <button class="btn btn-success"><i class="bi bi-file-pdf"></i> Export ke PDF</button> untuk mendownload laporan keseluruhan.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">2</span>
                        <div class="step-content">
                            <div class="step-title">Filter Sebelum Export</div>
                            <p class="step-description">
                                Anda dapat memfilter data terlebih dahulu (berdasarkan tanggal atau pencarian) sebelum melakukan export. PDF yang dihasilkan akan sesuai dengan filter yang diterapkan.
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">3</span>
                        <div class="step-content">
                            <div class="step-title">Isi Laporan Keseluruhan</div>
                            <p class="step-description">
                                PDF laporan keseluruhan berisi:
                                <ul class="mt-2 mb-0">
                                    <li>📊 <strong>Cover Page</strong> - Judul laporan & periode</li>
                                    <li>📈 <strong>Ringkasan Statistik</strong>:
                                        <ul>
                                            <li>Total peminjaman dalam periode</li>
                                            <li>Total barang yang dipinjam</li>
                                            <li>Total ruangan yang dipinjam</li>
                                            <li>Peminjam terbanyak</li>
                                            <li>Barang paling sering dipinjam</li>
                                        </ul>
                                    </li>
                                    <li>📋 <strong>Tabel Rekap</strong> - Daftar semua peminjaman dengan:
                                        <ul>
                                            <li>No urut</li>
                                            <li>Kode Peminjaman</li>
                                            <li>Nama Peminjam</li>
                                            <li>Jurusan/Ormawa</li>
                                            <li>Tanggal Pinjam</li>
                                            <li>Tanggal Kembali</li>
                                            <li>Jumlah Item</li>
                                        </ul>
                                    </li>
                                    <li>📊 <strong>Grafik & Visualisasi</strong> (jika tersedia):
                                        <ul>
                                            <li>Grafik peminjaman per bulan</li>
                                            <li>Distribusi per jurusan/ormawa</li>
                                            <li>Top 10 barang paling sering dipinjam</li>
                                        </ul>
                                    </li>
                                    <li>📄 <strong>Lampiran</strong> - Detail setiap peminjaman (opsional)</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    
                    <h5 class="text-secondary mb-3 mt-4">Tips Pengelolaan Arsip</h5>
                    
                    <div class="tips-box d-flex align-items-center">
                        <i class="bi bi-lightbulb-fill"></i>
                        <div>
                            <strong>Tips Pencarian Cepat:</strong>
                            <ul class="mb-0 mt-2">
                                <li>🔍 Gunakan Kode Peminjaman untuk pencarian paling akurat</li>
                                <li>📅 Filter berdasarkan bulan untuk laporan bulanan</li>
                                <li>📊 Export PDF secara berkala untuk backup data</li>
                                <li>📁 Simpan PDF dengan struktur folder: <code>Arsip/[Tahun]/[Bulan]/</code></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="info-box d-flex align-items-center">
                        <i class="bi bi-info-circle-fill"></i>
                        <div>
                            <strong>Catatan Penting:</strong>
                            <ul class="mb-0 mt-2">
                                <li>📄 PDF menggunakan format A4 portrait yang siap untuk dicetak</li>
                                <li>🖨️ Pastikan foto terlihat jelas sebelum mencetak PDF</li>
                                <li>💾 Data arsip tidak dapat dihapus, hanya dapat dilihat dan didownload</li>
                                <li>🔒 Arsip hanya dapat diakses oleh admin yang login</li>
                                <li>📊 Gunakan laporan PDF untuk presentasi ke pimpinan atau audit</li>
                            </ul>
                        </div>
                    </div>
                    
                    <h5 class="text-secondary mb-3 mt-4">Troubleshooting Download PDF</h5>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">1</span>
                        <div class="step-content">
                            <div class="step-title">PDF Tidak Muncul/Blank</div>
                            <p class="step-description">
                                <strong>Solusi:</strong>
                                <ul class="mt-2 mb-0">
                                    <li>Refresh halaman dan coba lagi</li>
                                    <li>Cek popup blocker browser (izinkan popup dari situs ini)</li>
                                    <li>Coba gunakan browser lain (Chrome, Firefox, Edge)</li>
                                    <li>Pastikan koneksi internet stabil</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">2</span>
                        <div class="step-content">
                            <div class="step-title">Foto Tidak Muncul di PDF</div>
                            <p class="step-description">
                                <strong>Solusi:</strong>
                                <ul class="mt-2 mb-0">
                                    <li>Pastikan foto telah terupload dengan benar di sistem</li>
                                    <li>Cek folder <code>storage/app/public/</code> apakah foto ada</li>
                                    <li>Jalankan command: <code>php artisan storage:link</code></li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    
                    <div class="step-item d-flex">
                        <span class="step-number">3</span>
                        <div class="step-content">
                            <div class="step-title">Download Lambat/Error</div>
                            <p class="step-description">
                                <strong>Solusi:</strong>
                                <ul class="mt-2 mb-0">
                                    <li>Tunggu beberapa saat, PDF dengan banyak foto membutuhkan waktu generate</li>
                                    <li>Jangan klik tombol download berulang kali</li>
                                    <li>Filter data untuk mengurangi jumlah data yang di-export</li>
                                    <li>Hubungi admin sistem jika masalah berlanjut</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                    
                    <div class="info-box d-flex align-items-center">
                        <i class="bi bi-info-circle-fill"></i>
                        <div>
                            <strong>Kegunaan Arsip & PDF:</strong>
                            <ul class="mb-0 mt-2">
                                <li>📊 <strong>Pelaporan</strong> - Untuk laporan bulanan/tahunan ke pimpinan</li>
                                <li>🔍 <strong>Audit</strong> - Dokumentasi lengkap untuk audit internal/eksternal</li>
                                <li>📈 <strong>Analisis</strong> - Data historis untuk analisis tren peminjaman</li>
                                <li>📄 <strong>Bukti</strong> - Bukti tertulis jika terjadi perselisihan</li>
                                <li>💾 <strong>Backup</strong> - Backup data dalam bentuk hard copy</li>
                                <li>📋 <strong>Arsip Fisik</strong> - Dapat dicetak dan disimpan sebagai arsip fisik</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

        <!-- Back to Home Button -->
        <div class="text-center mt-5 mb-5">
            <a href="{{ route('beranda') }}" class="btn btn-back">
                <i class="bi bi-house-door me-2"></i>Kembali ke Beranda
            </a>
        </div>

    </div>

    <!-- Floating Home Button -->
    <a href="{{ route('beranda') }}" class="floating-home-btn" title="Kembali ke Beranda">
        <i class="bi bi-house-door-fill"></i>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Smooth Scroll Script -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Floating button - always visible in top-left corner
        const floatingBtn = document.querySelector('.floating-home-btn');
        
        // Always visible, no auto-hide since it's in top-left
        floatingBtn.style.opacity = '1';
        floatingBtn.style.visibility = 'visible';
        
        // Optional: Add pulse animation on page load
        setTimeout(() => {
            floatingBtn.style.animation = 'none';
        }, 3000);
    </script>
</body>
</html>

