<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class pagescontroler extends Controller
{
    // ===== Halaman biasa =====

    public function dasboard()
    {
        return view("dasboard");
    }

    public function login()
    {
        return view("login");
    }

    public function mengelola()
    {
        return view("mengelola");
    }

    public function riwayat()
    {
        return view("riwayat");
    }

    public function kasir(Request $request): View
    {
        $obats = Obat::query()
            ->with(['batches' => fn($query) => $query->tersedia()])
            ->cari($request->string('q')->toString())
            ->orderBy('nama')
            ->get();

        // Pastikan file kasir.blade.php ada di resources/views/layout/kasir.blade.php
        return view('layout.kasir', compact('obats'));
    }

    public function profil()
    {
        return view("profil");
    }

    public function showRegister()
    {
        return view("register");
    }

    // ===== Simpan data pendaftaran ke database =====

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create($validated);

        return redirect('/')->with('success', 'Pendaftaran berhasil, silakan login.');
    }

    // ===== Login =====

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/dashboard');
        }

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Email atau password salah.');
    }

    // ===== Logout =====

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logout berhasil! Kamu sudah keluar dari sistem ApotekCare.');
    }
}
