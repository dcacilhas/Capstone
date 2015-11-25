<?php

namespace App\Models;

use Eloquent;

class Favourite extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'favourites';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'series_id', 'sort_order'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function show()
    {
        return $this->belongsTo('App\Models\Show', 'series_id');
    }
}
