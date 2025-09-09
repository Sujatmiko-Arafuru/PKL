<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrmawaJurusan;

class OrmawaJurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $akuns = [
            // Jurusan
            [
                'nama' => 'Teknik Informatika',
                'tipe' => 'jurusan',
                'password' => 'password123',
                'email' => 'ti@poltekkes-denpasar.ac.id',
                'no_telp' => '081234567890',
                'alamat' => 'Poltekkes Denpasar',
                'is_active' => true,
            ],
            [
                'nama' => 'Keperawatan',
                'tipe' => 'jurusan',
                'password' => 'password123',
                'email' => 'keperawatan@poltekkes-denpasar.ac.id',
                'no_telp' => '081234567891',
                'alamat' => 'Poltekkes Denpasar',
                'is_active' => true,
            ],
            [
                'nama' => 'Kebidanan',
                'tipe' => 'jurusan',
                'password' => 'password123',
                'email' => 'kebidanan@poltekkes-denpasar.ac.id',
                'no_telp' => '081234567892',
                'alamat' => 'Poltekkes Denpasar',
                'is_active' => true,
            ],
            [
                'nama' => 'Farmasi',
                'tipe' => 'jurusan',
                'password' => 'password123',
                'email' => 'farmasi@poltekkes-denpasar.ac.id',
                'no_telp' => '081234567893',
                'alamat' => 'Poltekkes Denpasar',
                'is_active' => true,
            ],
            
            // Ormawa
            [
                'nama' => 'BEM Poltekkes',
                'tipe' => 'ormawa',
                'password' => 'password123',
                'email' => 'bem@poltekkes-denpasar.ac.id',
                'no_telp' => '081234567894',
                'alamat' => 'Poltekkes Denpasar',
                'is_active' => true,
            ],
            [
                'nama' => 'Himpunan Mahasiswa Teknik Informatika',
                'tipe' => 'ormawa',
                'password' => 'password123',
                'email' => 'hmti@poltekkes-denpasar.ac.id',
                'no_telp' => '081234567895',
                'alamat' => 'Poltekkes Denpasar',
                'is_active' => true,
            ],
            [
                'nama' => 'Himpunan Mahasiswa Keperawatan',
                'tipe' => 'ormawa',
                'password' => 'password123',
                'email' => 'hmk@poltekkes-denpasar.ac.id',
                'no_telp' => '081234567896',
                'alamat' => 'Poltekkes Denpasar',
                'is_active' => true,
            ],
        ];

        foreach ($akuns as $akun) {
            OrmawaJurusan::create($akun);
        }
    }
}