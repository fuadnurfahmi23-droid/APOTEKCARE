<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
