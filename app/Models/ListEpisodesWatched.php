<?php

namespace App\Models;

use Eloquent;

class ListEpisodesWatched extends Eloquent
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

    /**
     * A list of episodes watched has one episode.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function episode()
    {
        return $this->belongsTo('App\Models\Episode', 'episode_id');
    }

    /**
     * A list of episodes watched belongs to a list.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getList()
    {
        return $this->belongsTo('App\Models\Lists');
    }

    /**
     * Query of list of episodes watched with series information.
     *
     * @param $query
     * @return mixed
     */
    public function scopeWithSeries($query)
    {
        return $query
            ->join('tvepisodes', 'list_episodes_watched.episode_id', '=', 'tvepisodes.id')
            ->join('tvseasons', 'tvepisodes.seasonid', '=', 'tvseasons.id')
            ->join('tvseries', 'tvepisodes.seriesid', '=', 'tvseries.id')
            ->select('tvseries.SeriesName', 'tvepisodes.seriesid', 'tvepisodes.EpisodeName',
                'tvepisodes.EpisodeNumber', 'tvseasons.season', 'list_episodes_watched.updated_at');
    }

    /**
     * Query of most recent list of episodes watched.
     *
     * @param $query
     * @return mixed
     */
    public function scopeMostRecent($query)
    {
        return $query
            ->orderBy('list_episodes_watched.updated_at', 'desc')
            ->orderBy('list_episodes_watched.created_at', 'desc')
            ->orderBy('tvseasons.season', 'desc')
            ->orderBy('tvepisodes.EpisodeNumber', 'desc');
    }
}
