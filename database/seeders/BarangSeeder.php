<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barangs = [
            // Elektronik
            [
                'nama' => 'Laptop Dell Inspiron 15',
                'deskripsi' => 'Laptop Dell Inspiron 15 dengan processor Intel i5, RAM 8GB, SSD 256GB',
                'kategori' => 'Elektronik',
                'kode' => 'LAP001',
                'stok' => 10,
                'satuan' => 'Unit',
                'foto1' => 'laptop-dell.jpg',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Proyektor Epson EB-X41',
                'deskripsi' => 'Proyektor Epson EB-X41 dengan resolusi XGA dan brightness 3600 lumens',
                'kategori' => 'Elektronik',
                'kode' => 'PRO001',
                'stok' => 5,
                'satuan' => 'Unit',
                'foto1' => 'proyektor-epson.jpg',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Speaker JBL Charge 4',
                'deskripsi' => 'Speaker Bluetooth JBL Charge 4 dengan daya tahan baterai 20 jam',
                'kategori' => 'Elektronik',
                'kode' => 'SPK001',
                'stok' => 8,
                'satuan' => 'Unit',
                'foto1' => 'speaker-jbl.jpg',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Kamera Canon EOS 2000D',
                'deskripsi' => 'Kamera DSLR Canon EOS 2000D dengan lensa 18-55mm',
                'kategori' => 'Elektronik',
                'kode' => 'CAM001',
                'stok' => 3,
                'satuan' => 'Unit',
                'foto1' => 'kamera-canon.jpg',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Microphone Wireless Shure BLX24',
                'deskripsi' => 'Microphone wireless Shure BLX24 dengan receiver dan transmitter',
                'kategori' => 'Elektronik',
                'kode' => 'MIC001',
                'stok' => 4,
                'satuan' => 'Set',
                'foto1' => 'microphone-shure.jpg',
                'status' => 'tersedia'
            ],

            // Furniture
            [
                'nama' => 'Meja Seminar Kayu Jati',
                'deskripsi' => 'Meja seminar kayu jati dengan ukuran 2m x 0.8m untuk 6 orang',
                'kategori' => 'Furniture',
                'kode' => 'MEJ001',
                'stok' => 15,
                'satuan' => 'Unit',
                'foto1' => 'meja-seminar.jpg',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Kursi Plastik Putih',
                'deskripsi' => 'Kursi plastik putih dengan sandaran tinggi untuk seminar',
                'kategori' => 'Furniture',
                'kode' => 'KUR001',
                'stok' => 50,
                'satuan' => 'Unit',
                'foto1' => 'kursi-plastik.jpg',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Papan Tulis Putih 2x1m',
                'deskripsi' => 'Papan tulis putih dengan ukuran 2m x 1m dan standar mobile',
                'kategori' => 'Furniture',
                'kode' => 'PAP001',
                'stok' => 12,
                'satuan' => 'Unit',
                'foto1' => 'papan-tulis.jpg',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Stand Banner Roll Up',
                'deskripsi' => 'Stand banner roll up dengan ukuran 85cm x 200cm',
                'kategori' => 'Furniture',
                'kode' => 'BAN001',
                'stok' => 8,
                'satuan' => 'Unit',
                'foto1' => 'stand-banner.jpg',
                'status' => 'tersedia'
            ],

            // Peralatan Olahraga
            [
                'nama' => 'Bola Sepak Nike Strike',
                'deskripsi' => 'Bola sepak Nike Strike ukuran 5 untuk pertandingan resmi',
                'kategori' => 'Olahraga',
                'kode' => 'BOL001',
                'stok' => 20,
                'satuan' => 'Buah',
                'foto1' => 'bola-sepak.jpg',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Raket Badminton Yonex',
                'deskripsi' => 'Raket badminton Yonex ArcSaber 11 dengan grip dan tali',
                'kategori' => 'Olahraga',
                'kode' => 'RAK001',
                'stok' => 15,
                'satuan' => 'Unit',
                'foto1' => 'raket-badminton.jpg',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Net Voli Standar',
                'deskripsi' => 'Net voli standar dengan tinggi 2.43m untuk putra dan 2.24m untuk putri',
                'kategori' => 'Olahraga',
                'kode' => 'NET001',
                'stok' => 6,
                'satuan' => 'Set',
                'foto1' => 'net-voli.jpg',
                'status' => 'tersedia'
            ],

            // Peralatan Laboratorium
            [
                'nama' => 'Mikroskop Digital Olympus',
                'deskripsi' => 'Mikroskop digital Olympus dengan kamera built-in dan layar LCD',
                'kategori' => 'Laboratorium',
                'kode' => 'MIK001',
                'stok' => 8,
                'satuan' => 'Unit',
                'foto1' => 'mikroskop-olympus.jpg',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Tabung Reaksi Pyrex 50ml',
                'deskripsi' => 'Tabung reaksi Pyrex 50ml dengan tutup untuk percobaan kimia',
                'kategori' => 'Laboratorium',
                'kode' => 'TAB001',
                'stok' => 100,
                'satuan' => 'Buah',
                'foto1' => 'tabung-reaksi.jpg',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Timbangan Digital Ohaus',
                'deskripsi' => 'Timbangan digital Ohaus dengan akurasi 0.01g untuk laboratorium',
                'kategori' => 'Laboratorium',
                'kode' => 'TIM001',
                'stok' => 5,
                'satuan' => 'Unit',
                'foto1' => 'timbangan-digital.jpg',
                'status' => 'tersedia'
            ],

            // Peralatan Kantor
            [
                'nama' => 'Printer HP LaserJet Pro',
                'deskripsi' => 'Printer HP LaserJet Pro M404dn dengan print speed 38 ppm',
                'kategori' => 'Kantor',
                'kode' => 'PRI001',
                'stok' => 4,
                'satuan' => 'Unit',
                'foto1' => 'printer-hp.jpg',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Mesin Fotocopy Canon',
                'deskripsi' => 'Mesin fotocopy Canon IR 2525i dengan fitur scan dan print',
                'kategori' => 'Kantor',
                'kode' => 'FOT001',
                'stok' => 2,
                'satuan' => 'Unit',
                'foto1' => 'fotocopy-canon.jpg',
                'status' => 'tersedia'
            ],
            [
                'nama' => 'Shredder Paper Dahle',
                'deskripsi' => 'Mesin penghancur kertas Dahle dengan kapasitas 8 lembar',
                'kategori' => 'Kantor',
                'kode' => 'SHR001',
                'stok' => 3,
                'satuan' => 'Unit',
                'foto1' => 'shredder-dahle.jpg',
                'status' => 'tersedia'
            ]
        ];

        foreach ($barangs as $barang) {
            Barang::create($barang);
        }
    }
}