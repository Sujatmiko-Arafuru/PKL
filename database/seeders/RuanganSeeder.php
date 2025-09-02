<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ruangan;

class RuanganSeeder extends Seeder
{
    public function run(): void
    {
        $ruangans = [
            [
                'nama' => 'Aula Utama',
                'deskripsi' => 'Aula utama yang dapat menampung berbagai acara besar seperti seminar, workshop, dan acara formal.',
                'kapasitas' => 200,
                'kode' => 'AU-001',
                'kategori' => 'Aula',
                'lantai' => '1',
                'lokasi' => 'Gedung Utama',
                'fasilitas' => 'Podium, Sound System, LCD Projector, AC, Panggung',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Ruang Meeting VIP',
                'deskripsi' => 'Ruang meeting eksklusif untuk pertemuan tingkat tinggi dan presentasi penting.',
                'kapasitas' => 20,
                'kode' => 'RMV-001',
                'kategori' => 'Meeting Room',
                'lantai' => '2',
                'lokasi' => 'Gedung Utama',
                'fasilitas' => 'Meja Meeting, LCD Projector, Whiteboard, AC, Coffee Break',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Lab Komputer 1',
                'deskripsi' => 'Laboratorium komputer untuk praktikum dan pelatihan teknologi informasi.',
                'kapasitas' => 30,
                'kode' => 'LK-001',
                'kategori' => 'Laboratorium',
                'lantai' => '1',
                'lokasi' => 'Gedung Teknik',
                'fasilitas' => '30 PC, LCD Projector, AC, Internet, Printer',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Ruang Seminar',
                'deskripsi' => 'Ruang seminar yang nyaman untuk presentasi dan diskusi kelompok.',
                'kapasitas' => 50,
                'kode' => 'RS-001',
                'kategori' => 'Seminar',
                'lantai' => '2',
                'lokasi' => 'Gedung Akademik',
                'fasilitas' => 'Podium, LCD Projector, Sound System, AC, Papan Tulis',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Studio Musik',
                'deskripsi' => 'Studio musik untuk latihan dan rekaman dengan akustik yang baik.',
                'kapasitas' => 15,
                'kode' => 'SM-001',
                'kategori' => 'Studio',
                'lantai' => '1',
                'lokasi' => 'Gedung Seni',
                'fasilitas' => 'Instrumen Musik, Sound System, AC, Akustik Treatment',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Ruang Kerja Kelompok',
                'deskripsi' => 'Ruang kerja kelompok yang fleksibel untuk diskusi dan kolaborasi.',
                'kapasitas' => 12,
                'kode' => 'RKK-001',
                'kategori' => 'Kerja Kelompok',
                'lantai' => '3',
                'lokasi' => 'Gedung Perpustakaan',
                'fasilitas' => 'Meja Kerja, Whiteboard, AC, Internet, Printer',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Auditorium',
                'deskripsi' => 'Auditorium besar untuk acara-acara besar dan pertunjukan.',
                'kapasitas' => 500,
                'kode' => 'AUD-001',
                'kategori' => 'Auditorium',
                'lantai' => '1',
                'lokasi' => 'Gedung Pusat',
                'fasilitas' => 'Panggung Besar, Sound System Profesional, Lighting, AC, Layar Besar',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Ruang Diskusi',
                'deskripsi' => 'Ruang diskusi kecil yang nyaman untuk pertemuan informal.',
                'kapasitas' => 8,
                'kode' => 'RD-001',
                'kategori' => 'Diskusi',
                'lantai' => '2',
                'lokasi' => 'Gedung Akademik',
                'fasilitas' => 'Meja Diskusi, Whiteboard, AC, Coffee Corner',
                'status' => 'tersedia'
            ]
        ];

        foreach ($ruangans as $ruangan) {
            Ruangan::create($ruangan);
        }
    }
}
