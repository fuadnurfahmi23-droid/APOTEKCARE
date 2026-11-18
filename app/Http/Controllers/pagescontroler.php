<?php

namespace App\Http\Controllers;

use App\Models\BatchObat;
use App\Models\Obat;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class pagescontroler extends Controller
{
    // ===== Halaman biasa =====

    public function dasboard()
    {
        return view('dasboard');
    }

    public function login()
    {
        return view('login');
    }

    public function mengelola()
    {
        return view('mengelola');
    }

    public function riwayat()
    {
        return view('riwayat');
    }

    public function semuaObat(): View
    {
        $obats = Obat::with(['batches' => fn (Builder $query): Builder => $query->tersedia()])
            ->orderBy('nama')
            ->get();

        return view('obat.menu', ['halaman' => 'semua-obat', 'obats' => $obats]);
    }

    public function daftarObat(): View
    {
        return view('obat.menu', ['halaman' => 'daftar-obat']);
    }

    public function simpanObat(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:30|unique:obats,kode',
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:80',
            'satuan' => 'required|string|max:30',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'batas_minimum' => 'required|integer|min:0',
        ]);

        Obat::create($validated);

        return redirect()->route('semua-obat')->with('success', 'Obat berhasil didaftarkan.');
    }

    public function tambahStok(): View
    {
        $obats = Obat::orderBy('nama')->get();

        return view('obat.menu', ['halaman' => 'tambah-stok', 'obats' => $obats]);
    }

    public function simpanStok(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'obat_id' => 'required|exists:obats,id',
            'nomor_batch' => [
                'required',
                'string',
                'max:50',
                Rule::unique('batch_obats', 'nomor_batch')
                    ->where('obat_id', $request->input('obat_id')),
            ],
            'pemasok' => 'nullable|string|max:255',
            'tanggal_masuk' => 'required|date',
            'tanggal_kedaluwarsa' => 'required|date|after_or_equal:tanggal_masuk',
            'jumlah' => 'required|integer|min:1',
        ]);

        $obat = Obat::findOrFail($validated['obat_id']);
        $obat->batches()->create([
            'nomor_batch' => $validated['nomor_batch'],
            'pemasok' => $validated['pemasok'] ?? null,
            'tanggal_masuk' => $validated['tanggal_masuk'],
            'tanggal_kedaluwarsa' => $validated['tanggal_kedaluwarsa'],
            'jumlah_awal' => $validated['jumlah'],
            'sisa_stok' => $validated['jumlah'],
        ]);

        return redirect()->route('semua-obat')->with('success', 'Stok obat berhasil ditambahkan.');
    }

    public function hapusObat(): View
    {
        $obats = Obat::with(['batches' => fn (Builder $query): Builder => $query->tersedia()])
            ->orderBy('nama')
            ->get();

        return view('obat.menu', ['halaman' => 'hapus-obat', 'obats' => $obats]);
    }

    public function hapusObatDariDaftar(Obat $obat): RedirectResponse
    {
        $obat->delete();

        return redirect()->route('hapus-obat')->with('success', 'Obat berhasil dihapus.');
    }

    public function prioritasObat(): View
    {
        $obats = Obat::with(['batches' => fn (Builder $query): Builder => $query->tersedia()])
            ->orderBy('nama')
            ->get()
            ->filter(function (Obat $obat): bool {
                $stokTersedia = $obat->batches->sum('sisa_stok');
                $segeraKedaluwarsa = $obat->batches->contains(
                    fn (BatchObat $batch): bool => $batch->tanggal_kedaluwarsa->lte(today()->addDays(BatchObat::AMBANG_SEGERA_HARI))
                );

                return $stokTersedia <= $obat->batas_minimum || $segeraKedaluwarsa;
            });

        return view('obat.menu', ['halaman' => 'prioritas-obat', 'obats' => $obats]);
    }

    public function kasir(Request $request): View
    {
        $obats = Obat::query()
            ->with(['batches' => fn ($query) => $query->tersedia()])
            ->cari($request->string('q')->toString())
            ->orderBy('nama')
            ->get();

        // Pastikan file kasir.blade.php ada di resources/views/layout/kasir.blade.php
        return view('layout.kasir', compact('obats'));
    }

    public function profil()
    {
        return view('profil');
    }

    public function showRegister()
    {
        return view('register');
    }

    // ===== Simpan data pendaftaran ke database =====

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create($validated);

        return redirect('/')->with('success', 'Pendaftaran berhasil, silakan login.');
    }

    // ===== Login =====

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
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
