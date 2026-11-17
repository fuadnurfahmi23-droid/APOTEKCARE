<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class pagescontroler extends Controller
{
    public function dasboard(){
        return view("dasboard");
    }

    public function login(){
        return view("login");
    }

    public function mengelola(){
        return view("mengelola");
    }

    public function riwayat(){
        return view("riwayat");
    }

    public function profil(){
        return view("profil");
    }
    public function register(Request $request){
    $validated = $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
    ]);

    // role otomatis 'public' (default di migration)
    User::create($validated);

    return redirect('/')->with('success', 'Pendaftaran berhasil, silakan login.');
}
    public function authenticate(Request $request){
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
    public function showRegister(){
        return view("register");
    }
}
