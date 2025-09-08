<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Barang;
use App\Services\NotificationService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function form(): \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('dashboard')->with('error', 'Keranjang masih kosong.');
        }
        
        // Separate items and rooms from unified cart
        $barangItems = [];
        $ruanganItems = [];
        
        foreach ($cart as $key => $item) {
            if ($item['type'] === 'barang') {
                $barangItems[$key] = $item;
            } elseif ($item['type'] === 'ruangan') {
                $ruanganItems[$key] = $item;
            }
        }
        
        // Hapus session kode peminjaman jika user mulai peminjaman baru
        session()->forget('kode_peminjaman');
        return view('peminjaman_form', compact('barangItems', 'ruanganItems'));
    }

    public function ajukan(Request $request): \Illuminate\Http\RedirectResponse
    {
        // Debug logging
        \Illuminate\Support\Facades\Log::info('Peminjaman ajukan method called', [
            'request_data' => $request->all(),
            'files' => $request->allFiles(),
            'has_foto' => $request->hasFile('foto_peminjam'),
            'has_bukti' => $request->hasFile('bukti'),
            'cart' => session()->get('cart', [])
        ]);
        
        try {
            // Check if foto is uploaded via session
            $fotoPath = session('foto_peminjam_path');
            if (!$fotoPath) {
                return redirect()->route('peminjaman.form')
                    ->with('error', 'Foto peminjam wajib diupload terlebih dahulu. Silakan upload foto menggunakan tombol "Upload Foto" di form.');
            }

            $request->validate([
                'nama' => 'required|string|max:100|min:3',
                'nim_nip' => 'required|string|max:50',
                'unit' => 'required|string|max:100',
                'no_telp' => 'required|string|max:20',
                'nama_kegiatan' => 'required|string|max:255',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                'bukti' => 'required|mimes:pdf,jpg,jpeg,png|max:2048',
            ], [
                'nama.min' => 'Nama harus minimal 3 karakter untuk generate kode unik.',
                'nama.required' => 'Nama wajib diisi.',
                'nama.max' => 'Nama maksimal 100 karakter.',
                'nim_nip.required' => 'NIM/NIP wajib diisi.',
                'nim_nip.max' => 'NIM/NIP maksimal 50 karakter.',
                'bukti.required' => 'Bukti kegiatan wajib diupload.',
                'bukti.mimes' => 'Format bukti harus PDF, JPG, JPEG, atau PNG.',
                'bukti.max' => 'Ukuran bukti maksimal 2MB.',
            ]);
            // Simpan data form
            $formData = $request->except(['bukti']);
            
            // Use foto from session
            $formData['foto_peminjam'] = $fotoPath;
            
            // Handle bukti upload
            if ($request->hasFile('bukti')) {
                $formData['bukti'] = $request->file('bukti')->store('bukti_peminjaman', 'public');
            }
            $cart = session()->get('cart', []);
            
            // Separate and filter items and rooms from unified cart
            $validBarangItems = [];
            $validRuanganItems = [];
            
            foreach ($cart as $key => $item) {
                if ($item['type'] === 'barang') {
                    $barang = \App\Models\Barang::find($item['id']);
                    if ($barang && $barang->stok_tersedia >= $item['qty']) {
                        $validBarangItems[$key] = $item;
                    }
                } elseif ($item['type'] === 'ruangan') {
                    $ruangan = \App\Models\Ruangan::find($item['id']);
                    if ($ruangan && $ruangan->bisaDipinjam()) {
                        $validRuanganItems[$key] = $item;
                    }
                }
            }
            
            // Jika tidak ada item valid di cart
            if (empty($validBarangItems) && empty($validRuanganItems)) {
                session()->forget('cart');
                return redirect()->route('dashboard')->with('error', 'Keranjang kosong atau semua item tidak tersedia.');
            }
            
            $formData['validBarangItems'] = $validBarangItems;
            $formData['validRuanganItems'] = $validRuanganItems;
            // Generate kode peminjaman otomatis dengan format NAMAAWAL-TANGGALMULAIDIPINJAM-000X
            $namaAwal = strtoupper(substr($formData['nama'], 0, 3)); // Ambil 3 huruf pertama nama
            $tanggalMulai = date('Ymd', strtotime($formData['tanggal_mulai']));
            
            // Cari urutan peminjaman terakhir berdasarkan tanggal pengajuan (created_at) secara global
            $lastPeminjaman = \App\Models\Peminjaman::orderBy('created_at', 'desc')->first();
            
            if ($lastPeminjaman) {
                // Extract nomor urut dari kode terakhir (ambil 4 digit terakhir)
                $lastNumber = intval(substr($lastPeminjaman->kode_peminjaman, -4));
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }
            
            $kodePeminjaman = $namaAwal . '-' . $tanggalMulai . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            
            // Pastikan kode peminjaman unik
            while (\App\Models\Peminjaman::where('kode_peminjaman', $kodePeminjaman)->exists()) {
                $nextNumber++;
                $kodePeminjaman = $namaAwal . '-' . $tanggalMulai . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            }
            
            // Mulai database transaction
            DB::beginTransaction();
            
            try {
                // Simpan data peminjaman dengan kode peminjaman
                $peminjaman = \App\Models\Peminjaman::create([
                'nama' => $formData['nama'],
                'nim_nip' => $formData['nim_nip'],
                'foto_peminjam' => $formData['foto_peminjam'],
                'unit' => $formData['unit'],
                'no_telp' => $formData['no_telp'],
                'nama_kegiatan' => $formData['nama_kegiatan'],
                'tanggal_mulai' => $formData['tanggal_mulai'],
                'tanggal_selesai' => $formData['tanggal_selesai'],
                'bukti' => $formData['bukti'],
                'status' => 'menunggu',
                'kode_peminjaman' => $kodePeminjaman,
            ]);
                // Simpan detail barang yang dipinjam
                foreach ($formData['validBarangItems'] as $key => $item) {
                    // Validasi barang masih ada di database
                    $barang = \App\Models\Barang::find($item['id']);
                    if (!$barang) {
                        // Jika barang tidak ditemukan, hapus dari cart dan lanjutkan
                        continue;
                    }
                    
                    // Validasi stok masih mencukupi (gunakan stok tersedia)
                    $availableStock = $barang->stok_tersedia;
                    if ($availableStock < $item['qty']) {
                        return redirect()->back()->withErrors(['stok' => 'Stok barang "' . $barang->nama . '" tidak mencukupi. Stok tersedia: ' . $availableStock]);
                    }
                    
                    try {
                        \App\Models\DetailPeminjaman::create([
                            'peminjaman_id' => $peminjaman->id,
                            'barang_id' => $item['id'],
                            'jumlah' => $item['qty'],
                        ]);
                        
                        // JANGAN kurangi stok barang saat submit request
                        // Stock hanya dikurangi saat admin approve
                    } catch (\Exception $e) {
                        // Jika terjadi error, rollback dan kembalikan error
                        DB::rollback();
                        return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan detail peminjaman: ' . $e->getMessage()]);
                    }
                }
                
                // Simpan detail ruangan yang dipinjam
                foreach ($formData['validRuanganItems'] as $key => $item) {
                    // Validasi ruangan masih ada di database
                    $ruangan = \App\Models\Ruangan::find($item['id']);
                    if (!$ruangan) {
                        // Jika ruangan tidak ditemukan, hapus dari cart dan lanjutkan
                        continue;
                    }
                    
                    // Validasi ruangan masih bisa dipinjam
                    if (!$ruangan->bisaDipinjam()) {
                        return redirect()->back()->withErrors(['ruangan' => 'Ruangan "' . $ruangan->nama . '" tidak tersedia untuk dipinjam. Status: ' . ucfirst($ruangan->status)]);
                    }
                    
                    try {
                        \App\Models\DetailPeminjamanRuangan::create([
                            'peminjaman_id' => $peminjaman->id,
                            'ruangan_id' => $item['id'],
                        ]);
                        
                        // JANGAN ubah status ruangan saat submit request
                        // Status hanya diubah saat admin approve
                    } catch (\Exception $e) {
                        // Jika terjadi error, rollback dan kembalikan error
                        DB::rollback();
                        return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan detail peminjaman ruangan: ' . $e->getMessage()]);
                    }
                }
                
                // Commit transaction jika semua berhasil
                DB::commit();
                
                // Create notification for new peminjaman
                NotificationService::notifyNewPeminjaman($peminjaman);
                
                // Hapus session cart
                session()->forget('cart');
                // Hapus session kode peminjaman agar tidak ditampilkan lagi
                session()->forget('kode_peminjaman');
                return redirect()->route('dashboard')->with('success', 'Peminjaman berhasil diajukan! Kode Peminjaman: ' . $kodePeminjaman);
                
            } catch (\Exception $e) {
                // Rollback transaction jika terjadi error
                DB::rollback();
                return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat mengajukan peminjaman: ' . $e->getMessage()]);
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            \Illuminate\Support\Facades\Log::error('Error submitting peminjaman form: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat mengajukan peminjaman. Silakan coba lagi.']);
        }
    }

    public function ajukanPengembalian(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        // Hapus session kode peminjaman jika user mengajukan pengembalian
        session()->forget('kode_peminjaman');
        
        $peminjaman = \App\Models\Peminjaman::findOrFail($id);
        if ($peminjaman->status !== 'disetujui') {
            return back()->with('error', 'Pengembalian hanya bisa diajukan jika status peminjaman disetujui.');
        }
        $peminjaman->status = 'pengembalian_diajukan';
        $peminjaman->save();
        return back()->with('success', 'Pengajuan pengembalian berhasil, menunggu persetujuan admin.');
    }

    public function cekStatusForm(): \Illuminate\Contracts\View\View
    {
        // Hapus session kode peminjaman jika user melakukan cek status
        session()->forget('kode_peminjaman');
        
        return view('cek_status_form');
    }

    public function cekStatus(Request $request): \Illuminate\Contracts\View\View
    {
        // Hapus session kode peminjaman jika user melakukan cek status
        session()->forget('kode_peminjaman');
        
        $request->validate([
            'kode_peminjaman' => 'required|string',
        ]);
        $peminjaman = \App\Models\Peminjaman::where('kode_peminjaman', $request->kode_peminjaman)
            ->first();
        return view('cek_status_hasil', compact('peminjaman'));
    }

    public function searchByKegiatan(Request $request): \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
    {
        // Hapus session kode peminjaman jika user melakukan pencarian
        session()->forget('kode_peminjaman');
        
        $request->validate([
            'kode_peminjaman' => 'nullable|string',
            'nama_kegiatan' => 'nullable|string',
            'nama_peminjam' => 'nullable|string',
            'no_telp' => 'nullable|string',
        ]);
        
        // Validasi minimal satu field terisi
        if (!$request->filled('kode_peminjaman') && 
            !$request->filled('nama_kegiatan') && 
            !$request->filled('nama_peminjam') && 
            !$request->filled('no_telp')) {
            return back()->withErrors(['search' => 'Minimal isi salah satu field untuk melakukan pencarian.']);
        }
        
        $query = \App\Models\Peminjaman::with('details.barang');
        
        // Filter berdasarkan kode peminjaman
        if ($request->filled('kode_peminjaman')) {
            $query->where('kode_peminjaman', 'like', '%' . $request->kode_peminjaman . '%');
        }
        
        // Filter berdasarkan nama kegiatan
        if ($request->filled('nama_kegiatan')) {
            $query->where('nama_kegiatan', 'like', '%' . $request->nama_kegiatan . '%');
        }
        
        // Filter berdasarkan nama peminjam
        if ($request->filled('nama_peminjam')) {
            $query->where('nama', 'like', '%' . $request->nama_peminjam . '%');
        }
        
        // Filter berdasarkan no telepon
        if ($request->filled('no_telp')) {
            $query->where('no_telp', 'like', '%' . $request->no_telp . '%');
        }
        
        $peminjamans = $query->orderBy('created_at', 'desc')->get();
        return view('cek_status_search_result', compact('peminjamans', 'request'));
    }

    public function detailPeminjaman($id): \Illuminate\Contracts\View\View
    {
        // Hapus session kode peminjaman jika user melihat detail peminjam
        session()->forget('kode_peminjaman');
        
        $peminjaman = \App\Models\Peminjaman::with('details.barang')->findOrFail($id);
        return view('cek_status_detail', compact('peminjaman'));
    }

    public function listPeminjam(Request $request): \Illuminate\Contracts\View\View
    {
        // Hapus session kode peminjaman jika user melihat list peminjam
        session()->forget('kode_peminjaman');
        
        $query = \App\Models\Peminjaman::with('details.barang');
        
        // Filter berdasarkan status jika ada
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan kode peminjaman jika ada
        if ($request->filled('kode_peminjaman')) {
            $query->where('kode_peminjaman', 'like', '%' . $request->kode_peminjaman . '%');
        }
        
        // Filter berdasarkan nama kegiatan jika ada
        if ($request->filled('nama_kegiatan')) {
            $query->where('nama_kegiatan', 'like', '%' . $request->nama_kegiatan . '%');
        }
        
        // Urutkan berdasarkan tanggal terbaru - tanpa pagination
        $peminjamans = $query->orderBy('created_at', 'desc')->get();
        
        return view('list_peminjam', compact('peminjamans'));
    }

    public function detailPeminjamPublic($id): \Illuminate\Contracts\View\View
    {
        // Hapus session kode peminjaman jika user melihat detail peminjam public
        session()->forget('kode_peminjaman');
        
        $peminjaman = \App\Models\Peminjaman::with('details.barang')->findOrFail($id);
        return view('list_peminjam_detail', compact('peminjaman'));
    }

    public function getDetailPeminjamApi($id): \Illuminate\Http\JsonResponse
    {
        $peminjaman = \App\Models\Peminjaman::with(['details.barang', 'detailsRuangan.ruangan'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $peminjaman->id,
                'kode_peminjaman' => $peminjaman->kode_peminjaman,
                'nama' => $peminjaman->nama,
                'nim_nip' => $peminjaman->nim_nip,
                'unit' => $peminjaman->unit,
                'no_telp' => $peminjaman->no_telp,
                'nama_kegiatan' => $peminjaman->nama_kegiatan,
                'tanggal_mulai' => $peminjaman->tanggal_mulai,
                'tanggal_selesai' => $peminjaman->tanggal_selesai,
                'status' => $peminjaman->status,
                'admin_notes' => $peminjaman->admin_notes,
                'foto_peminjam' => $peminjaman->foto_peminjam ? asset('storage/' . $peminjaman->foto_peminjam) : null,
                'bukti' => $peminjaman->bukti ? asset('storage/' . $peminjaman->bukti) : null,
                'created_at' => $peminjaman->created_at->toISOString(),
                'updated_at' => $peminjaman->updated_at->toISOString(),
                'details' => $peminjaman->details->map(function($detail) {
                    return [
                        'id' => $detail->id,
                        'barang_id' => $detail->barang_id,
                        'jumlah' => $detail->jumlah,
                        'jumlah_dikembalikan' => $detail->jumlah_dikembalikan,
                        'sisa_belum_dikembalikan' => $detail->sisa_belum_dikembalikan,
                        'barang' => [
                            'id' => $detail->barang->id,
                            'nama' => $detail->barang->nama,
                            'kode' => 'BRG-' . str_pad($detail->barang->id, 4, '0', STR_PAD_LEFT),
                            'kategori' => 'Barang',
                            'stok' => $detail->barang->stok,
                            'satuan' => 'Unit',
                        ]
                    ];
                }),
                'detailsRuangan' => $peminjaman->detailsRuangan->map(function($detail) {
                    return [
                        'id' => $detail->id,
                        'ruangan_id' => $detail->ruangan_id,
                        'sudah_dikembalikan' => $detail->sudah_dikembalikan,
                        'tanggal_dikembalikan' => $detail->tanggal_dikembalikan,
                        'ruangan' => [
                            'id' => $detail->ruangan->id,
                            'nama' => $detail->ruangan->nama,
                            'kode' => $detail->ruangan->kode,
                            'lokasi' => $detail->ruangan->lokasi,
                            'status' => $detail->ruangan->status,
                        ]
                    ];
                })
            ]
        ]);
    }
} 