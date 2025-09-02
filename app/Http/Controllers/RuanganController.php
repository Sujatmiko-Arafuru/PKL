<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index()
    {
        $ruangans = Ruangan::where('status', 'tersedia')
            ->orderBy('nama', 'asc')
            ->paginate(12);
        
        return view('ruangan.index', compact('ruangans'));
    }

    public function show(Ruangan $ruangan)
    {
        return view('ruangan.show', compact('ruangan'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $ruangans = Ruangan::where('status', 'tersedia')
            ->where(function($q) use ($query) {
                $q->where('nama', 'like', "%{$query}%")
                  ->orWhere('deskripsi', 'like', "%{$query}%")
                  ->orWhere('kategori', 'like', "%{$query}%")
                  ->orWhere('lokasi', 'like', "%{$query}%");
            })
            ->orderBy('nama', 'asc')
            ->paginate(12);
        
        return view('ruangan.index', compact('ruangans', 'query'));
    }
}
