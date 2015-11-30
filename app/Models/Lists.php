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

    /**
     * A list belongs to a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * A list has one show.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function show()
    {
        return $this->hasOne('App\Models\Show', 'id', 'series_id');
    }

    /**
     * A list has many lists of episodes watched.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function episodesWatched()
    {
        return $this->hasMany('App\Models\ListEpisodesWatched', 'list_id');
    }

    /**
     * Query of list with series information.
     *
     * @param $query
     * @return mixed
     */
    public function scopeWithSeries($query)
    {
        return $query->join('tvseries', 'list.series_id', '=', 'tvseries.id')
            ->select('list.*', 'tvseries.SeriesName');
    }
}
