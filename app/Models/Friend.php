<?php

namespace App\Models;

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

//    public function getFriends($userId)
//    {
//        return $this->where();
//    }
}
