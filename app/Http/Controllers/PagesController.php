<?php

namespace App\Http\Controllers;

use App\Http\Requests;

class PagesController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function profile()
    {
        return view('profile');
    }

    public function login()
    {
        return view('login');
    }

    public function register()
    {
        return view('register');
    }

    public function about()
    {
        return view('about');
    }
}
