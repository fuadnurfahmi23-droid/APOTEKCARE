<?php

use App\Http\Controllers\pagescontroler;
use Illuminate\Support\Facades\Route;

// Tambahkan ->name('login') agar middleware 'auth' tahu ke mana harus redirect jika belum login
Route::get('/', [pagescontroler::class, 'login'])->name('login');
Route::post('/login', [pagescontroler::class, 'authenticate']);

Route::get('/register', [pagescontroler::class, 'showRegister']);
Route::post('/register', [pagescontroler::class, 'register']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [pagescontroler::class, 'dasboard'])->name('dashboard');
    Route::get('/mengelola', [pagescontroler::class, 'mengelola'])->name('mengelola');
    Route::get('/kasir', [pagescontroler::class, 'kasir'])->name('kasir');
    Route::get('/profil', [pagescontroler::class, 'profil'])->name('profil');
    Route::get('/riwayat', [pagescontroler::class, 'riwayat'])->name('riwayat');

    // Tambahkan route Logout ini agar tombol "Ya, Logout" di modal berfungsi
    Route::post('/logout', [pagescontroler::class, 'logout'])->name('logout');
});
