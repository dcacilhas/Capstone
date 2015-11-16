<?php

namespace App\Models;

use Eloquent;

class Notification extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    protected $fillable = ['user_id', 'notification_type', 'status'];
}
