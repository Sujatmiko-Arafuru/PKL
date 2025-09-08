<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PengembalianController extends Controller
{
    public function index()
    {
        $peminjamans = Peminjaman::with(['details.barang', 'detailsRuangan.ruangan'])
            ->whereIn('status', ['disetujui', 'dipinjam', 'proses_pengembalian'])
            ->where(function($query) {
                $query->whereHas('details', function($q) {
                    $q->whereRaw('jumlah_dikembalikan < jumlah');
                })->orWhereHas('detailsRuangan');
            })->orderBy('created_at', 'desc')->get();

        return view('admin.pengembalian.index', compact('peminjamans'));
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['details.barang', 'detailsRuangan.ruangan'])->findOrFail($id);
        
        // Cek apakah status sudah dikembalikan
        if ($peminjaman->status === 'dikembalikan') {
            return redirect()->route('admin.pengembalian.index')->with('info', 'Peminjaman ini sudah selesai dikembalikan.');
        }
        
        // Cek apakah masih ada barang atau ruangan yang bisa dikembalikan
        $hasUnreturnedItems = $peminjaman->total_belum_dikembalikan > 0;
        $hasUnreturnedRooms = $peminjaman->detailsRuangan->count() > 0;
        
        if (!$hasUnreturnedItems && !$hasUnreturnedRooms) {
            return redirect()->route('admin.pengembalian.index')->with('info', 'Semua item untuk peminjaman ini sudah dikembalikan.');
        }
        
        return view('admin.pengembalian.show', compact('peminjaman'));
    }

    public function inputKodePengembalian(Request $request)
    {
        $request->validate([
            'kode_peminjaman' => 'nullable|string',
            'nama' => 'nullable|string',
            'nama_kegiatan' => 'nullable|string',
            'no_telp' => 'nullable|string',
        ]);

        $query = Peminjaman::with(['details.barang']);

        // Filter berdasarkan input yang diberikan
        if ($request->filled('kode_peminjaman')) {
            $query->where('kode_peminjaman', 'like', '%' . $request->kode_peminjaman . '%');
        }
        
        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }
        
        if ($request->filled('nama_kegiatan')) {
            $query->where('nama_kegiatan', 'like', '%' . $request->nama_kegiatan . '%');
        }
        
        if ($request->filled('no_telp')) {
            $query->where('no_telp', 'like', '%' . $request->no_telp . '%');
        }

        $peminjamans = $query->whereIn('status', ['disetujui', 'dipinjam', 'proses_pengembalian'])
            ->whereHas('details', function($subQuery) {
                $subQuery->whereRaw('jumlah_dikembalikan < jumlah');
            })->get();

        if ($peminjamans->isEmpty()) {
            return back()->with('error', 'Tidak ada data peminjaman yang ditemukan.');
        }

        if ($peminjamans->count() == 1) {
            return redirect()->route('admin.pengembalian.show', $peminjamans->first()->id);
        }

        return view('admin.pengembalian.search_result', compact('peminjamans'));
    }

    /**
     * Update detail pengembalian untuk semua detail peminjaman
     */
    public function bulkUpdatePengembalian(Request $request, $id)
    {
        $request->validate([
            'details' => 'required|array',
            'details.*.id' => 'required|exists:detail_peminjamans,id',
            'details.*.jumlah_dikembalikan' => 'required|integer|min:0',
        ]);

        $peminjaman = Peminjaman::with(['details.barang'])->findOrFail($id);
        
        // Cek apakah masih ada barang yang bisa dikembalikan
        if ($peminjaman->total_belum_dikembalikan <= 0) {
            return redirect()->route('admin.pengembalian.index')->with('info', 'Semua barang untuk peminjaman ini sudah dikembalikan.');
        }

        DB::beginTransaction();
        try {
            $totalStokDikembalikan = 0;
            
            foreach ($request->details as $detailData) {
                $detail = DetailPeminjaman::with('barang')->find($detailData['id']);
                
                if ($detail && $detail->peminjaman_id == $peminjaman->id) {
                    $tambahan = (int) $detailData['jumlah_dikembalikan'];
                    $sisa = max(0, $detail->jumlah - $detail->jumlah_dikembalikan);
                    
                    if ($tambahan < 0) {
                        throw new \Exception("Jumlah tambahan pengembalian untuk {$detail->barang->nama} tidak boleh kurang dari 0.");
                    }
                    if ($tambahan > $sisa) {
                        throw new \Exception("Jumlah tambahan pengembalian untuk {$detail->barang->nama} tidak boleh melebihi sisa yang belum dikembalikan ({$sisa}).");
                    }
                    
                    if ($tambahan > 0) {
                        $detail->jumlah_dikembalikan += $tambahan;
                        $detail->save();
                        
                        $barang = $detail->barang;
                        $barang->stok += $tambahan;
                        $barang->save();
                        
                        $totalStokDikembalikan += $tambahan;
                    }
                }
            }

            // Update status peminjaman berdasarkan jumlah yang dikembalikan
            $this->updatePeminjamanStatus($peminjaman);
            
            DB::commit();
            
            $message = 'Pengembalian berhasil diupdate. ';
            if ($totalStokDikembalikan > 0) {
                $message .= "Stok barang telah diupdate (+{$totalStokDikembalikan}). ";
            }
            
            if ($peminjaman->status === 'proses_pengembalian') {
                $message .= 'Status berubah menjadi "Proses Pengembalian".';
            } elseif ($peminjaman->status === 'dikembalikan') {
                $message .= 'Status berubah menjadi "Dikembalikan".';
            }
            
            // Jika semua barang sudah dikembalikan, redirect ke index
            if ($peminjaman->status === 'dikembalikan') {
                return redirect()->route('admin.pengembalian.index')->with('success', $message);
            }
            
            return back()->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Handle room return
     */
    public function returnRoom(Request $request, $id)
    {
        $request->validate([
            'ruangan_id' => 'required|integer|exists:ruangans,id'
        ]);

        $peminjaman = Peminjaman::with(['detailsRuangan.ruangan'])->findOrFail($id);
        $ruanganId = $request->ruangan_id;

        // Find the room detail for this loan
        $roomDetail = $peminjaman->detailsRuangan->where('ruangan_id', $ruanganId)->first();
        
        if (!$roomDetail) {
            return back()->with('error', 'Ruangan tidak ditemukan dalam peminjaman ini.');
        }

        DB::beginTransaction();
        try {
            // Update room status to available
            $roomDetail->ruangan->setAvailable();
            
            // Mark room as returned instead of deleting the record
            $roomDetail->update([
                'sudah_dikembalikan' => true,
                'tanggal_dikembalikan' => now()
            ]);
            
            // Update loan status
            $this->updatePeminjamanStatus($peminjaman);
            
            DB::commit();
            
            return back()->with('success', 'Ruangan "' . $roomDetail->ruangan->nama . '" berhasil dikembalikan dan status diupdate menjadi tersedia.');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update status peminjaman berdasarkan jumlah barang yang dikembalikan
     */
    private function updatePeminjamanStatus(Peminjaman $peminjaman)
    {
        // Hitung ulang dari database untuk memastikan angka terbaru
        $totalBarang = \App\Models\DetailPeminjaman::where('peminjaman_id', $peminjaman->id)
            ->sum('jumlah');
        $totalDikembalikan = \App\Models\DetailPeminjaman::where('peminjaman_id', $peminjaman->id)
            ->sum('jumlah_dikembalikan');
        
        // Check if there are any rooms still borrowed (not returned)
        $hasRooms = \App\Models\DetailPeminjamanRuangan::where('peminjaman_id', $peminjaman->id)
            ->where('sudah_dikembalikan', false)
            ->exists();

        if ($totalDikembalikan == 0 && !$hasRooms) {
            // Jika belum ada yang dikembalikan dan tidak ada ruangan, status tetap seperti semula
            if ($peminjaman->status === 'disetujui') {
                $peminjaman->status = 'dipinjam';
            }
        } elseif ($totalDikembalikan < $totalBarang || $hasRooms) {
            // Jika sebagian dikembalikan atau masih ada ruangan, status menjadi proses pengembalian
            $peminjaman->status = 'proses_pengembalian';
        } else {
            // Jika semua dikembalikan dan tidak ada ruangan, status menjadi dikembalikan
            $peminjaman->status = 'dikembalikan';
            
            // Jika status menjadi dikembalikan, pastikan semua ruangan yang terkait juga dikembalikan
            $peminjaman->load('detailsRuangan.ruangan');
            foreach ($peminjaman->detailsRuangan as $detail) {
                if ($detail->ruangan && $detail->ruangan->status === 'dipinjam' && !$detail->sudah_dikembalikan) {
                    $detail->ruangan->setAvailable();
                    $detail->update([
                        'sudah_dikembalikan' => true,
                        'tanggal_dikembalikan' => now()
                    ]);
                }
            }
        }

        $peminjaman->save();
    }

    /**
     * Get peminjaman yang bisa dikembalikan untuk API
     */
    public function getPeminjamanForReturn()
    {
        $peminjamans = Peminjaman::with(['details.barang'])
            ->whereIn('status', ['disetujui', 'dipinjam', 'proses_pengembalian'])
            ->whereHas('details', function($query) {
                $query->whereRaw('jumlah_dikembalikan < jumlah');
            })->get();

        return response()->json([
            'success' => true,
            'data' => $peminjamans
        ]);
    }

    /**
     * Get detail pengembalian untuk peminjaman tertentu
     */
    public function getDetailPengembalian($id)
    {
        $peminjaman = Peminjaman::with(['details.barang'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $peminjaman
        ]);
    }

    /**
     * Update status pengembalian untuk item tertentu
     */
    public function updateItemReturnStatus(Request $request, $id)
    {
        $request->validate([
            'detail_id' => 'required|exists:detail_peminjamans,id',
            'jumlah_dikembalikan' => 'required|integer|min:0',
        ]);

        $detail = DetailPeminjaman::with(['peminjaman', 'barang'])->findOrFail($request->detail_id);
        
        if ($detail->peminjaman_id != $id) {
            return back()->with('error', 'Detail tidak sesuai dengan peminjaman.');
        }

        $jumlahSebelumnya = $detail->jumlah_dikembalikan;
        $jumlahBaru = (int) $request->jumlah_dikembalikan;
        
        if ($jumlahBaru > $detail->jumlah) {
            return back()->with('error', 'Jumlah pengembalian tidak boleh melebihi jumlah yang dipinjam.');
        }

        DB::beginTransaction();
        try {
            // Update jumlah dikembalikan
            $detail->jumlah_dikembalikan = $jumlahBaru;
            $detail->save();

            // Update stok barang
            $selisih = $jumlahBaru - $jumlahSebelumnya;
            if ($selisih != 0) {
                $detail->barang->stok += $selisih;
                $detail->barang->save();
            }

            // Update status peminjaman
            $this->updatePeminjamanStatus($detail->peminjaman);
            
            DB::commit();
            
            $message = 'Status pengembalian berhasil diupdate.';
            if ($selisih > 0) {
                $message .= " Stok barang telah diupdate (+{$selisih}).";
            }
            
            return back()->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
} 