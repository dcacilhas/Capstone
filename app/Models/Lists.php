<?php

namespace App\Models;

use Eloquent;

class Lists extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'list';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'series_id',
        'user_id',
        'list_status',
        'rating'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function show()
    {
        return $this->has('App\Models\Show');
    }

    public function episodesWatched()
    {
        return $this->belongsToMany('App\Models\ListEpisodesWatched');
    }
}
