<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Lists;
use App\Show;
use Illuminate\Support\Facades\DB;

class ShowsDetailsController extends Controller
{
    public function index($seriesId)
    {
        $show = Show::findOrFail($seriesId);
        $show->Genre = implode(", ", array_filter(explode("|", $show->Genre)));
        $show->SiteRating = Lists::where('series_id', $seriesId)->whereNotNull('rating')->avg('rating');
        if ($show->SiteRating) {
            $show->SiteRating = number_format($show->SiteRating, 1);
        }

        $seasons = DB::table('tvseasons')
            ->select('season')
            ->where('seriesid', $seriesId)
            ->where('season', '<>', 0)
            ->orderBy('season')
            ->get();

        $episodes = DB::table('tvepisodes')
            ->join('tvseasons', 'tvepisodes.seasonid', '=', 'tvseasons.id')
            ->select('tvepisodes.seriesid', 'season', 'episodenumber', 'episodename')
            ->where('tvepisodes.seriesid', $seriesId)
            ->where('season', '<>', 0)
            ->whereNotNull('episodename')
            ->orderBy('season')
            ->orderBy('episodenumber')
            ->get();

        return view('shows/details', compact('show', 'seasons', 'episodes'));
    }

    public function showSeason($seriesId, $seasonNum)
    {
        $episodes = DB::table('tvepisodes')
            ->join('tvseasons', 'tvepisodes.seasonid', '=', 'tvseasons.id')
            ->select('tvepisodes.seriesid', 'season', 'episodenumber', 'episodename', 'overview')
            ->where('tvepisodes.seriesid', $seriesId)
            ->where('season', $seasonNum)
            ->where('season', '<>', 0)
            ->whereNotNull('episodename')
            ->orderBy('season')
            ->orderBy('episodenumber')
            ->get();

        return $episodes;
    }

    public function showEpisode($seriesId, $seasonNum, $episodeNum)
    {
        $episode = DB::table('tvepisodes')
            ->join('tvseasons', 'tvepisodes.seasonid', '=', 'tvseasons.id')
            ->select('tvepisodes.seriesid', 'season', 'episodenumber', 'episodename', 'overview')
            ->where('tvepisodes.seriesid', $seriesId)
            ->where('season', $seasonNum)
            ->where('episodenumber', $episodeNum)
            ->where('season', '<>', 0)
            ->whereNotNull('episodename')
            ->orderBy('season')
            ->orderBy('episodenumber')
            ->get();

        return $episode;
    }
}
