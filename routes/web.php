<?php

use App\Http\Controllers\pagescontroler;
use Illuminate\Support\Facades\Route;

Route::get('/', [pagescontroler::class, 'login']);
Route::post('/login', [pagescontroler::class, 'authenticate']);

Route::get('/register', [pagescontroler::class, 'showRegister']);
Route::post('/register', [pagescontroler::class, 'register']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [pagescontroler::class, 'dasboard']);
    Route::get('/mengelola', [pagescontroler::class, 'mengelola']);
    Route::get('/profil', [pagescontroler::class, 'profil']);
    Route::get('/riwayat', [pagescontroler::class, 'riwayat']);
});
