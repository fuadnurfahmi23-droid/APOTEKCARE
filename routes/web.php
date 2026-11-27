<?php

use App\Http\Controllers\pagescontroler;
use Illuminate\Support\Facades\Route;

// Halaman Login & Register
Route::get('/', [pagescontroler::class, 'login'])->name('login');
Route::post('/login', [pagescontroler::class, 'authenticate']);
Route::get('/register', [pagescontroler::class, 'showRegister']);
Route::post('/register', [pagescontroler::class, 'register']);

// Halaman yang butuh Login (Grup Auth)
Route::middleware('auth')->group(function () {
    // Menu Dasar
    Route::get('/dashboard', [pagescontroler::class, 'dasboard'])->name('dashboard');
    Route::get('/profil', [pagescontroler::class, 'profil'])->name('profil');
    Route::post('/logout', [pagescontroler::class, 'logout'])->name('logout');

    // Menu Kasir & Riwayat (Jika masih dipakai)
    Route::get('/kasir', [pagescontroler::class, 'kasir'])->name('kasir');
    Route::get('/riwayat', [pagescontroler::class, 'riwayat'])->name('riwayat');
    Route::get('/mengelola', [pagescontroler::class, 'mengelola'])->name('mengelola');
    Route::post('/mengelola', [pagescontroler::class, 'simpanObatModal'])->name('mengelola.simpan');
    Route::put('/mengelola/{obat}', [pagescontroler::class, 'updateObat'])->whereNumber('obat')->name('mengelola.update');
    Route::get('/obat/{obat}', [pagescontroler::class, 'detailObat'])->whereNumber('obat')->name('obat.detail');

    // ==========================================
    // MENU BARU (Sesuai Sidebar Inventory/Investor)
    // ==========================================
    Route::get('/semua-obat', [pagescontroler::class, 'semuaObat'])->name('semua-obat');
    Route::get('/daftar-obat', [pagescontroler::class, 'daftarObat'])->name('daftar-obat');
    Route::post('/daftar-obat', [pagescontroler::class, 'simpanObat'])->name('daftar-obat.simpan');
    Route::get('/tambah-stok', [pagescontroler::class, 'tambahStok'])->name('tambah-stok');
    Route::post('/tambah-stok', [pagescontroler::class, 'simpanStok'])->name('tambah-stok.simpan');
    Route::get('/hapus-obat', [pagescontroler::class, 'hapusObat'])->name('hapus-obat');
    Route::delete('/hapus-obat/{obat}', [pagescontroler::class, 'hapusObatDariDaftar'])->name('hapus-obat.hapus');
    Route::get('/prioritas-obat', [pagescontroler::class, 'prioritasObat'])->name('prioritas-obat');
});
