<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrmawaJurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AkunController extends Controller
{
    public function index()
    {
        $akuns = OrmawaJurusan::orderBy('tipe')->orderBy('nama')->get();
        return view('admin.akun.index', compact('akuns'));
    }

    public function create()
    {
        return view('admin.akun.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'tipe' => 'required|in:ormawa,jurusan',
            'password' => 'required|string|min:6',
            'email' => 'nullable|email|max:255',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        OrmawaJurusan::create([
            'nama' => $request->nama,
            'tipe' => $request->tipe,
            'password' => $request->password,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'is_active' => true,
        ]);

        return redirect()->route('admin.akun.index')
            ->with('success', 'Akun berhasil dibuat!');
    }

    public function show($id)
    {
        $akun = OrmawaJurusan::findOrFail($id);
        return view('admin.akun.show', compact('akun'));
    }

    public function edit($id)
    {
        $akun = OrmawaJurusan::findOrFail($id);
        return view('admin.akun.edit', compact('akun'));
    }

    public function update(Request $request, $id)
    {
        $akun = OrmawaJurusan::findOrFail($id);

        // Log data yang diterima
        \Log::info('Update Akun Request', [
            'id' => $id,
            'nama' => $request->nama,
            'password_filled' => $request->filled('password'),
            'password_value' => $request->password ? '***' : 'null',
            'all_data' => $request->except('password')
        ]);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'tipe' => 'required|in:ormawa,jurusan',
            'password' => 'nullable|string|min:6',
            'email' => 'nullable|email|max:255',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            // is_active is not validated here - handled below
        ]);

        if ($validator->fails()) {
            \Log::error('Validation failed', ['errors' => $validator->errors()]);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $oldPassword = $akun->password;

        $data = [
            'nama' => $request->nama,
            'tipe' => $request->tipe,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'is_active' => $request->has('is_active'),
        ];

        // Update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = $request->password;
            \Log::info('Password akan diupdate', [
                'old_password' => $oldPassword,
                'new_password' => $request->password
            ]);
        } else {
            \Log::info('Password tidak diubah (field kosong)');
        }

        $akun->update($data);

        // Refresh untuk mendapatkan data terbaru
        $akun->refresh();
        
        \Log::info('Akun updated', [
            'id' => $akun->id,
            'password_after' => $akun->password,
            'password_changed' => $oldPassword !== $akun->password
        ]);

        $message = 'Akun berhasil diperbarui!';
        if ($request->filled('password')) {
            $message = 'Akun dan password berhasil diperbarui!';
        }

        return redirect()->route('admin.akun.index')
            ->with('success', $message);
    }

    public function destroy($id)
    {
        $akun = OrmawaJurusan::findOrFail($id);
        $akun->delete();

        return redirect()->route('admin.akun.index')
            ->with('success', 'Akun berhasil dihapus!');
    }

    public function resetPassword($id)
    {
        $akun = OrmawaJurusan::findOrFail($id);
        return view('admin.akun.reset-password', compact('akun'));
    }

    public function updatePassword(Request $request, $id)
    {
        $akun = OrmawaJurusan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $akun->update([
            'password' => $request->password,
        ]);

        return redirect()->route('admin.akun.index')
            ->with('success', 'Password berhasil direset!');
    }
}