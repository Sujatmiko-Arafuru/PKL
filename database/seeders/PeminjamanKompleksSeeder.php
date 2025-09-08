<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\DetailPeminjamanRuangan;
use App\Models\Barang;
use App\Models\Ruangan;
use App\Models\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PeminjamanKompleksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        $this->command->info('Clearing existing peminjaman data...');
        
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        DetailPeminjamanRuangan::truncate();
        DetailPeminjaman::truncate();
        Notification::where('type', 'peminjaman_baru')->delete();
        Peminjaman::truncate();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get existing barang and ruangan
        $barangs = Barang::all();
        $ruangans = Ruangan::all();
        
        if ($barangs->isEmpty() || $ruangans->isEmpty()) {
            $this->command->error('Barang atau Ruangan tidak ditemukan. Jalankan seeder barang dan ruangan terlebih dahulu.');
            return;
        }

        // Create comprehensive peminjaman data
        $peminjamanData = [
            // ===== PEMINJAMAN MENUNGGU PERSETUJUAN =====
            
            // 1. Peminjaman Baru - Barang Saja
            [
                'nama' => 'Ahmad Fadillah',
                'nim_nip' => '2021001',
                'foto_peminjam' => 'foto_peminjam_2021001.jpg',
                'unit' => 'Fakultas Teknik',
                'no_telp' => '081234567890',
                'nama_kegiatan' => 'Workshop Robotik dan IoT',
                'tanggal_mulai' => '2025-01-25',
                'tanggal_selesai' => '2025-01-26',
                'bukti' => 'bukti_workshop_robotik.pdf',
                'status' => 'menunggu',
                'kode_peminjaman' => 'AHM-20250125-0001',
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
                'type' => 'barang_only'
            ],
            
            // 2. Peminjaman Baru - Ruangan Saja
            [
                'nama' => 'Sarah Putri',
                'nim_nip' => '2021002',
                'foto_peminjam' => 'foto_peminjam_2021002.jpg',
                'unit' => 'Fakultas Ekonomi',
                'no_telp' => '081234567891',
                'nama_kegiatan' => 'Seminar Kewirausahaan Digital',
                'tanggal_mulai' => '2025-01-27',
                'tanggal_selesai' => '2025-01-27',
                'bukti' => 'bukti_seminar_kewirausahaan.pdf',
                'status' => 'menunggu',
                'kode_peminjaman' => 'SAR-20250127-0002',
                'created_at' => now()->subHours(4),
                'updated_at' => now()->subHours(4),
                'type' => 'ruangan_only'
            ],
            
            // 3. Peminjaman Baru - Barang + Ruangan
            [
                'nama' => 'Budi Santoso',
                'nim_nip' => '2021003',
                'foto_peminjam' => 'foto_peminjam_2021003.jpg',
                'unit' => 'Fakultas Hukum',
                'no_telp' => '081234567892',
                'nama_kegiatan' => 'Konferensi Hukum Internasional',
                'tanggal_mulai' => '2025-01-30',
                'tanggal_selesai' => '2025-02-01',
                'bukti' => 'bukti_konferensi_hukum.pdf',
                'status' => 'menunggu',
                'kode_peminjaman' => 'BUD-20250130-0003',
                'created_at' => now()->subHours(6),
                'updated_at' => now()->subHours(6),
                'type' => 'barang_ruangan'
            ],

            // ===== PEMINJAMAN DISETUJUI =====
            
            // 4. Peminjaman Disetujui - Barang Saja
            [
                'nama' => 'Dewi Sartika',
                'nim_nip' => '2021004',
                'foto_peminjam' => 'foto_peminjam_2021004.jpg',
                'unit' => 'Fakultas Kedokteran',
                'no_telp' => '081234567893',
                'nama_kegiatan' => 'Pelatihan Kesehatan Masyarakat',
                'tanggal_mulai' => '2025-01-20',
                'tanggal_selesai' => '2025-01-22',
                'bukti' => 'bukti_pelatihan_kesehatan.pdf',
                'status' => 'disetujui',
                'kode_peminjaman' => 'DEW-20250120-0004',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(2),
                'type' => 'barang_only'
            ],
            
            // 5. Peminjaman Disetujui - Ruangan Saja
            [
                'nama' => 'Rizki Pratama',
                'nim_nip' => '2021005',
                'foto_peminjam' => 'foto_peminjam_2021005.jpg',
                'unit' => 'Fakultas Ilmu Komputer',
                'no_telp' => '081234567894',
                'nama_kegiatan' => 'Hackathon Programming Competition',
                'tanggal_mulai' => '2025-01-18',
                'tanggal_selesai' => '2025-01-19',
                'bukti' => 'bukti_hackathon.pdf',
                'status' => 'disetujui',
                'kode_peminjaman' => 'RIZ-20250118-0005',
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(3),
                'type' => 'ruangan_only'
            ],
            
            // 6. Peminjaman Disetujui - Barang + Ruangan
            [
                'nama' => 'Maya Indah',
                'nim_nip' => '2021006',
                'foto_peminjam' => 'foto_peminjam_2021006.jpg',
                'unit' => 'Fakultas Seni dan Desain',
                'no_telp' => '081234567895',
                'nama_kegiatan' => 'Pameran Seni Rupa Kontemporer',
                'tanggal_mulai' => '2025-01-15',
                'tanggal_selesai' => '2025-01-17',
                'bukti' => 'bukti_pameran_seni.pdf',
                'status' => 'disetujui',
                'kode_peminjaman' => 'MAY-20250115-0006',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(4),
                'type' => 'barang_ruangan'
            ],

            // ===== PEMINJAMAN DITOLAK =====
            
            // 7. Peminjaman Ditolak - Barang Saja
            [
                'nama' => 'Agus Setiawan',
                'nim_nip' => '2021007',
                'foto_peminjam' => 'foto_peminjam_2021007.jpg',
                'unit' => 'Fakultas Pertanian',
                'no_telp' => '081234567896',
                'nama_kegiatan' => 'Seminar Pertanian Modern',
                'tanggal_mulai' => '2025-01-12',
                'tanggal_selesai' => '2025-01-13',
                'bukti' => 'bukti_seminar_pertanian.pdf',
                'status' => 'ditolak',
                'kode_peminjaman' => 'AGU-20250112-0007',
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(7),
                'type' => 'barang_only'
            ],
            
            // 8. Peminjaman Ditolak - Ruangan Saja
            [
                'nama' => 'Linda Sari',
                'nim_nip' => '2021008',
                'foto_peminjam' => 'foto_peminjam_2021008.jpg',
                'unit' => 'Fakultas Psikologi',
                'no_telp' => '081234567897',
                'nama_kegiatan' => 'Workshop Psikologi Klinis',
                'tanggal_mulai' => '2025-01-10',
                'tanggal_selesai' => '2025-01-11',
                'bukti' => 'bukti_workshop_psikologi.pdf',
                'status' => 'ditolak',
                'kode_peminjaman' => 'LIN-20250110-0008',
                'created_at' => now()->subDays(9),
                'updated_at' => now()->subDays(8),
                'type' => 'ruangan_only'
            ],

            // ===== PEMINJAMAN SEDANG BERLANGSUNG =====
            
            // 9. Peminjaman Sedang Berlangsung - Barang Saja
            [
                'nama' => 'Eko Prasetyo',
                'nim_nip' => '2021009',
                'foto_peminjam' => 'foto_peminjam_2021009.jpg',
                'unit' => 'Fakultas Teknik Sipil',
                'no_telp' => '081234567898',
                'nama_kegiatan' => 'Pelatihan AutoCAD dan SketchUp',
                'tanggal_mulai' => '2025-01-15',
                'tanggal_selesai' => '2025-01-20',
                'bukti' => 'bukti_pelatihan_autocad.pdf',
                'status' => 'dipinjam',
                'kode_peminjaman' => 'EKO-20250115-0009',
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(8),
                'type' => 'barang_only'
            ],
            
            // 10. Peminjaman Sedang Berlangsung - Ruangan Saja
            [
                'nama' => 'Siti Nurhaliza',
                'nim_nip' => '2021010',
                'foto_peminjam' => 'foto_peminjam_2021010.jpg',
                'unit' => 'Fakultas Bahasa dan Sastra',
                'no_telp' => '081234567899',
                'nama_kegiatan' => 'Festival Sastra dan Puisi',
                'tanggal_mulai' => '2025-01-18',
                'tanggal_selesai' => '2025-01-25',
                'bukti' => 'bukti_festival_sastra.pdf',
                'status' => 'dipinjam',
                'kode_peminjaman' => 'SIT-20250118-0010',
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(10),
                'type' => 'ruangan_only'
            ],
            
            // 11. Peminjaman Sedang Berlangsung - Barang + Ruangan
            [
                'nama' => 'Hendra Wijaya',
                'nim_nip' => '2021011',
                'foto_peminjam' => 'foto_peminjam_2021011.jpg',
                'unit' => 'Fakultas Teknik Mesin',
                'no_telp' => '081234567800',
                'nama_kegiatan' => 'Kompetisi Robotika Nasional',
                'tanggal_mulai' => '2025-01-20',
                'tanggal_selesai' => '2025-01-25',
                'bukti' => 'bukti_kompetisi_robotika.pdf',
                'status' => 'dipinjam',
                'kode_peminjaman' => 'HEN-20250120-0011',
                'created_at' => now()->subDays(11),
                'updated_at' => now()->subDays(9),
                'type' => 'barang_ruangan'
            ],

            // ===== PEMINJAMAN SELESAI =====
            
            // 12. Peminjaman Selesai - Barang Saja
            [
                'nama' => 'Nina Kartika',
                'nim_nip' => '2021012',
                'foto_peminjam' => 'foto_peminjam_2021012.jpg',
                'unit' => 'Fakultas Farmasi',
                'no_telp' => '081234567801',
                'nama_kegiatan' => 'Pelatihan Laboratorium Kimia',
                'tanggal_mulai' => '2025-01-05',
                'tanggal_selesai' => '2025-01-08',
                'bukti' => 'bukti_pelatihan_laboratorium.pdf',
                'status' => 'dikembalikan',
                'kode_peminjaman' => 'NIN-20250105-0012',
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(18),
                'type' => 'barang_only'
            ],
            
            // 13. Peminjaman Selesai - Ruangan Saja
            [
                'nama' => 'Bambang Sutejo',
                'nim_nip' => '2021013',
                'foto_peminjam' => 'foto_peminjam_2021013.jpg',
                'unit' => 'Fakultas Matematika dan IPA',
                'no_telp' => '081234567802',
                'nama_kegiatan' => 'Olimpiade Sains Nasional',
                'tanggal_mulai' => '2025-01-08',
                'tanggal_selesai' => '2025-01-10',
                'bukti' => 'bukti_olimpiade_sains.pdf',
                'status' => 'dikembalikan',
                'kode_peminjaman' => 'BAM-20250108-0013',
                'created_at' => now()->subDays(22),
                'updated_at' => now()->subDays(20),
                'type' => 'ruangan_only'
            ],
            
            // 14. Peminjaman Selesai - Barang + Ruangan
            [
                'nama' => 'Ratna Dewi',
                'nim_nip' => '2021014',
                'foto_peminjam' => 'foto_peminjam_2021014.jpg',
                'unit' => 'Fakultas Teknik Elektro',
                'no_telp' => '081234567803',
                'nama_kegiatan' => 'Expo Teknologi dan Inovasi',
                'tanggal_mulai' => '2025-01-03',
                'tanggal_selesai' => '2025-01-05',
                'bukti' => 'bukti_expo_teknologi.pdf',
                'status' => 'dikembalikan',
                'kode_peminjaman' => 'RAT-20250103-0014',
                'created_at' => now()->subDays(25),
                'updated_at' => now()->subDays(23),
                'type' => 'barang_ruangan'
            ],

            // ===== PEMINJAMAN KHUSUS =====
            
            // 15. Peminjaman Jangka Panjang - Barang
            [
                'nama' => 'Doni Kusuma',
                'nim_nip' => '2021015',
                'foto_peminjam' => 'foto_peminjam_2021015.jpg',
                'unit' => 'Fakultas Teknik Industri',
                'no_telp' => '081234567804',
                'nama_kegiatan' => 'Penelitian Sistem Manufaktur',
                'tanggal_mulai' => '2025-01-01',
                'tanggal_selesai' => '2025-02-28',
                'bukti' => 'bukti_penelitian_manufaktur.pdf',
                'status' => 'disetujui',
                'kode_peminjaman' => 'DON-20250101-0015',
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(28),
                'type' => 'barang_only'
            ],
            
            // 16. Peminjaman Event Besar - Ruangan + Barang
            [
                'nama' => 'Yuni Safitri',
                'nim_nip' => '2021016',
                'foto_peminjam' => 'foto_peminjam_2021016.jpg',
                'unit' => 'Fakultas Ilmu Sosial dan Politik',
                'no_telp' => '081234567805',
                'nama_kegiatan' => 'Konferensi Nasional Mahasiswa',
                'tanggal_mulai' => '2025-02-01',
                'tanggal_selesai' => '2025-02-03',
                'bukti' => 'bukti_konferensi_nasional.pdf',
                'status' => 'menunggu',
                'kode_peminjaman' => 'YUN-20250201-0016',
                'created_at' => now()->subHours(1),
                'updated_at' => now()->subHours(1),
                'type' => 'barang_ruangan'
            ],
            
            // 17. Peminjaman Darurat - Barang
            [
                'nama' => 'Rudi Hartono',
                'nim_nip' => '2021017',
                'foto_peminjam' => 'foto_peminjam_2021017.jpg',
                'unit' => 'Fakultas Kedokteran Gigi',
                'no_telp' => '081234567806',
                'nama_kegiatan' => 'Pelayanan Kesehatan Gigi Darurat',
                'tanggal_mulai' => '2025-01-24',
                'tanggal_selesai' => '2025-01-24',
                'bukti' => 'bukti_pelayanan_darurat.pdf',
                'status' => 'disetujui',
                'kode_peminjaman' => 'RUD-20250124-0017',
                'created_at' => now()->subHours(12),
                'updated_at' => now()->subHours(10),
                'type' => 'barang_only'
            ],
            
            // 18. Peminjaman Rutin - Ruangan
            [
                'nama' => 'Sari Indah',
                'nim_nip' => '2021018',
                'foto_peminjam' => 'foto_peminjam_2021018.jpg',
                'unit' => 'Fakultas Ekonomi dan Bisnis',
                'no_telp' => '081234567807',
                'nama_kegiatan' => 'Kuliah Umum Ekonomi Digital',
                'tanggal_mulai' => '2025-01-26',
                'tanggal_selesai' => '2025-01-26',
                'bukti' => 'bukti_kuliah_umum.pdf',
                'status' => 'menunggu',
                'kode_peminjaman' => 'SAR-20250126-0018',
                'created_at' => now()->subHours(3),
                'updated_at' => now()->subHours(3),
                'type' => 'ruangan_only'
            ],
            
            // 19. Peminjaman Internasional - Barang + Ruangan
            [
                'nama' => 'Michael Chen',
                'nim_nip' => '2021019',
                'foto_peminjam' => 'foto_peminjam_2021019.jpg',
                'unit' => 'Fakultas Teknik Informatika',
                'no_telp' => '081234567808',
                'nama_kegiatan' => 'International Coding Bootcamp',
                'tanggal_mulai' => '2025-02-05',
                'tanggal_selesai' => '2025-02-10',
                'bukti' => 'bukti_coding_bootcamp.pdf',
                'status' => 'menunggu',
                'kode_peminjaman' => 'MIC-20250205-0019',
                'created_at' => now()->subHours(8),
                'updated_at' => now()->subHours(8),
                'type' => 'barang_ruangan'
            ],
            
            // 20. Peminjaman Alumni - Barang
            [
                'nama' => 'Lina Marlina',
                'nim_nip' => '2018001',
                'foto_peminjam' => 'foto_peminjam_2018001.jpg',
                'unit' => 'Alumni Fakultas Teknik',
                'no_telp' => '081234567809',
                'nama_kegiatan' => 'Reuni Akbar Teknik 2025',
                'tanggal_mulai' => '2025-02-15',
                'tanggal_selesai' => '2025-02-15',
                'bukti' => 'bukti_reuni_akbar.pdf',
                'status' => 'menunggu',
                'kode_peminjaman' => 'LIN-20250215-0020',
                'created_at' => now()->subHours(5),
                'updated_at' => now()->subHours(5),
                'type' => 'barang_only'
            ],
        ];

        // Create peminjaman records
        foreach ($peminjamanData as $data) {
            $type = $data['type'];
            unset($data['type']);
            
            $peminjaman = Peminjaman::create($data);
            
            // Create detail peminjaman based on type
            switch ($type) {
                case 'barang_only':
                    $this->createBarangDetails($peminjaman, $barangs);
                    break;
                    
                case 'ruangan_only':
                    $this->createRuanganDetails($peminjaman, $ruangans);
                    break;
                    
                case 'barang_ruangan':
                    $this->createBarangDetails($peminjaman, $barangs);
                    $this->createRuanganDetails($peminjaman, $ruangans);
                    break;
            }
            
            // Create notifications for new peminjaman
            if (in_array($peminjaman->status, ['menunggu', 'disetujui'])) {
                $this->createNotification($peminjaman);
            }
        }

        $this->command->info('Comprehensive peminjaman data created successfully!');
        $this->command->info('Created ' . count($peminjamanData) . ' peminjaman records');
        $this->command->info('Created ' . DetailPeminjaman::count() . ' detail peminjaman barang records');
        $this->command->info('Created ' . DetailPeminjamanRuangan::count() . ' detail peminjaman ruangan records');
        $this->command->info('Created ' . Notification::count() . ' notification records');
        
        // Display statistics
        $this->displayStatistics();
    }

    private function createBarangDetails($peminjaman, $barangs)
    {
        // Random barang items (1-4 items)
        $selectedBarangs = $barangs->random(rand(1, 4));
        foreach ($selectedBarangs as $barang) {
            $jumlah = rand(1, 5);
            $jumlahDikembalikan = 0;
            
            // For completed peminjaman, set some items as returned
            if ($peminjaman->status === 'dikembalikan') {
                $jumlahDikembalikan = rand(0, $jumlah);
            }
            
            DetailPeminjaman::create([
                'peminjaman_id' => $peminjaman->id,
                'barang_id' => $barang->id,
                'jumlah' => $jumlah,
                'jumlah_dikembalikan' => $jumlahDikembalikan,
                'created_at' => $peminjaman->created_at,
                'updated_at' => $peminjaman->updated_at,
            ]);
        }
    }

    private function createRuanganDetails($peminjaman, $ruangans)
    {
        // Random ruangan (1-2 ruangan)
        $selectedRuangans = $ruangans->random(rand(1, 2));
        foreach ($selectedRuangans as $ruangan) {
            DetailPeminjamanRuangan::create([
                'peminjaman_id' => $peminjaman->id,
                'ruangan_id' => $ruangan->id,
                'created_at' => $peminjaman->created_at,
                'updated_at' => $peminjaman->updated_at,
            ]);
        }
    }

    private function createNotification($peminjaman)
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

        Notification::create([
            'type' => 'peminjaman_baru',
            'title' => $title,
            'message' => $message,
            'status' => 'unread',
            'peminjaman_id' => $peminjaman->id,
            'user_id' => null,
            'data' => $data,
            'created_at' => $peminjaman->created_at,
            'updated_at' => $peminjaman->updated_at,
        ]);
    }

    private function displayStatistics()
    {
        $this->command->info('=== PEMINJAMAN STATISTICS ===');
        
        // Status statistics
        $statusStats = Peminjaman::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
            
        foreach ($statusStats as $stat) {
            $this->command->info("Status {$stat->status}: {$stat->count} peminjaman");
        }
        
        // Type statistics
        $barangOnly = DetailPeminjaman::distinct('peminjaman_id')->count();
        $ruanganOnly = DetailPeminjamanRuangan::distinct('peminjaman_id')->count();
        $both = Peminjaman::whereHas('details')
            ->whereHas('detailsRuangan')
            ->count();
            
        $this->command->info("Barang only: {$barangOnly} peminjaman");
        $this->command->info("Ruangan only: {$ruanganOnly} peminjaman");
        $this->command->info("Barang + Ruangan: {$both} peminjaman");
        
        // Recent peminjaman
        $recent = Peminjaman::where('created_at', '>=', now()->subDays(7))->count();
        $this->command->info("Recent peminjaman (7 days): {$recent}");
        
        // Notification statistics
        $unreadNotifications = Notification::where('status', 'unread')->count();
        $this->command->info("Unread notifications: {$unreadNotifications}");
    }
}
