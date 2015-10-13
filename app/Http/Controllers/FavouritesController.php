<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\User;
use DB;

class FavouritesController extends Controller
{
    public function index($username)
    {
        $user = User::where('username', $username)->first();
        $favourites = DB::table('favourites')
            ->join('tvseries', 'favourites.series_id', '=', 'tvseries.id')
            ->where('user_id', $user->id)
            ->get();

        return view('profile.favourites', compact('user', 'favourites'));
    }

    public function add($username)
    {
    }

    public function remove($username)
    {
    }

    public function update($username)
    {
    }
}
