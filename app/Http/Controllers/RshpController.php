<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RshpController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function layanan()
    {
        return view('layanan');
    }

    public function visimisi()
    {
        return view('visimisi');
    }


    public function struktur()
    {
        return view('struktur');
    }

    public function login()
    {
        return view('login');
    }
}
