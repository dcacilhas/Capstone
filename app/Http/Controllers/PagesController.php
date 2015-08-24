<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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

    public function shows()
    {
        return view('shows');
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
