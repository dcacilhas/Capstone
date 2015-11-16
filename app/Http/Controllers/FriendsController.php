<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Friend;
use App\Models\User;
use Auth;
use DB;
use Input;

class FriendsController extends Controller
{
    public function index($username)
    {
        $user = User::where('username', $username)->first();
        $canViewProfile = $this->canViewProfile($user);
        if ($canViewProfile) {
            // TODO: Extract this to model
            $friendIds = DB::table('friends as f1')->join('friends as f2', function ($query) use ($user) {
                $query->on('f1.user_id', '=', 'f2.friend_id')->on('f1.friend_id', '=', 'f2.user_id')->where('f1.user_id',
                    '=', $user->id);
            })->select('f1.friend_id')->lists('friend_id');

            $friends = User::whereIn('id', $friendIds)->get();
        }

        return view('profile.friends', compact('user', 'friends', 'canViewProfile'));
    }

    public function add()
    {
        // TODO: Make this AJAX instead
        $user = Auth::user();
        $friendUsernameOrEmail = Input::get('friendUsernameOrEmail');
        // Check if requested user exists
        $requestedFriend = User::where('username', '=', $friendUsernameOrEmail)->orWhere('email', '=', $friendUsernameOrEmail)->first();
        if ($requestedFriend) {
            // Check if already friends or request has already been sent
            $alreadyFriendsOrRequested = Friend::where(function ($query) use ($user, $requestedFriend) {
                $query->where('user_id', '=', $user->id)
                    ->where('friend_id', '=', $requestedFriend->id);
            })->orWhere(function ($query) use ($user, $requestedFriend) {
                $query->where('user_id', '=', $requestedFriend->id)
                    ->where('friend_id', '=', $user->id);
            })->exists();

            if ($alreadyFriendsOrRequested) {
                return back()->withErrors('You are already friends with this user or there is a friend request pending.');
            } else {
                // Create request
                Friend::create(['user_id' => $user->id, 'friend_id' => $requestedFriend->id]);
            }
        } else {
            return back()->withErrors('No user has been found with that username or email address.');
        }

        return back();
    }

    /**
     * @param $user
     * @return bool
     */
    private function canViewProfile($user)
    {
        // If user is viewing their own profile
        if (Auth::check() && Auth::user()->username === $user->username || $user->profile_visibility === 0) {
            $canViewProfile = true;
            return $canViewProfile;
        } else {
            // If user's profile is public
//            if ($user->profile_visibility === 0) {
//                $canViewProfile = true;
//            }

            // If user's profile is private
            if ($user->profile_visibility === 1) {
                $canViewProfile = false;
            }

            // If user's profile is set to friends only
            if ($user->profile_visibility === 2) {
                // TODO: Extract this to model
                $friendIds = DB::table('friends as f1')->join('friends as f2', function ($query) use ($user) {
                    $query->on('f1.user_id', '=', 'f2.friend_id')->on('f1.friend_id', '=',
                        'f2.user_id')->where('f1.user_id',
                        '=', $user->id);
                })->select('f1.friend_id')->lists('friend_id');

                if (Auth::check() && in_array(Auth::user()->id, $friendIds)) {
                    $canViewProfile = true;
                    return $canViewProfile;
                } else {
                    $canViewProfile = false;
                    return $canViewProfile;
                }

//            $friends = User::whereIn('id', $friendIds)->get();
            }
            return $canViewProfile;
        }
    }
}