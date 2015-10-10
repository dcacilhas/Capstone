<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tvseries';

    /**
     * Query of episodes for a TV show.
     *
     * @return mixed
     */
    public function getEpisodes() {
        return $this->hasMany('App\Episode', 'seriesid')
            ->join('tvseasons', 'tvepisodes.seasonid', '=', 'tvseasons.id')
            ->where('tvepisodes.seriesid', $this->id)
            ->where('tvseasons.season', '<>', 0)
            ->whereNull('airsbefore_episode')
            ->whereNull('airsbefore_season')
            ->whereNull('airsafter_season');
    }
}
