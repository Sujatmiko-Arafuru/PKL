<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrmawaJurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'tipe' => 'required|in:ormawa,jurusan',
            'password' => 'nullable|string|min:6',
            'email' => 'nullable|email|max:255',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'nama' => $request->nama,
            'tipe' => $request->tipe,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $akun->update($data);

        return redirect()->route('admin.akun.index')
            ->with('success', 'Akun berhasil diperbarui!');
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