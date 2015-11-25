<?php

namespace App\Models;

use DB;
use Eloquent;

class Friend extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'friends';

    protected $fillable = ['user_id', 'friend_id'];

    public static function getFriendIds($user)
    {
        return DB::table('friends as f1')->join('friends as f2', function ($query) use ($user) {
            $query->on('f1.user_id', '=', 'f2.friend_id')
                ->on('f1.friend_id', '=', 'f2.user_id')
                ->where('f1.user_id', '=', $user->id);
        })->select('f1.friend_id')->lists('friend_id');
    }

    public static function getFriendsOrRequested($user, $requestedFriend)
    {
        return Friend::where(function ($query) use ($user, $requestedFriend) {
            $query->where('user_id', '=', $user->id)
                ->where('friend_id', '=', $requestedFriend->id);
        })->orWhere(function ($query) use ($user, $requestedFriend) {
            $query->where('user_id', '=', $requestedFriend->id)
                ->where('friend_id', '=', $user->id);
        });
    }
}
