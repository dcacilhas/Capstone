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
        $show = Show::findOrFail($seriesId);
        $episode = $show->episode($seasonNum, $episodeNum);
        $season = $episode->season;
        if (Auth::check()) {
            $user = Auth::user();
            $show->isOnList = $user->getList()->where('series_id', $show->id)->exists();
            $episode->isWatched = $user->episodesWatched()->where('episode_id', $episode->id)->exists();
        }

        return view('shows.episode', compact('episode', 'show', 'season'));
    }
}
