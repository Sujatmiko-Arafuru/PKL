<?php

namespace Database\Seeders;

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

class PeminjamanKompleksSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data first
        DB::table('notifications')->delete();
        DB::table('detail_peminjaman_ruangans')->delete();
        DB::table('detail_peminjamans')->delete();
        DB::table('peminjamans')->delete();

        // Get existing barang and ruangan
        $barangs = Barang::all();
        $ruangans = Ruangan::all();

        if ($barangs->isEmpty() || $ruangans->isEmpty()) {
            echo "Error: Tidak ada data barang atau ruangan yang tersedia!\n";
            return;
        }

        // Data peminjaman kompleks (barang + ruangan)
        $peminjamanKompleksData = [
            [
                'nama' => 'Dr. Budi Santoso',
                'nim_nip' => '1985001',
                'foto_peminjam' => 'dummy_foto_dosen_1.jpg',
                'unit' => 'Fakultas Teknik',
                'no_telp' => '081234567890',
                'nama_kegiatan' => 'Seminar Nasional Teknologi',
                'tanggal_mulai' => '2025-02-15',
                'tanggal_selesai' => '2025-02-16',
                'bukti' => 'dummy_bukti_seminar_1.pdf',
                'status' => 'menunggu',
                'kode_peminjaman' => 'PMJ-' . date('Y') . '-' . str_pad(1, 3, '0', STR_PAD_LEFT),
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
                'barang_items' => [
                    ['barang_id' => $barangs->random()->id, 'jumlah' => 2],
                    ['barang_id' => $barangs->random()->id, 'jumlah' => 1],
                ],
                'ruangan_items' => [
                    ['ruangan_id' => $ruangans->random()->id],
                ]
            ],
            [
                'nama' => 'Siti Nurhaliza',
                'nim_nip' => '2021008',
                'foto_peminjam' => 'dummy_foto_mahasiswa_1.jpg',
                'unit' => 'Fakultas Ekonomi',
                'no_telp' => '081234567891',
                'nama_kegiatan' => 'Workshop Kewirausahaan',
                'tanggal_mulai' => '2025-02-20',
                'tanggal_selesai' => '2025-02-21',
                'bukti' => 'dummy_bukti_workshop_1.pdf',
                'status' => 'disetujui',
                'kode_peminjaman' => 'PMJ-' . date('Y') . '-' . str_pad(2, 3, '0', STR_PAD_LEFT),
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(2),
                'barang_items' => [
                    ['barang_id' => $barangs->random()->id, 'jumlah' => 3],
                    ['barang_id' => $barangs->random()->id, 'jumlah' => 2],
                    ['barang_id' => $barangs->random()->id, 'jumlah' => 1],
                ],
                'ruangan_items' => [
                    ['ruangan_id' => $ruangans->random()->id],
                ]
            ],
            [
                'nama' => 'Ahmad Rizki',
                'nim_nip' => '2021009',
                'foto_peminjam' => 'dummy_foto_mahasiswa_2.jpg',
                'unit' => 'Fakultas Hukum',
                'no_telp' => '081234567892',
                'nama_kegiatan' => 'Simulasi Sidang Mahasiswa',
                'tanggal_mulai' => '2025-02-25',
                'tanggal_selesai' => '2025-02-26',
                'bukti' => 'dummy_bukti_simulasi_1.pdf',
                'status' => 'menunggu',
                'kode_peminjaman' => 'PMJ-' . date('Y') . '-' . str_pad(3, 3, '0', STR_PAD_LEFT),
                'created_at' => now()->subHours(6),
                'updated_at' => now()->subHours(6),
                'barang_items' => [
                    ['barang_id' => $barangs->random()->id, 'jumlah' => 1],
                ],
                'ruangan_items' => [
                    ['ruangan_id' => $ruangans->random()->id],
                ]
            ],
            [
                'nama' => 'Prof. Dr. Maria Garcia',
                'nim_nip' => '1978001',
                'foto_peminjam' => 'dummy_foto_professor_1.jpg',
                'unit' => 'Fakultas Kedokteran',
                'no_telp' => '081234567893',
                'nama_kegiatan' => 'Konferensi Internasional Kesehatan',
                'tanggal_mulai' => '2025-03-01',
                'tanggal_selesai' => '2025-03-03',
                'bukti' => 'dummy_bukti_konferensi_1.pdf',
                'status' => 'menunggu',
                'kode_peminjaman' => 'PMJ-' . date('Y') . '-' . str_pad(4, 3, '0', STR_PAD_LEFT),
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
                'barang_items' => [
                    ['barang_id' => $barangs->random()->id, 'jumlah' => 5],
                    ['barang_id' => $barangs->random()->id, 'jumlah' => 3],
                    ['barang_id' => $barangs->random()->id, 'jumlah' => 2],
                ],
                'ruangan_items' => [
                    ['ruangan_id' => $ruangans->random()->id],
                    ['ruangan_id' => $ruangans->random()->id],
                ]
            ],
            [
                'nama' => 'Rina Melati',
                'nim_nip' => '2021010',
                'foto_peminjam' => 'dummy_foto_mahasiswa_3.jpg',
                'unit' => 'Fakultas Seni',
                'no_telp' => '081234567894',
                'nama_kegiatan' => 'Pameran Seni Rupa Digital',
                'tanggal_mulai' => '2025-03-05',
                'tanggal_selesai' => '2025-03-07',
                'bukti' => 'dummy_bukti_pameran_2.pdf',
                'status' => 'ditolak',
                'kode_peminjaman' => 'PMJ-' . date('Y') . '-' . str_pad(5, 3, '0', STR_PAD_LEFT),
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(4),
                'barang_items' => [
                    ['barang_id' => $barangs->random()->id, 'jumlah' => 4],
                    ['barang_id' => $barangs->random()->id, 'jumlah' => 2],
                ],
                'ruangan_items' => [
                    ['ruangan_id' => $ruangans->random()->id],
                ]
            ],
            [
                'nama' => 'Budi Prasetyo',
                'nim_nip' => '2021011',
                'foto_peminjam' => 'dummy_foto_mahasiswa_4.jpg',
                'unit' => 'Fakultas Pertanian',
                'no_telp' => '081234567895',
                'nama_kegiatan' => 'Pelatihan Hidroponik',
                'tanggal_mulai' => '2025-03-10',
                'tanggal_selesai' => '2025-03-12',
                'bukti' => 'dummy_bukti_pelatihan_1.pdf',
                'status' => 'dipinjam',
                'kode_peminjaman' => 'PMJ-' . date('Y') . '-' . str_pad(6, 3, '0', STR_PAD_LEFT),
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(6),
                'barang_items' => [
                    ['barang_id' => $barangs->random()->id, 'jumlah' => 2],
                    ['barang_id' => $barangs->random()->id, 'jumlah' => 1],
                ],
                'ruangan_items' => [
                    ['ruangan_id' => $ruangans->random()->id],
                ]
            ],
            [
                'nama' => 'Dewi Sartika',
                'nim_nip' => '2021012',
                'foto_peminjam' => 'dummy_foto_mahasiswa_5.jpg',
                'unit' => 'Fakultas Psikologi',
                'no_telp' => '081234567896',
                'nama_kegiatan' => 'Seminar Kesehatan Mental',
                'tanggal_mulai' => '2025-03-15',
                'tanggal_selesai' => '2025-03-16',
                'bukti' => 'dummy_bukti_seminar_2.pdf',
                'status' => 'pengembalian_diajukan',
                'kode_peminjaman' => 'PMJ-' . date('Y') . '-' . str_pad(7, 3, '0', STR_PAD_LEFT),
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(1),
                'barang_items' => [
                    ['barang_id' => $barangs->random()->id, 'jumlah' => 1],
                ],
                'ruangan_items' => [
                    ['ruangan_id' => $ruangans->random()->id],
                ]
            ],
            [
                'nama' => 'Ir. Bambang Sutejo',
                'nim_nip' => '1982001',
                'foto_peminjam' => 'dummy_foto_dosen_2.jpg',
                'unit' => 'Fakultas Teknik',
                'no_telp' => '081234567897',
                'nama_kegiatan' => 'Workshop Robotika Lanjutan',
                'tanggal_mulai' => '2025-03-20',
                'tanggal_selesai' => '2025-03-22',
                'bukti' => 'dummy_bukti_workshop_2.pdf',
                'status' => 'dikembalikan',
                'kode_peminjaman' => 'PMJ-' . date('Y') . '-' . str_pad(8, 3, '0', STR_PAD_LEFT),
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(2),
                'barang_items' => [
                    ['barang_id' => $barangs->random()->id, 'jumlah' => 3],
                    ['barang_id' => $barangs->random()->id, 'jumlah' => 2],
                ],
                'ruangan_items' => [
                    ['ruangan_id' => $ruangans->random()->id],
                ]
            ]
        ];

        echo "Creating complex peminjaman data (barang + ruangan)...\n";

        foreach ($peminjamanKompleksData as $index => $data) {
            // Create peminjaman
            $peminjaman = Peminjaman::create([
                'nama' => $data['nama'],
                'nim_nip' => $data['nim_nip'],
                'foto_peminjam' => $data['foto_peminjam'],
                'unit' => $data['unit'],
                'no_telp' => $data['no_telp'],
                'nama_kegiatan' => $data['nama_kegiatan'],
                'tanggal_mulai' => $data['tanggal_mulai'],
                'tanggal_selesai' => $data['tanggal_selesai'],
                'bukti' => $data['bukti'],
                'status' => $data['status'],
                'kode_peminjaman' => $data['kode_peminjaman'],
                'created_at' => $data['created_at'],
                'updated_at' => $data['updated_at'],
            ]);

            echo "Created peminjaman: {$peminjaman->kode_peminjaman} - {$peminjaman->nama_kegiatan}\n";

            // Create detail peminjaman barang
            foreach ($data['barang_items'] as $barangItem) {
                DetailPeminjaman::create([
                    'peminjaman_id' => $peminjaman->id,
                    'barang_id' => $barangItem['barang_id'],
                    'jumlah' => $barangItem['jumlah'],
                ]);
            }

            // Create detail peminjaman ruangan
            foreach ($data['ruangan_items'] as $ruanganItem) {
                DetailPeminjamanRuangan::create([
                    'peminjaman_id' => $peminjaman->id,
                    'ruangan_id' => $ruanganItem['ruangan_id'],
                ]);
            }

            // Create notifications for new peminjaman
            if (in_array($peminjaman->status, ['menunggu', 'disetujui'])) {
                $barang = Barang::find($data['barang_items'][0]['barang_id']);
                $ruangan = Ruangan::find($data['ruangan_items'][0]['ruangan_id']);
                
                Notification::create([
                    'type' => 'peminjaman_baru',
                    'title' => 'Peminjaman Baru',
                    'message' => "{$peminjaman->nama} ({$peminjaman->nim_nip}) mengajukan peminjaman untuk kegiatan {$peminjaman->nama_kegiatan}",
                    'status' => 'unread',
                    'peminjaman_id' => $peminjaman->id,
                    'data' => json_encode([
                        'nama' => $peminjaman->nama,
                        'nim_nip' => $peminjaman->nim_nip,
                        'nama_kegiatan' => $peminjaman->nama_kegiatan,
                        'peminjaman_id' => $peminjaman->id,
                        'tanggal_mulai' => $peminjaman->tanggal_mulai,
                        'tanggal_selesai' => $peminjaman->tanggal_selesai,
                        'barang_count' => count($data['barang_items']),
                        'ruangan_count' => count($data['ruangan_items']),
                    ]),
                    'created_at' => $peminjaman->created_at,
                    'updated_at' => $peminjaman->created_at,
                ]);
            }
        }

        // Summary
        $totalPeminjaman = Peminjaman::count();
        $totalDetailBarang = DetailPeminjaman::count();
        $totalDetailRuangan = DetailPeminjamanRuangan::count();
        $totalNotifications = Notification::count();

        echo "\n=== SUMMARY ===\n";
        echo "Total Peminjaman: {$totalPeminjaman}\n";
        echo "Total Detail Barang: {$totalDetailBarang}\n";
        echo "Total Detail Ruangan: {$totalDetailRuangan}\n";
        echo "Total Notifications: {$totalNotifications}\n";

        // Status distribution
        echo "\n=== STATUS DISTRIBUTION ===\n";
        $statuses = Peminjaman::selectRaw('status, count(*) as count')->groupBy('status')->get();
        foreach ($statuses as $status) {
            echo "{$status->status}: {$status->count}\n";
        }

        echo "\n✅ Peminjaman kompleks (barang + ruangan) berhasil dibuat!\n";
    }
}
