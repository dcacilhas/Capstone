<?php

namespace App\Models;

use Elasticquent\ElasticquentTrait;
use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    use ElasticquentTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tvseries';

    protected $fillable = ['SeriesName'];

    protected $mappingProperties = [
        'SeriesName' => [
            'type' => 'string',
            "analyzer" => "standard",
        ]
    ];

    /**
     * Query of episodes for a TV show.
     *
     * @return mixed
     */
    public function getEpisodes() {
        return $this->hasMany('App\Models\Episode', 'seriesid')
            ->join('tvseasons', 'tvepisodes.seasonid', '=', 'tvseasons.id')
            ->where('tvepisodes.seriesid', $this->id)
            ->where('tvseasons.season', '<>', 0)
            ->whereNull('airsbefore_episode')
            ->whereNull('airsbefore_season')
            ->whereNull('airsafter_season');
    }
}
