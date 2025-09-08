<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FotoUploadController extends Controller
{
    /**
     * Handle foto upload
     */
    public function upload(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'foto_peminjam' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ], [
                'foto_peminjam.required' => 'Foto peminjam wajib diupload.',
                'foto_peminjam.image' => 'File foto harus berupa gambar.',
                'foto_peminjam.mimes' => 'Format foto harus JPG, JPEG, atau PNG.',
                'foto_peminjam.max' => 'Ukuran foto maksimal 2MB.',
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->first()
                    ], 422);
                }
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Handle file upload
            if ($request->hasFile('foto_peminjam')) {
                $file = $request->file('foto_peminjam');
                
                // Generate unique filename
                $filename = 'foto_peminjam_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                
                // Store file in public storage
                $path = $file->storeAs('foto_peminjam', $filename, 'public');
                
                // Store foto path in session for later use in peminjaman form
                session(['foto_peminjam_path' => $path]);
                
                // Log successful upload
                \Illuminate\Support\Facades\Log::info('Foto peminjam uploaded successfully', [
                    'filename' => $filename,
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType()
                ]);

                // Return JSON response for AJAX request
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Foto berhasil diupload!',
                        'foto_path' => $path,
                        'foto_url' => Storage::url($path)
                    ]);
                }

                return redirect()->route('peminjaman.form')
                    ->with('success', 'Foto berhasil diupload! Silakan lanjutkan mengisi form peminjaman.');
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat upload foto.'
                ]);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat upload foto.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error uploading foto peminjam', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'files' => $request->hasFile('foto_peminjam') ? 'File exists' : 'No file'
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    /**
     * Get foto from session and return as response
     */
    public function getFoto()
    {
        $fotoPath = session('foto_peminjam_path');
        
        if (!$fotoPath) {
            return response()->json(['error' => 'Foto tidak ditemukan'], 404);
        }

        if (!Storage::disk('public')->exists($fotoPath)) {
            return response()->json(['error' => 'File foto tidak ditemukan'], 404);
        }

        $file = Storage::disk('public')->get($fotoPath);
        $mimeType = mime_content_type(Storage::disk('public')->path($fotoPath));

        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="foto_peminjam.jpg"');
    }

    /**
     * Delete foto from session and storage
     */
    public function deleteFoto()
    {
        $fotoPath = session('foto_peminjam_path');
        
        if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
            Storage::disk('public')->delete($fotoPath);
        }
        
        session()->forget('foto_peminjam_path');
        
        return response()->json(['message' => 'Foto berhasil dihapus']);
    }

    /**
     * Validate foto dimensions and quality
     */
    private function validateFotoQuality($file)
    {
        $imageInfo = getimagesize($file->getPathname());
        
        if (!$imageInfo) {
            throw new \Exception('File bukan gambar yang valid.');
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];

        // Check minimum dimensions
        if ($width < 300 || $height < 300) {
            throw new \Exception('Resolusi foto terlalu rendah. Minimal 300x300 pixel.');
        }

        // Check aspect ratio (prefer square or close to square)
        $aspectRatio = $width / $height;
        if ($aspectRatio < 0.5 || $aspectRatio > 2) {
            throw new \Exception('Rasio aspek foto tidak sesuai. Gunakan foto dengan rasio 1:1 atau mendekati.');
        }

        return true;
    }
}
