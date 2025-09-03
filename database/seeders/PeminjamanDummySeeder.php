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
use Illuminate\Support\Str;

class PeminjamanDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing barang and ruangan
        $barangs = Barang::all();
        $ruangans = Ruangan::all();
        
        if ($barangs->isEmpty() || $ruangans->isEmpty()) {
            $this->command->error('Barang atau Ruangan tidak ditemukan. Jalankan seeder barang dan ruangan terlebih dahulu.');
            return;
        }

        // Create dummy peminjaman data
        $peminjamanData = [
            // 1. Peminjaman Baru (Menunggu Persetujuan)
            [
                'nama' => 'Ahmad Fadillah',
                'nim_nip' => '2021001',
                'foto_peminjam' => 'dummy_foto_1.jpg',
                'unit' => 'Fakultas Teknik',
                'no_telp' => '081234567890',
                'nama_kegiatan' => 'Workshop Robotik',
                'tanggal_mulai' => '2025-01-25',
                'tanggal_selesai' => '2025-01-26',
                'bukti' => 'dummy_bukti_1.pdf',
                'status' => 'menunggu',
                'kode_peminjaman' => 'PMJ-' . date('Y') . '-' . str_pad(1, 3, '0', STR_PAD_LEFT),
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            
            // 2. Peminjaman Disetujui
            [
                'nama' => 'Sarah Putri',
                'nim_nip' => '2021002',
                'foto_peminjam' => 'dummy_foto_2.jpg',
                'unit' => 'Fakultas Ekonomi',
                'no_telp' => '081234567891',
                'nama_kegiatan' => 'Seminar Kewirausahaan',
                'tanggal_mulai' => '2025-01-20',
                'tanggal_selesai' => '2025-01-21',
                'bukti' => 'dummy_bukti_2.pdf',
                'status' => 'disetujui',
                'kode_peminjaman' => 'PMJ-' . date('Y') . '-' . str_pad(2, 3, '0', STR_PAD_LEFT),
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(3),
            ],
            
            // 3. Peminjaman Ditolak
            [
                'nama' => 'Budi Santoso',
                'nim_nip' => '2021003',
                'foto_peminjam' => 'dummy_foto_3.jpg',
                'unit' => 'Fakultas Hukum',
                'no_telp' => '081234567892',
                'nama_kegiatan' => 'Diskusi Panel Hukum',
                'tanggal_mulai' => '2025-01-18',
                'tanggal_selesai' => '2025-01-19',
                'bukti' => 'dummy_bukti_3.pdf',
                'status' => 'ditolak',
                'kode_peminjaman' => 'PMJ-' . date('Y') . '-' . str_pad(3, 3, '0', STR_PAD_LEFT),
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(6),
            ],
            
            // 4. Peminjaman Sedang Berlangsung (Dipinjam)
            [
                'nama' => 'Dewi Sartika',
                'nim_nip' => '2021004',
                'foto_peminjam' => 'dummy_foto_4.jpg',
                'unit' => 'Fakultas Kedokteran',
                'no_telp' => '081234567893',
                'nama_kegiatan' => 'Pelatihan Kesehatan',
                'tanggal_mulai' => '2025-01-15',
                'tanggal_selesai' => '2025-01-17',
                'bukti' => 'dummy_bukti_4.pdf',
                'status' => 'dipinjam',
                'kode_peminjaman' => 'PMJ-' . date('Y') . '-' . str_pad(4, 3, '0', STR_PAD_LEFT),
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(8),
            ],
            
            // 5. Peminjaman Selesai (Dikembalikan)
            [
                'nama' => 'Rizki Pratama',
                'nim_nip' => '2021005',
                'foto_peminjam' => 'dummy_foto_5.jpg',
                'unit' => 'Fakultas Ilmu Komputer',
                'no_telp' => '081234567894',
                'nama_kegiatan' => 'Hackathon Programming',
                'tanggal_mulai' => '2025-01-10',
                'tanggal_selesai' => '2025-01-12',
                'bukti' => 'dummy_bukti_5.pdf',
                'status' => 'dikembalikan',
                'kode_peminjaman' => 'PMJ-' . date('Y') . '-' . str_pad(5, 3, '0', STR_PAD_LEFT),
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(13),
            ],
            
            // 6. Peminjaman Baru Hari Ini
            [
                'nama' => 'Maya Indah',
                'nim_nip' => '2021006',
                'foto_peminjam' => 'dummy_foto_6.jpg',
                'unit' => 'Fakultas Seni',
                'no_telp' => '081234567895',
                'nama_kegiatan' => 'Pameran Seni Rupa',
                'tanggal_mulai' => '2025-01-30',
                'tanggal_selesai' => '2025-02-01',
                'bukti' => 'dummy_bukti_6.pdf',
                'status' => 'menunggu',
                'kode_peminjaman' => 'PMJ-' . date('Y') . '-' . str_pad(6, 3, '0', STR_PAD_LEFT),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // 7. Peminjaman dengan Ruangan
            [
                'nama' => 'Agus Setiawan',
                'nim_nip' => '2021007',
                'foto_peminjam' => 'dummy_foto_7.jpg',
                'unit' => 'Fakultas Pertanian',
                'no_telp' => '081234567896',
                'nama_kegiatan' => 'Seminar Pertanian Modern',
                'tanggal_mulai' => '2025-01-28',
                'tanggal_selesai' => '2025-01-29',
                'bukti' => 'dummy_bukti_7.pdf',
                'status' => 'menunggu',
                'kode_peminjaman' => 'PMJ-' . date('Y') . '-' . str_pad(7, 3, '0', STR_PAD_LEFT),
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ],
        ];

        // Create peminjaman records
        foreach ($peminjamanData as $data) {
            $peminjaman = Peminjaman::create($data);
            
            // Create detail peminjaman barang (for first 6 peminjaman)
            if ($peminjaman->id <= 6) {
                // Random barang items
                $selectedBarangs = $barangs->random(rand(1, 3));
                foreach ($selectedBarangs as $barang) {
                    DetailPeminjaman::create([
                        'peminjaman_id' => $peminjaman->id,
                        'barang_id' => $barang->id,
                        'jumlah' => rand(1, 5),
                        'jumlah_dikembalikan' => $peminjaman->status === 'dikembalikan' ? rand(1, 5) : 0,
                        'created_at' => $peminjaman->created_at,
                        'updated_at' => $peminjaman->updated_at,
                    ]);
                }
            }
            
            // Create detail peminjaman ruangan (for peminjaman ke-7)
            if ($peminjaman->id == 7) {
                $selectedRuangan = $ruangans->random();
                DetailPeminjamanRuangan::create([
                    'peminjaman_id' => $peminjaman->id,
                    'ruangan_id' => $selectedRuangan->id,
                    'created_at' => $peminjaman->created_at,
                    'updated_at' => $peminjaman->updated_at,
                ]);
            }
            
            // Create notifications for new peminjaman
            if (in_array($peminjaman->status, ['menunggu', 'disetujui'])) {
                $this->createNotification($peminjaman);
            }
        }

        $this->command->info('Dummy peminjaman data created successfully!');
        $this->command->info('Created ' . count($peminjamanData) . ' peminjaman records');
        $this->command->info('Created ' . DetailPeminjaman::count() . ' detail peminjaman barang records');
        $this->command->info('Created ' . DetailPeminjamanRuangan::count() . ' detail peminjaman ruangan records');
        $this->command->info('Created ' . Notification::count() . ' notification records');
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
}
