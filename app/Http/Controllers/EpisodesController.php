<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Season;
use App\Models\Show;
use Auth;

class EpisodesController extends Controller
{
    public function index($seriesId, $seasonNum, $episodeNum)
    {
        $series = Show::findOrFail($seriesId);
        $episode = $series->episode($seasonNum, $episodeNum);
        $season = Season::findOrFail($episode->seasonid);
        if (Auth::check()) {
            $user = Auth::user();
            $series->isOnList = $user->getList()->where('series_id', $series->id)->exists();
            $episode->isWatched = $user->episodesWatched()->where('episode_id', $episode->id)->exists();
        }

        return view('shows.episode', compact('episode', 'series', 'season'));
    }
}
