<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Episode;
use App\Models\ListEpisodesWatched;
use App\Models\Lists;
use App\Models\Show;
use Auth;
use Carbon\Carbon;

class EpisodesController extends Controller
{
    public function index($seriesId, $seasonNum, $episodeNum)
    {
        // TODO: Extract to model (Episode::getEpisode($seriesId, $seasonNum, $episodeNum))
        $episode = Episode::join('tvseasons', 'tvepisodes.seasonid', '=', 'tvseasons.id')
            ->select('tvepisodes.id', 'tvepisodes.seriesid', 'season', 'episodenumber', 'episodename', 'overview',
                'tvepisodes.IMDB_ID', 'firstaired', 'tvepisodes.director', 'tvepisodes.writer')
            ->where('tvepisodes.seriesid', $seriesId)
            ->where('season', $seasonNum)
            ->where('episodenumber', $episodeNum)
            ->where('season', '<>', 0)
            ->whereNotNull('episodename')
            ->orderBy('season')
            ->orderBy('episodenumber')
            ->first();
        $episode->firstaired = Carbon::createFromTimeStamp(strtotime($episode->firstaired))->toFormattedDateString();
        $episode->writer = implode(", ", array_filter(explode("|", $episode->writer)));
        $episode->director = implode(", ", array_filter(explode("|", $episode->director)));

        if (Auth::check()) {
            $episode->isOnList = null;
            $user = Auth::user();
            // TODO: Extract to model (ListEpisodesWatched::isEpisodeWatched($userId, $seriesId, $episodeId))
            $episode->isOnList = ListEpisodesWatched::join('list', 'list_episodes_watched.list_id', '=', 'list.id')
                ->where('user_id', $user->id)
                ->where('list.series_id', $episode->seriesid)
                ->where('list_episodes_watched.episode_id', $episode->id)
                ->exists();
            // TODO: Extract to model (Lists::isSeriesOnUserList($userId, $seriesId))
            $episode->seriesIsOnList = Lists::where('user_id', $user->id)
                ->where('series_id', $episode->seriesid)
                ->exists();
        }

        // TODO: Extract to model (Show::getSeriesName($seriesId))
        $series = Show::where('id', $episode->seriesid)->select('id', 'SeriesName')->first();

        return view('shows.episode', compact('episode', 'series'));
    }
}
