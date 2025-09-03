<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InventarisRuanganController extends Controller
{
    public function index(Request $request)
    {
        $query = Ruangan::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        
        $ruangans = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Get statistics
        $totalRuangan = Ruangan::count();
        $ruanganTersedia = Ruangan::where('status', 'tersedia')->count();
        
        // Get unique categories for filter
        $kategoris = Ruangan::whereNotNull('kategori')->distinct()->pluck('kategori');
        
        return view('admin.inventaris-ruangan.index', compact(
            'ruangans', 'totalRuangan', 'ruanganTersedia', 'kategoris'
        ));
    }

    public function create()
    {
        return view('admin.inventaris-ruangan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kode' => 'nullable|string|max:50',
            'kategori' => 'nullable|string|max:100',
            'lantai' => 'nullable|string|max:50',
            'lokasi' => 'nullable|string|max:255',
            'fasilitas' => 'nullable|string',
            'status' => 'required|in:tersedia,maintenance,dipinjam,tidak tersedia',
            'foto1' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto2' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto3' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only([
            'nama', 'deskripsi', 'kode', 'kategori', 
            'lantai', 'lokasi', 'fasilitas', 'status'
        ]);

        // Handle photo uploads
        for ($i = 1; $i <= 3; $i++) {
            if ($request->hasFile("foto{$i}")) {
                $file = $request->file("foto{$i}");
                $filename = 'ruangan_' . time() . '_' . $i . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('ruangan-photos', $filename, 'public');
                $data["foto{$i}"] = $path;
            }
        }

        Ruangan::create($data);

        return redirect()->route('admin.inventaris-ruangan.index')
            ->with('success', 'Ruangan berhasil ditambahkan!');
    }

    public function show($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        return view('admin.inventaris-ruangan.show', compact('ruangan'));
    }

    public function edit($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        return view('admin.inventaris-ruangan.edit', compact('ruangan'));
    }

    public function update(Request $request, $id)
    {
        $ruangan = Ruangan::findOrFail($id);
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kode' => 'nullable|string|max:50',
            'kategori' => 'nullable|string|max:100',
            'lantai' => 'nullable|string|max:50',
            'lokasi' => 'nullable|string|max:255',
            'fasilitas' => 'nullable|string',
            'status' => 'required|in:tersedia,maintenance,dipinjam,tidak tersedia',
            'foto1' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto2' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto3' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only([
            'nama', 'deskripsi', 'kode', 'kategori', 
            'lantai', 'lokasi', 'fasilitas', 'status'
        ]);

        // Handle photo uploads
        for ($i = 1; $i <= 3; $i++) {
            if ($request->hasFile("foto{$i}")) {
                // Delete old photo if exists
                if ($ruangan->{"foto{$i}"}) {
                    Storage::disk('public')->delete($ruangan->{"foto{$i}"});
                }
                
                $file = $request->file("foto{$i}");
                $filename = 'ruangan_' . time() . '_' . $i . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('ruangan-photos', $filename, 'public');
                $data["foto{$i}"] = $path;
            }
        }

        $ruangan->update($data);

        return redirect()->route('admin.inventaris-ruangan.index')
            ->with('success', 'Ruangan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $ruangan = Ruangan::findOrFail($id);
        
        // Delete photos from storage
        for ($i = 1; $i <= 3; $i++) {
            if ($ruangan->{"foto{$i}"}) {
                Storage::disk('public')->delete($ruangan->{"foto{$i}"});
            }
        }

        $ruangan->delete();

        return redirect()->route('admin.inventaris-ruangan.index')
            ->with('success', 'Ruangan berhasil dihapus!');
    }
}
