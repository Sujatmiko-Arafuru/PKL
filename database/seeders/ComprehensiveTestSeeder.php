<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Ruangan;
use App\Models\User;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\DetailPeminjamanRuangan;
use Illuminate\Support\Facades\Hash;

class ComprehensiveTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test users
        $users = [
            [
                'name' => 'Ahmad Rizki',
                'nama' => 'Ahmad Rizki',
                'nim_nip' => '1234567890',
                'unit' => 'Teknik Informatika',
                'no_telp' => '081234567890',
                'email' => 'ahmad@test.com',
                'password' => Hash::make('password123')
            ],
            [
                'name' => 'Siti Nurhaliza',
                'nama' => 'Siti Nurhaliza',
                'nim_nip' => '0987654321',
                'unit' => 'Sistem Informasi',
                'no_telp' => '081987654321',
                'email' => 'siti@test.com',
                'password' => Hash::make('password123')
            ],
            [
                'name' => 'Budi Santoso',
                'nama' => 'Budi Santoso',
                'nim_nip' => '1122334455',
                'unit' => 'Teknik Komputer',
                'no_telp' => '081122334455',
                'email' => 'budi@test.com',
                'password' => Hash::make('password123')
            ]
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        // Create test items with good stock
        $barangs = [
            [
                'nama' => 'Laptop Dell Inspiron',
                'kode' => 'LAP-001',
                'deskripsi' => 'Laptop untuk presentasi dan kerja',
                'stok' => 15,
                'status' => 'tersedia',
                'foto1' => null
            ],
            [
                'nama' => 'Proyektor Epson',
                'kode' => 'PROJ-001',
                'deskripsi' => 'Proyektor untuk presentasi',
                'stok' => 8,
                'status' => 'tersedia',
                'foto1' => null
            ],
            [
                'nama' => 'CCTV Camera Dome',
                'kode' => 'CCTV-001',
                'deskripsi' => 'Kamera CCTV untuk keamanan',
                'stok' => 20,
                'status' => 'tersedia',
                'foto1' => null
            ],
            [
                'nama' => 'Speaker JBL',
                'kode' => 'SPK-001',
                'deskripsi' => 'Speaker untuk acara',
                'stok' => 12,
                'status' => 'tersedia',
                'foto1' => null
            ],
            [
                'nama' => 'Microphone Wireless',
                'kode' => 'MIC-001',
                'deskripsi' => 'Microphone nirkabel',
                'stok' => 6,
                'status' => 'tersedia',
                'foto1' => null
            ]
        ];

        foreach ($barangs as $barangData) {
            Barang::create($barangData);
        }

        // Create test rooms
        $ruangans = [
            [
                'nama' => 'Auditorium Utama',
                'kode' => 'AUD-001',
                'deskripsi' => 'Auditorium untuk acara besar',
                'lokasi' => 'Gedung Pusat',
                'status' => 'tersedia',
                'foto1' => null,
                'foto2' => null,
                'foto3' => null
            ],
            [
                'nama' => 'Lab Komputer 1',
                'kode' => 'LAB-001',
                'deskripsi' => 'Laboratorium komputer untuk praktikum',
                'lokasi' => 'Gedung Teknik',
                'status' => 'tersedia',
                'foto1' => null,
                'foto2' => null,
                'foto3' => null
            ],
            [
                'nama' => 'Ruang Meeting A',
                'kode' => 'MTG-001',
                'deskripsi' => 'Ruang meeting untuk diskusi',
                'lokasi' => 'Gedung Administrasi',
                'status' => 'tersedia',
                'foto1' => null,
                'foto2' => null,
                'foto3' => null
            ],
            [
                'nama' => 'Studio Recording',
                'kode' => 'STU-001',
                'deskripsi' => 'Studio untuk recording dan podcast',
                'lokasi' => 'Gedung Multimedia',
                'status' => 'tersedia',
                'foto1' => null,
                'foto2' => null,
                'foto3' => null
            ]
        ];

        foreach ($ruangans as $ruanganData) {
            Ruangan::create($ruanganData);
        }

        $this->command->info('Test data created successfully!');
        $this->command->info('Users: ' . count($users));
        $this->command->info('Items: ' . count($barangs));
        $this->command->info('Rooms: ' . count($ruangans));
    }
}