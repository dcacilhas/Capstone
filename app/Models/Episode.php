<?php

namespace App\Models;

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

    /**
     * An episode belongs to a show.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function show()
    {
        return $this->belongsTo('App\Models\Show', 'seriesid');
    }

    /**
     * An episode belongs to a season.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function season()
    {
        return $this->belongsTo('App\Models\Season', 'seasonid');
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

    /**
     * Query of episodes that are not specials.
     *
     * @param $query
     * @return mixed
     */
    public function scopeNoSpecials($query)
    {
        return $query->where('season', '<>', 0)->where('episodenumber', '<>', 0);
    }

    /**
     * Query of episodes in order.
     *
     * @param $query
     * @return mixed
     */
    public function scopeInOrder($query)
    {
        return $query->orderBy('season')->orderBy('episodenumber');
    }
}
