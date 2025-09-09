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
                'kode' => 'AU-001',
                'lokasi' => 'Gedung Utama',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Ruang Meeting VIP',
                'deskripsi' => 'Ruang meeting eksklusif untuk pertemuan tingkat tinggi dan presentasi penting.',
                'kode' => 'RMV-001',
                'lokasi' => 'Gedung Utama',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Lab Komputer 1',
                'deskripsi' => 'Laboratorium komputer untuk praktikum dan pelatihan teknologi informasi.',
                'kode' => 'LK-001',
                'lokasi' => 'Gedung Teknik',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Ruang Seminar',
                'deskripsi' => 'Ruang seminar yang nyaman untuk presentasi dan diskusi kelompok.',
                'kode' => 'RS-001',
                'lokasi' => 'Gedung Akademik',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Studio Musik',
                'deskripsi' => 'Studio musik untuk latihan dan rekaman dengan akustik yang baik.',
                'kode' => 'SM-001',
                'lokasi' => 'Gedung Seni',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Ruang Kerja Kelompok',
                'deskripsi' => 'Ruang kerja kelompok yang fleksibel untuk diskusi dan kolaborasi.',
                'kode' => 'RKK-001',
                'lokasi' => 'Gedung Perpustakaan',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Auditorium',
                'deskripsi' => 'Auditorium besar untuk acara-acara besar dan pertunjukan.',
                'kode' => 'AUD-001',
                'lokasi' => 'Gedung Pusat',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Ruang Diskusi',
                'deskripsi' => 'Ruang diskusi kecil yang nyaman untuk pertemuan informal.',
                'kode' => 'RD-001',
                'lokasi' => 'Gedung Akademik',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Lab Kimia',
                'deskripsi' => 'Laboratorium kimia dengan peralatan lengkap untuk praktikum dan penelitian.',
                'kode' => 'LK-002',
                'lokasi' => 'Gedung Sains',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Ruang Workshop',
                'deskripsi' => 'Ruang workshop untuk kegiatan praktik dan pelatihan keterampilan.',
                'kode' => 'RW-001',
                'lokasi' => 'Gedung Teknik',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Studio Fotografi',
                'deskripsi' => 'Studio fotografi dengan lighting profesional dan backdrop.',
                'kode' => 'SF-001',
                'lokasi' => 'Gedung Seni',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Ruang Konferensi',
                'deskripsi' => 'Ruang konferensi dengan teknologi video conference dan audio system.',
                'kode' => 'RK-001',
                'lokasi' => 'Gedung Pusat',
                'status' => 'tersedia'
            ]
        ];

        foreach ($ruangans as $ruangan) {
            Ruangan::create($ruangan);
        }
    }
}
