<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrmawaJurusan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $akun = OrmawaJurusan::where('nama', $request->nama)
            ->where('is_active', true)
            ->first();

        if ($akun && $akun->verifyPassword($request->password)) {
            session(['user_id' => $akun->id, 'user_nama' => $akun->nama, 'user_tipe' => $akun->tipe]);
            return redirect()->intended(route('dashboard'));
        }

        return redirect()->back()
            ->withErrors(['nama' => 'Nama atau password salah'])
            ->withInput();
    }

    public function logout()
    {
        session()->forget(['user_id', 'user_nama', 'user_tipe']);
        return redirect()->route('beranda');
    }
}