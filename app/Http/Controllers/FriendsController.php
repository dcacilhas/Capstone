<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Friend;
use App\Models\User;
use Auth;
use DB;
use Fenos\Notifynder\Facades\Notifynder;
use Fenos\Notifynder\Models\Notification;
use Input;

class FriendsController extends Controller
{
    public function index($username)
    {
        $user = User::where('username', $username)->first();
        $canViewProfile = $this->canViewProfile($user);
        if ($canViewProfile) {
            $friendIds = Friend::getFriendIds($user);
            $friends = User::whereIn('id', $friendIds)->get();
        }

        return view('profile.friends', compact('user', 'friends', 'canViewProfile'));
    }

    // TODO: Use authorization for this? http://laravel.com/docs/5.1/authorization
    private function canViewProfile($user)
    {
        // If user is viewing their own profile or profile visibility is public
        if (Auth::check() && Auth::user()->username === $user->username || $user->profile_visibility === 0) {
            return true;
        } else {
            // If user's profile is private
            if ($user->profile_visibility === 1) {
                return false;
            }

            // If user's profile is set to friends only
            if ($user->profile_visibility === 2) {
                // TODO: Extract this to model (Friends::getFriendIds($user))
                $friendIds = DB::table('friends as f1')->join('friends as f2', function ($query) use ($user) {
                    $query->on('f1.user_id', '=', 'f2.friend_id')->on('f1.friend_id', '=',
                        'f2.user_id')->where('f1.user_id',
                        '=', $user->id);
                })->select('f1.friend_id')->lists('friend_id');

                if (Auth::check() && in_array(Auth::user()->id, $friendIds)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * Route that handles adding friends from the Friends page.
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function add()
    {
        // TODO: Make this AJAX instead
        $user = Auth::user();
        $friendUsernameOrEmail = Input::get('friendUsernameOrEmail');
        // Get user if they exist
        $requestedFriend = User::where('username', '=', $friendUsernameOrEmail)
            ->orWhere('email', '=', $friendUsernameOrEmail)
            ->first();
        if ($requestedFriend->id === $user->id) {
            return back()->withErrors('You cannot send a friend request to yourself.');
        }
        if ($requestedFriend) {
            // Check if already friends or request has already been sent
            $areFriendsOrRequested = Friend::getFriendsOrRequested($user, $requestedFriend)->exists();
            if ($areFriendsOrRequested) {
                return back()->withErrors('You are already friends with this user or there is a friend request pending.');
            } else {
                $this->sendFriendRequest($user, $requestedFriend);
            }
        } else {
            return back()->withErrors('No user has been found with that username or email address.');
        }

        return back()->with('status', 'Friend request successfully sent to ' . $requestedFriend->username . '.');
    }

    /**
     * Send friend request from one user to another.
     *
     * @param $fromUser
     * @param $toUser
     */
    private function sendFriendRequest($fromUser, $toUser)
    {
        Friend::create(['user_id' => $fromUser->id, 'friend_id' => $toUser->id]);
        $username = $fromUser->username;
        Notifynder::category('friend.request')
            ->from($fromUser->id)
            ->to($toUser->id)
            ->url('')
            ->extra(compact('username'))
            ->send();
    }

    /**
     * Route that handles removing friends.
     *
     * @param $username
     * @return bool
     */
    public function remove($username)
    {
        $user = Auth::user();
        $friend = User::where('username', $username)->first();
        $deletedRows = Friend::getFriendsOrRequested($user, $friend)->delete();
        if ($deletedRows > 0) {
            echo true;
        } else {
            return false;
        }
    }

    /**
     * Route that handles AJAX request for sending a friend request from a user's profile.
     *
     * @param $username
     */
    public function sendRequest($username)
    {
        $user = Auth::user();
        $friend = User::where('username', $username)->first();
        $this->sendFriendRequest($user, $friend);

        echo true;
    }

    /**
     * Route that handles AJAX request for accepting a friend request from user's Notifications page.
     *
     * @param $username
     * @param $fromId
     * @param $notificationId
     */
    public function acceptFriendRequest($username, $fromId, $notificationId)
    {
        $user = Auth::user();
        Friend::create(['user_id' => $user->id, 'friend_id' => $fromId]);
        Notification::where('id', $notificationId)->delete();
        $username = $user->username;
        Notifynder::category('friend.accept')
            ->from($user->id)
            ->to($fromId)
            ->url('')
            ->extra(compact('username'))
            ->send();

        echo true;
    }

    /**
     * Route that handles AJAX request for declining a friend request from user's Notifications page.
     *
     * @param $username
     * @param $fromId
     * @param $notificationId
     * @throws \Exception
     */
    public function declineFriendRequest($username, $fromId, $notificationId)
    {
        $user = Auth::user();
        Friend::where(['user_id' => $fromId, 'friend_id' => $user->id])->delete();
        Notification::where('id', $notificationId)->delete();
        $username = $user->username;
        Notifynder::category('friend.decline')
            ->from($user->id)
            ->to($fromId)
            ->url('')
            ->extra(compact('username'))
            ->send();

        echo true;
    }
}
