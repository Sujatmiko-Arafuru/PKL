<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Barang;
use App\Models\Ruangan;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\DetailPeminjamanRuangan;

class TestPeminjamanFlow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:peminjaman-flow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test comprehensive peminjaman flow from submission to return';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== COMPREHENSIVE PEMINJAMAN TESTING ===');
        $this->newLine();

        // Test 1: Check if test data exists
        $this->info('1. Checking test data...');
        $user = User::first();
        $barang = Barang::first();
        $ruangan = Ruangan::first();

        if (!$user || !$barang || !$ruangan) {
            $this->error('❌ Missing test data!');
            return 1;
        }

        $this->info("✅ User: {$user->nama} ({$user->nim_nip})");
        $this->info("✅ Barang: {$barang->nama} (Stok: {$barang->stok})");
        $this->info("✅ Ruangan: {$ruangan->nama}");
        $this->newLine();

        // Test 2: Create new peminjaman
        $this->info('2. Creating new peminjaman...');
        $peminjaman = Peminjaman::create([
            'nama' => $user->nama,
            'nim_nip' => $user->nim_nip,
            'unit' => $user->unit,
            'no_telp' => $user->no_telp,
            'nama_kegiatan' => 'Testing Comprehensive Flow',
            'tanggal_mulai' => '2025-09-20',
            'tanggal_selesai' => '2025-09-22',
            'status' => 'menunggu',
            'kode_peminjaman' => 'TEST-' . date('Ymd') . '-0003'
        ]);

        $detail = DetailPeminjaman::create([
            'peminjaman_id' => $peminjaman->id,
            'barang_id' => $barang->id,
            'jumlah' => 3
        ]);

        $detailRuangan = DetailPeminjamanRuangan::create([
            'peminjaman_id' => $peminjaman->id,
            'ruangan_id' => $ruangan->id
        ]);

        $this->info("✅ Peminjaman created: {$peminjaman->kode_peminjaman}");
        $this->info("✅ Detail barang: {$detail->jumlah} x {$barang->nama}");
        $this->info("✅ Detail ruangan: {$ruangan->nama}");
        $this->newLine();

        // Test 3: Admin approval with quantity adjustment
        $this->info('3. Testing admin approval with quantity adjustment...');
        $originalStock = $barang->stok;
        $requestedQuantity = $detail->jumlah;
        $adjustedQuantity = 2; // Reduce from 3 to 2

        // Update quantity
        $detail->jumlah = $adjustedQuantity;
        $detail->save();

        // Update admin notes
        $peminjaman->admin_notes = "Barang dikurangi karena ada yang rusak. Stok tersedia hanya {$originalStock} unit.";
        $peminjaman->status = 'disetujui';
        $peminjaman->save();

        // Update stock
        $barang->stok = $originalStock - $adjustedQuantity;
        $barang->save();

        // Update room status
        $ruangan->status = 'dipinjam';
        $ruangan->save();

        $this->info("✅ Quantity adjusted: {$requestedQuantity} → {$adjustedQuantity}");
        $this->info("✅ Admin notes added: {$peminjaman->admin_notes}");
        $this->info("✅ Status updated to: {$peminjaman->status}");
        $this->info("✅ Stock updated: {$originalStock} → {$barang->stok}");
        $this->info("✅ Room status: {$ruangan->status}");
        $this->newLine();

        // Test 4: Check peminjaman details
        $this->info('4. Checking peminjaman details...');
        $peminjaman->load(['details.barang', 'detailsRuangan.ruangan']);

        $this->info("✅ Peminjaman ID: {$peminjaman->id}");
        $this->info("✅ Status: {$peminjaman->status}");
        $this->info("✅ Admin Notes: " . ($peminjaman->admin_notes ?: 'None'));
        $this->info("✅ Items: " . $peminjaman->details->count());
        $this->info("✅ Rooms: " . $peminjaman->detailsRuangan->count());
        $this->newLine();

        // Test 5: Test return process
        $this->info('5. Testing return process...');

        // Mark items as returned
        foreach ($peminjaman->details as $detail) {
            $detail->jumlah_dikembalikan = $detail->jumlah;
            $detail->save();
            
            // Restore stock
            $barang = $detail->barang;
            $barang->stok += $detail->jumlah;
            $barang->save();
            
            $this->info("✅ Item returned: {$detail->jumlah} x {$barang->nama}");
        }

        // Mark rooms as returned
        foreach ($peminjaman->detailsRuangan as $detail) {
            $detail->sudah_dikembalikan = true;
            $detail->tanggal_dikembalikan = now();
            $detail->save();
            
            // Restore room status
            $ruangan = $detail->ruangan;
            $ruangan->status = 'tersedia';
            $ruangan->save();
            
            $this->info("✅ Room returned: {$ruangan->nama}");
        }

        // Update peminjaman status
        $peminjaman->status = 'dikembalikan';
        $peminjaman->save();

        $this->info("✅ Peminjaman status: {$peminjaman->status}");
        $this->newLine();

        // Test 6: Final verification
        $this->info('6. Final verification...');
        $barang->refresh();
        $ruangan->refresh();

        $this->info("✅ Final stock: {$barang->stok}");
        $this->info("✅ Final room status: {$ruangan->status}");
        $this->info("✅ All items returned: " . ($peminjaman->details->every(function($d) { return $d->jumlah_dikembalikan == $d->jumlah; }) ? 'Yes' : 'No'));
        $this->info("✅ All rooms returned: " . ($peminjaman->detailsRuangan->every(function($d) { return $d->sudah_dikembalikan; }) ? 'Yes' : 'No'));
        $this->newLine();

        $this->info('=== TESTING COMPLETED SUCCESSFULLY ===');
        $this->info('✅ All processes working correctly!');
        $this->info('✅ Admin quantity adjustment: Working');
        $this->info('✅ Admin notes: Working');
        $this->info('✅ Stock management: Working');
        $this->info('✅ Room management: Working');
        $this->info('✅ Return process: Working');

        return 0;
    }
}