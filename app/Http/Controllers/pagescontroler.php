<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class pagescontroler extends Controller
{
    public function dasboard()
    {
        return view('dasboard');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            return back()
                ->withErrors(['username' => 'Username atau password salah.'])
                ->onlyInput('username');
        }

        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'username' => ['required', 'string', 'min:4', 'max:30', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        User::create([
            'name' => $validated['nama'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'password' => $validated['password'],
            'role' => 'Kasir',
        ]);

        return redirect('/login')->with('success', 'Registrasi berhasil, silakan login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
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
