<?php

namespace App\Models;

use Elasticquent\ElasticquentTrait;
use Eloquent;

class Show extends Eloquent
{
    use ElasticquentTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tvseries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['SeriesName'];

    protected $mappingProperties = [
        'SeriesName' => [
            'type' => 'string',
            "analyzer" => "standard",
        ]
    ];

    public function seasons()
    {
        return $this->hasMany('App\Models\Season', 'seriesid');
    }

    /**
     * Get all of the episodes for the show.
     */
    public function episodes()
    {
        return $this->hasManyThrough('App\Models\Episode', 'App\Models\Season', 'seriesid', 'seasonid');
    }

    public function getLists()
    {
        return $this->belongsToMany('App\Models\Lists', 'series_id');
    }

    public function episode($seasonNum, $episodeNum)
    {
        return $this->episodes()->where('season', $seasonNum)->where('episodenumber', $episodeNum)->first();
    }

    /**
     * Query of episodes for a TV show.
     *
     * @return mixed
     */
    // TODO: Remove?
    public function getEpisodes()
    {
        return $this->hasMany('App\Models\Episode', 'seriesid')
            ->join('tvseasons', 'tvepisodes.seasonid', '=', 'tvseasons.id')
            ->where('tvepisodes.seriesid', $this->id)
            ->where('tvseasons.season', '<>', 0)
            ->whereNull('airsbefore_episode')
            ->whereNull('airsbefore_season')
            ->whereNull('airsafter_season');
    }

    public function favourites()
    {
        return $this->hasMany('App\Models\Favourite', 'series_id');
    }
}
