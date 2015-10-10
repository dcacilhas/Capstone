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

    /**
     * Query of episodes watched based on list ID.
     *
     * @param $query
     * @param $listId
     * @return mixed
     */
    public static function scopeGetListEpisodesWatched($query, $listId) {
        return $query
            ->where('list_id', $listId)
            ->join('list', 'list_episodes_watched.list_id', '=', 'list.id')
            ->join('tvepisodes', 'list_episodes_watched.episode_id', '=', 'tvepisodes.id')
            ->join('tvseasons', 'tvepisodes.seasonid', '=', 'tvseasons.id')
            ->join('tvseries', 'tvepisodes.seriesid', '=', 'tvseries.id');
    }

    /**
     * Query of episodes watched based on user ID.
     *
     * @param $query
     * @param $userId
     * @return mixed
     */
    public static function scopeGetUserEpisodesWatched($query, $userId) {
        return $query
            ->where('list.user_id', $userId)
            ->join('list', 'list_episodes_watched.list_id', '=', 'list.id')
            ->join('tvepisodes', 'list_episodes_watched.episode_id', '=', 'tvepisodes.id')
            ->join('tvseasons', 'tvepisodes.seasonid', '=', 'tvseasons.id')
            ->join('tvseries', 'tvepisodes.seriesid', '=', 'tvseries.id')
            ->join('users', 'list.user_id', '=', 'users.id');
    }

    /**
     * Query that orders episodes watched.
     *
     * @param $query
     * @return mixed
     */
    public function scopeGetMostRecent($query) {
        return $query
            ->orderBy('list_episodes_watched.updated_at', 'desc')
            ->orderBy('list_episodes_watched.created_at', 'desc')
            ->orderBy('tvseasons.season', 'desc')
            ->orderBy('tvepisodes.EpisodeNumber', 'desc');
    }
}
