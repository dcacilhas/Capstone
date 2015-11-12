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

    public function getFriends($userId)
    {
        return $this->where();
    }
}
