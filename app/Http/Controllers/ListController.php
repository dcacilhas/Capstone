<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;

class ListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.profile');
    }

    public function index($username)
    {
        $user = User::where('username', $username)->first();

        return view('profile/list', ['user' => $user]);
    }
}
