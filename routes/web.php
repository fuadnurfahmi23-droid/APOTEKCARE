<?php

use App\Http\Controllers\pagescontroler;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::view('/login', 'login')->name('login');
Route::post('/login', [pagescontroler::class, 'login']);

Route::view('/register', 'register');
Route::post('/register', [pagescontroler::class, 'register']);

Route::post('/dashboard', [pagescontroler::class, 'dasboard']);
Route::get('/dashboard', [pagescontroler::class, 'dasboard']);
Route::post('/mengelola', [pagescontroler::class, 'mengelola']);
Route::get('/mengelola', [pagescontroler::class, 'mengelola']);
Route::post('/profil', [pagescontroler::class, 'profil']);
Route::get('/profil', [pagescontroler::class, 'profil']);
Route::post('/riwayat', [pagescontroler::class, 'riwayat']);
Route::get('/riwayat', [pagescontroler::class, 'riwayat']);
