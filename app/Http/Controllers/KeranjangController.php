<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Ruangan;

class KeranjangController extends Controller
{
    public function tambah(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'barang_id' => 'required|integer|exists:barangs,id',
                'jumlah' => 'required|integer|min:1'
            ]);

            $id = $request->input('barang_id');
            $barang = Barang::findOrFail($id);
            $jumlah = (int) $request->input('jumlah', 1);
            
            // Cek apakah barang tersedia dan stok mencukupi
            if (!$barang->bisaDipinjam($jumlah)) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Jumlah melebihi stok tersedia untuk barang "' . $barang->nama . '". Stok tersedia: ' . $barang->stok_tersedia
                ], 400);
            }
            
            $cart = session()->get('cart', []);
            
            if(isset($cart[$id])) {
                // Cek apakah penambahan jumlah tidak melebihi stok tersedia
                $totalQty = $cart[$id]['qty'] + $jumlah;
                if ($totalQty > $barang->stok_tersedia) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Jumlah melebihi stok tersedia untuk barang "' . $barang->nama . '". Stok tersedia: ' . $barang->stok_tersedia
                    ], 400);
                }
                $cart[$id]['qty'] = $totalQty;
            } else {
                // Barang baru, tambahkan ke keranjang
                $cart[$id] = [
                    'id' => $barang->id,
                    'nama' => $barang->nama,
                    'stok' => $barang->stok,
                    'stok_tersedia' => $barang->stok_tersedia,
                    'stok_dipinjam' => $barang->stok_dipinjam,
                    'status' => $barang->status,
                    'qty' => $jumlah,
                    'type' => 'barang'
                ];
            }
            session(['cart' => $cart]);
            
            return response()->json([
                'success' => true, 
                'cart_count' => count($cart),
                'message' => 'Barang "' . $barang->nama . '" (' . $jumlah . ') berhasil ditambahkan ke keranjang!'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid: ' . $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function tambahRuangan(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'ruangan_id' => 'required|integer|exists:ruangans,id'
            ]);

            $ruanganId = $request->ruangan_id;
            $ruangan = Ruangan::findOrFail($ruanganId);
            
            if (!$ruangan->bisaDipinjam()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Ruangan "' . $ruangan->nama . '" tidak tersedia untuk dipinjam. Status: ' . ucfirst($ruangan->status)
                ], 400);
            }
            
            $cart = session()->get('cart', []);
            $cartKey = 'ruangan_' . $ruanganId;
            
            // Check if room is already in cart
            if (isset($cart[$cartKey])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ruangan "' . $ruangan->nama . '" sudah ada di keranjang!'
                ], 400);
            }
            
            // Add room to cart (only one room per booking)
            $cart[$cartKey] = [
                'id' => $ruangan->id,
                'nama' => $ruangan->nama,
                'deskripsi' => $ruangan->deskripsi,
                'kode' => $ruangan->kode,
                'kategori' => $ruangan->kategori,
                'lokasi' => $ruangan->lokasi,
                'lantai' => $ruangan->lantai,
                'fasilitas' => $ruangan->fasilitas,
                'status' => $ruangan->status,
                'foto' => $ruangan->foto1,
                'type' => 'ruangan'
            ];
            
            session(['cart' => $cart]);
            
            return response()->json([
                'success' => true,
                'message' => 'Ruangan berhasil ditambahkan ke keranjang!',
                'cart_count' => count($cart)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function hapus($id): \Illuminate\Http\RedirectResponse
    {
        $cart = session()->get('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);
        return redirect()->route('keranjang.index');
    }

    public function updateQty(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            $action = $request->input('action'); // 'increase' or 'decrease'
            $cart = session()->get('cart', []);
            
            if (!isset($cart[$id])) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Item tidak ditemukan di keranjang'
                ], 404);
            }
            
            $item = $cart[$id];
            
            if ($item['type'] === 'barang') {
                $barang = Barang::find($item['id']);
                if (!$barang) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Barang tidak ditemukan'
                    ], 404);
                }
                
                if (!$barang->bisaDipinjam(1)) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Barang tidak tersedia'
                    ], 400);
                }
                
                $currentQty = $item['qty'];
                
                if ($action === 'increase') {
                    $availableStock = $barang->stok_tersedia;
                    if ($currentQty < $availableStock) {
                        $cart[$id]['qty'] = $currentQty + 1;
                    } else {
                        return response()->json([
                            'success' => false, 
                            'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $availableStock
                        ], 400);
                    }
                } elseif ($action === 'decrease') {
                    if ($currentQty > 1) {
                        $cart[$id]['qty'] = $currentQty - 1;
                    } else {
                        unset($cart[$id]);
                        session(['cart' => $cart]);
                        
                        return response()->json([
                            'success' => true,
                            'removed' => true,
                            'message' => 'Item dihapus dari keranjang'
                        ]);
                    }
                }
            } elseif ($item['type'] === 'ruangan') {
                // For rooms, we don't allow quantity changes since rooms are booked as whole units
                return response()->json([
                    'success' => false, 
                    'message' => 'Ruangan dipinjam sebagai satu kesatuan, tidak dapat mengubah jumlah'
                ], 400);
            }
            
            session(['cart' => $cart]);
            
            return response()->json([
                'success' => true,
                'newQty' => $cart[$id]['qty'],
                'message' => 'Jumlah berhasil diperbarui'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }



    public function kosongkanRuangan(): \Illuminate\Http\RedirectResponse
    {
        $cart = session()->get('cart', []);
        
        // Remove only room items
        foreach ($cart as $key => $item) {
            if ($item['type'] === 'ruangan') {
                unset($cart[$key]);
            }
        }
        
        session(['cart' => $cart]);
        return redirect()->route('keranjang.index')->with('success', 'Keranjang ruangan berhasil dikosongkan');
    }

    public function index(): \Illuminate\View\View
    {
        // Hapus session kode peminjaman jika user melihat keranjang
        session()->forget('kode_peminjaman');
        
        $cart = session()->get('cart', []);
        
        // Separate items and rooms
        $barangItems = [];
        $ruanganItems = [];
        
        foreach ($cart as $key => $item) {
            // Handle legacy cart items that don't have 'type' field
            if (!isset($item['type'])) {
                // Check if it's a barang by trying to find it in barang table
                $barang = Barang::find($item['id']);
                if ($barang) {
                    // It's a barang, add the type and process
                    $item['type'] = 'barang';
                    $cart[$key] = $item; // Update the cart with type
                } else {
                    // Check if it's a ruangan by trying to find it in ruangan table
                    $ruangan = Ruangan::find($item['id']);
                    if ($ruangan) {
                        // It's a ruangan, add the type and process
                        $item['type'] = 'ruangan';
                        $cart[$key] = $item; // Update the cart with type
                    } else {
                        // Item not found in either table, skip it
                        continue;
                    }
                }
            }
            
            if ($item['type'] === 'barang') {
                $barang = Barang::find($item['id']);
                if ($barang && $barang->bisaDipinjam($item['qty'])) {
                    $barangItems[$key] = array_merge($item, [
                        'stok_tersedia' => $barang->stok_tersedia,
                        'stok_dipinjam' => $barang->stok_dipinjam,
                        'status' => $barang->status
                    ]);
                }
            } elseif ($item['type'] === 'ruangan') {
                $ruangan = Ruangan::find($item['id']);
                if ($ruangan && $ruangan->bisaDipinjam($item['qty'])) {
                    $ruanganItems[$key] = array_merge($item, [
                        'kapasitas_tersedia' => $ruangan->kapasitas_tersedia,
                        'kapasitas_dipinjam' => $ruangan->kapasitas_dipinjam,
                        'status' => $ruangan->status,
                        'kode' => $ruangan->kode,
                        'kategori' => $ruangan->kategori,
                        'lokasi' => $ruangan->lokasi,
                        'lantai' => $ruangan->lantai
                    ]);
                }
            }
        }
        
        // Update session cart with cleaned data
        $cleanedCart = array_merge($barangItems, $ruanganItems);
        session(['cart' => $cleanedCart]);
        
        return view('keranjang', compact('barangItems', 'ruanganItems'));
    }
} 