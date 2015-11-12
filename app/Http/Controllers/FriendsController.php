<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\User;
use DB;

class FriendsController extends Controller
{
    public function index($username)
    {
        $user = User::where('username', $username)->first();
        // TODO: Extract this to model
        $friendIds = DB::table('friends as f1')->join('friends as f2', function ($query) use ($user) {
            $query->on('f1.user_id', '=', 'f2.friend_id')->on('f1.friend_id', '=', 'f2.user_id')->where('f1.user_id',
                '=', $user->id);
        })->select('f1.friend_id')->lists('friend_id');

        $friends = User::whereIn('id', $friendIds)->get();

        return view('profile.friends', compact('user', 'friends'));
    }
}
