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

    public function adjust(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'admin_notes' => 'nullable|string|max:1000',
            'quantities' => 'nullable|array',
            'quantities.*' => 'nullable|integer|min:0'
        ]);

        try {
            $peminjaman = Peminjaman::with(['details.barang', 'detailsRuangan.ruangan'])->findOrFail($id);
            $action = $request->input('action');
            $adminNotes = $request->input('admin_notes');
            $quantities = $request->input('quantities', []);

            DB::beginTransaction();

            // Update admin notes
            $peminjaman->admin_notes = $adminNotes;

            if ($action === 'approve') {
                // Update quantities if provided
                if (!empty($quantities)) {
                    foreach ($quantities as $detailId => $newQuantity) {
                        $detail = $peminjaman->details->find($detailId);
                        if ($detail && $newQuantity >= 0) {
                            $detail->jumlah = $newQuantity;
                            $detail->save();
                        }
                    }
                }

                // Validate stock after quantity adjustments
                foreach ($peminjaman->details as $detail) {
                    $barang = $detail->barang;
                    if (!$barang) {
                        throw new \Exception('Data barang tidak ditemukan.');
                    }
                    
                    $availableStock = $barang->stok_tersedia;
                    
                    if ($availableStock < $detail->jumlah) {
                        throw new \Exception('Stok barang "' . $barang->nama . '" tidak mencukupi. Stok tersedia: ' . $availableStock . ', diminta: ' . $detail->jumlah);
                    }
                }

                // Validate rooms
                foreach ($peminjaman->detailsRuangan as $detail) {
                    $ruangan = $detail->ruangan;
                    if (!$ruangan) {
                        throw new \Exception('Data ruangan tidak ditemukan.');
                    }
                    
                    if (!$ruangan->bisaDipinjam()) {
                        throw new \Exception('Ruangan "' . $ruangan->nama . '" tidak tersedia untuk dipinjam.');
                    }
                }

                // Update status to approved
                $peminjaman->status = 'disetujui';
                $peminjaman->save();

                // Update stock
                foreach ($peminjaman->details as $detail) {
                    $barang = $detail->barang;
                    $newStok = max(0, $barang->stok - $detail->jumlah);
                    $barang->stok = $newStok;
                    $barang->save();
                }

                // Update room status
                foreach ($peminjaman->detailsRuangan as $detail) {
                    $detail->ruangan->setBorrowed();
                }

                $message = 'Peminjaman berhasil disetujui dengan penyesuaian.';
                if ($adminNotes) {
                    $message .= ' Catatan admin telah disimpan.';
                }
            } else {
                // Reject
                $peminjaman->status = 'ditolak';
                $peminjaman->save();
                $message = 'Peminjaman berhasil ditolak.';
                if ($adminNotes) {
                    $message .= ' Catatan admin telah disimpan.';
                }
            }

            DB::commit();
            return redirect()->route('admin.peminjaman.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saat adjust peminjaman: ' . $e->getMessage(), [
                'peminjaman_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
} 