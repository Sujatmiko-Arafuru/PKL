<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Peminjaman;
use App\Services\NotificationService;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat notifikasi untuk semua peminjaman yang menunggu approve
        $peminjamansMenunggu = Peminjaman::where('status', 'menunggu')->get();
        
        foreach ($peminjamansMenunggu as $peminjaman) {
            NotificationService::notifyNewPeminjaman($peminjaman);
        }
        
        $this->command->info("Berhasil membuat " . $peminjamansMenunggu->count() . " notifikasi untuk peminjaman yang menunggu approve.");
    }
}
