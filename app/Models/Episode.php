<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;

class Episode extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tvepisodes';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['FirstAired'];

    public function show()
    {
        return $this->belongsTo('App\Models\Show');
    }

    public function season()
    {
        return $this->belongsTo('App\Models\season');
    }

    /**
     * Get the writers in a comma separated string.
     *
     * @param $writers
     * @return string
     */
    public function getWriterAttribute($writers)
    {
        return implode(", ", array_filter(explode("|", $writers)));
    }

    /**
     * Get the directors in a comma separated string.
     *
     * @param $directors
     * @return string
     */
    public function getDirectorAttribute($directors)
    {
        return implode(", ", array_filter(explode("|", $directors)));
    }
}
