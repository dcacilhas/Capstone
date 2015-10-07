<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListEpisodesWatched extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'list_episodes_watched';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['episode_id', 'list_id'];
}
