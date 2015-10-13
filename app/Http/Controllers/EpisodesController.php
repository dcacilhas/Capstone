<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\ListEpisodesWatched;
use App\Models\Lists;
use App\Models\Show;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EpisodesController extends Controller
{
    public function index($seriesId, $seasonNum, $episodeNum)
    {
        $episode = DB::table('tvepisodes')
            ->join('tvseasons', 'tvepisodes.seasonid', '=', 'tvseasons.id')
            ->select('tvepisodes.id', 'tvepisodes.seriesid', 'tvepisodes.seriesid', 'season', 'episodenumber', 'episodename', 'overview', 'tvepisodes.IMDB_ID', 'firstaired', 'tvepisodes.director', 'tvepisodes.writer')
            ->where('tvepisodes.seriesid', $seriesId)
            ->where('season', $seasonNum)
            ->where('episodenumber', $episodeNum)
            ->where('season', '<>', 0)
            ->whereNotNull('episodename')
            ->orderBy('season')
            ->orderBy('episodenumber')
            ->first();
        $episode->firstaired = Carbon::createFromTimeStamp(strtotime($episode->firstaired))->toFormattedDateString();

        if (Auth::check()) {
            $episode->isOnList = null;
            $user = Auth::user();
            $epsWatched = ListEpisodesWatched::join('list', 'list_episodes_watched.list_id', '=', 'list.id')
                ->where('user_id', $user->id)
                ->where('list.series_id', $episode->seriesid)
                ->select('list_episodes_watched.episode_id')
                ->get();
            $episode->isOnList = $epsWatched->contains('episode_id', $episode->id);
            $episode->seriesIsOnList = Lists::where('user_id', $user->id)->where('series_id', $episode->seriesid)->exists();
        }

        return view('shows.episode', compact('episode'));
    }
}
