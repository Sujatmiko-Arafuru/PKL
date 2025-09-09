<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Barang;
use App\Models\Ruangan;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\DetailPeminjamanRuangan;

class TestPeminjamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get test data
        $user = User::first();
        $barang = Barang::first();
        $ruangan = Ruangan::first();

        if (!$user || !$barang || !$ruangan) {
            $this->command->error('Missing test data. Please run ComprehensiveTestSeeder first.');
            return;
        }

        // Create test peminjaman
        $peminjaman = Peminjaman::create([
            'nama' => $user->nama,
            'nim_nip' => $user->nim_nip,
            'unit' => $user->unit,
            'no_telp' => $user->no_telp,
            'nama_kegiatan' => 'Seminar Teknologi',
            'tanggal_mulai' => '2025-09-15',
            'tanggal_selesai' => '2025-09-17',
            'status' => 'menunggu',
            'kode_peminjaman' => 'TEST-' . date('Ymd') . '-0001'
        ]);

        // Create detail peminjaman barang
        DetailPeminjaman::create([
            'peminjaman_id' => $peminjaman->id,
            'barang_id' => $barang->id,
            'jumlah' => 2
        ]);

        // Create detail peminjaman ruangan
        DetailPeminjamanRuangan::create([
            'peminjaman_id' => $peminjaman->id,
            'ruangan_id' => $ruangan->id
        ]);

        $this->command->info('Test peminjaman created: ' . $peminjaman->kode_peminjaman);
        $this->command->info('Peminjaman ID: ' . $peminjaman->id);
    }
}
