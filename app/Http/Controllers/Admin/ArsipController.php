<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Barang;

class ArsipController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['details.barang', 'detailsRuangan.ruangan']);

        // Filter berdasarkan kode peminjaman
        if ($request->filled('kode_peminjaman')) {
            $query->where('kode_peminjaman', 'like', '%' . $request->kode_peminjaman . '%');
        }

        // Filter berdasarkan nama peminjam
        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        // Filter berdasarkan bulan kegiatan (tanggal_mulai)
        if ($request->filled('bulan')) {
            $bulan = $request->bulan;
            $query->whereMonth('tanggal_mulai', $bulan);
        }

        // Urutan berdasarkan tanggal pengajuan (terbaru)
        $query->orderBy('created_at', 'desc');

        $peminjamans = $query->with(['details.barang', 'detailsRuangan.ruangan'])->paginate(10);

        // Data untuk summary - perbaiki relationship yang salah
        $terlaris = Barang::withCount(['peminjamanDetails' => function($query) {
            $query->whereHas('peminjaman', function($q) {
                $q->where('status', 'dikembalikan');
            });
        }])->orderBy('peminjaman_details_count', 'desc')->first();

        $tidakPernah = Barang::whereDoesntHave('peminjamanDetails.peminjaman', function($query) {
            $query->where('status', 'dikembalikan');
        })->get();

        return view('admin.arsip.index', compact('peminjamans', 'terlaris', 'tidakPernah'));
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['details.barang', 'detailsRuangan.ruangan'])->findOrFail($id);
        return view('admin.arsip.show', compact('peminjaman'));
    }

    public function exportPdf(Request $request)
    {
        $query = Peminjaman::with(['details.barang', 'detailsRuangan.ruangan']);

        // Filter berdasarkan kode peminjaman
        if ($request->filled('kode_peminjaman')) {
            $query->where('kode_peminjaman', 'like', '%' . $request->kode_peminjaman . '%');
        }

        // Filter berdasarkan nama peminjam
        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        // Filter berdasarkan bulan kegiatan (tanggal_mulai)
        if ($request->filled('bulan')) {
            $bulan = $request->bulan;
            $query->whereMonth('tanggal_mulai', $bulan);
        }

        // Urutan berdasarkan tanggal pengajuan (terbaru)
        $query->orderBy('created_at', 'desc');

        $peminjamans = $query->get();

        // Filter info untuk ditampilkan di PDF
        $filterInfo = [];
        if ($request->filled('kode_peminjaman')) $filterInfo['kode_peminjaman'] = $request->kode_peminjaman;
        if ($request->filled('nama')) $filterInfo['nama'] = $request->nama;
        if ($request->filled('bulan')) {
            $bulanNames = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            $filterInfo['bulan'] = $bulanNames[$request->bulan];
        }

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.arsip.pdf', compact('peminjamans', 'filterInfo'));
        
        $filename = 'arsip_peminjaman_' . date('Y-m-d_H-i-s') . '.pdf';
        
        return $pdf->download($filename);
    }

    public function exportPeminjamanPdf($id)
    {
        $peminjaman = Peminjaman::with(['details.barang', 'detailsRuangan.ruangan'])->findOrFail($id);
        
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.arsip.peminjaman-detail-pdf', compact('peminjaman'));
        
        $filename = 'detail_peminjaman_' . $peminjaman->kode_peminjaman . '_' . date('Y-m-d_H-i-s') . '.pdf';
        
        return $pdf->download($filename);
    }
} 