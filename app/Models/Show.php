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

    /**
     * A show has many seasons.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function seasons()
    {
        return $this->hasMany('App\Models\Season', 'seriesid');
    }

    /**
     * Get an episode by a show's season number and episode number.
     *
     * @param $seasonNum
     * @param $episodeNum
     * @return mixed
     */
    public function episode($seasonNum, $episodeNum)
    {
        return $this->episodes()->where('season', $seasonNum)->where('episodenumber', $episodeNum)->first();
    }

    /**
     * A show has many episodes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function episodes()
    {
        return $this->hasManyThrough('App\Models\Episode', 'App\Models\Season', 'seriesid', 'seasonid');
    }

    // TODO: Remove?

    /**
     * Query of episodes for a TV show.
     * Ignores special episodes.
     *
     * @return mixed
     */
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

    /**
     * Get a show's writers in a comma separated string.
     *
     * @param $writers
     * @return string
     */
    public function getGenreAttribute($genres)
    {
        return implode(", ", array_filter(explode("|", $genres)));
    }

    /**
     * A show belongs to a favourite.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function favourites()
    {
        return $this->belongsTo('App\Models\Favourite', 'series_id');
    }
}
