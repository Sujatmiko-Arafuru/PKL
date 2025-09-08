<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PeminjamanController extends Controller
{
    public function index(Request $request): \Illuminate\View\View
    {
        $query = Peminjaman::query();
        
        // Filter berdasarkan search
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('no_telp', 'like', '%' . $request->search . '%')
                  ->orWhere('nama_kegiatan', 'like', '%' . $request->search . '%');
        }
        
        // Filter berdasarkan status
        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }
        
        // Sorting
        if ($request->filled('urut')) {
            $query->orderBy('created_at', $request->urut == 'terbaru' ? 'desc' : 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        // Dengan pagination - tampilkan 12 data per halaman
        $peminjamans = $query->with(['details.barang', 'detailsRuangan.ruangan'])->paginate(12);
        
        // Data untuk tabel terpisah (tanpa pagination)
        $menunggu = Peminjaman::where('status', 'menunggu')->orderBy('created_at', 'desc')->get();
        $sedang_berlangsung = Peminjaman::where('status', 'disetujui')->orderBy('created_at', 'desc')->get();
        
        return view('admin.peminjaman.index', compact('peminjamans', 'menunggu', 'sedang_berlangsung'));
    }

    public function show($id): \Illuminate\View\View
    {
        $peminjaman = Peminjaman::with(['details.barang', 'detailsRuangan.ruangan'])->findOrFail($id);
        return view('admin.peminjaman.show', compact('peminjaman'));
    }

    public function approve($id): \Illuminate\Http\RedirectResponse
    {
        try {
            $peminjaman = Peminjaman::with(['details.barang', 'detailsRuangan.ruangan'])->findOrFail($id);
            $oldStatus = $peminjaman->status;
            
            // Validasi stok barang sebelum approve
            foreach ($peminjaman->details as $detail) {
                $barang = $detail->barang;
                if (!$barang) {
                    return redirect()->route('admin.peminjaman.index')->with('error', 'Data barang tidak ditemukan.');
                }
                
                $availableStock = $barang->stok_tersedia;
                
                if ($availableStock < $detail->jumlah) {
                    return redirect()->route('admin.peminjaman.index')->with('error', 'Stok barang "' . $barang->nama . '" tidak mencukupi untuk approve peminjaman ini. Stok tersedia: ' . $availableStock . ', diminta: ' . $detail->jumlah);
                }
            }
            
            // Validasi ruangan sebelum approve
            foreach ($peminjaman->detailsRuangan as $detail) {
                $ruangan = $detail->ruangan;
                if (!$ruangan) {
                    return redirect()->route('admin.peminjaman.index')->with('error', 'Data ruangan tidak ditemukan.');
                }
                
                if (!$ruangan->bisaDipinjam()) {
                    return redirect()->route('admin.peminjaman.index')->with('error', 'Ruangan "' . $ruangan->nama . '" tidak tersedia untuk dipinjam. Status: ' . ucfirst($ruangan->status));
                }
            }
            
            // Mulai transaction
            DB::beginTransaction();
            
            // Update status peminjaman
            $peminjaman->status = 'disetujui';
            $peminjaman->saveQuietly(); // Gunakan saveQuietly untuk performa
            
            // Batch update stok barang untuk optimasi
            $barangUpdates = [];
            foreach ($peminjaman->details as $detail) {
                $barang = $detail->barang;
                $newStok = max(0, $barang->stok - $detail->jumlah);
                $barangUpdates[] = [
                    'id' => $barang->id,
                    'stok' => $newStok
                ];
            }
            
            // Update stok dalam batch untuk efisiensi
            foreach ($barangUpdates as $update) {
                DB::table('barangs')
                    ->where('id', $update['id'])
                    ->update(['stok' => $update['stok']]);
                    
                // Update status secara manual untuk barang yang diupdate
                $barang = \App\Models\Barang::find($update['id']);
                if ($barang) {
                    $barang->updateStatusOtomatis();
                }
            }
            
            // Update status ruangan menjadi dipinjam
            foreach ($peminjaman->detailsRuangan as $detail) {
                $ruangan = $detail->ruangan;
                $ruangan->setBorrowed();
            }
            
            DB::commit();
            
            // Create notification for status change
            NotificationService::notifyPeminjamanStatusChange($peminjaman, $oldStatus, 'disetujui');
            
            $message = 'Peminjaman disetujui dan stok barang berhasil diupdate.';
            if ($peminjaman->detailsRuangan->count() > 0) {
                $message .= ' Status ruangan telah diupdate menjadi dipinjam.';
            }
            
            return redirect()->route('admin.peminjaman.index')->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saat approve peminjaman: ' . $e->getMessage(), [
                'peminjaman_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('admin.peminjaman.index')->with('error', 'Terjadi kesalahan saat approve peminjaman. Silakan coba lagi.');
        }
    }

    public function reject($id): \Illuminate\Http\RedirectResponse
    {
        try {
            $peminjaman = Peminjaman::with(['detailsRuangan.ruangan'])->findOrFail($id);
            $oldStatus = $peminjaman->status;
            
            DB::beginTransaction();
            
            $peminjaman->status = 'ditolak';
            $peminjaman->saveQuietly();
            
            // Jika ada ruangan yang sudah diubah statusnya menjadi dipinjam saat approve sebelumnya,
            // kembalikan statusnya menjadi tersedia saat reject
            foreach ($peminjaman->detailsRuangan as $detail) {
                $ruangan = $detail->ruangan;
                if ($ruangan && $ruangan->status === 'dipinjam') {
                    $ruangan->setAvailable();
                }
            }
            
            DB::commit();
            
            // Create notification for status change
            NotificationService::notifyPeminjamanStatusChange($peminjaman, $oldStatus, 'ditolak');
            
            return redirect()->route('admin.peminjaman.index')->with('success', 'Peminjaman berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saat reject peminjaman: ' . $e->getMessage(), [
                'peminjaman_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('admin.peminjaman.index')->with('error', 'Terjadi kesalahan saat menolak peminjaman. Silakan coba lagi.');
        }
    }
} 