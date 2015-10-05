<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Lists;
use App\Show;
use Illuminate\Support\Facades\DB;

class ShowsDetailsController extends Controller
{
    public function index($id)
    {
        $show = Show::findOrFail($id);
        $show->Genre = implode(", ", array_filter(explode("|", $show->Genre)));
        $show->SiteRating = number_format(Lists::where('series_id', $id)->whereNotNull('rating')->avg('rating'), 1);

        $seasons = DB::table('tvseasons')
            ->select('season')
            ->where('seriesid', $id)
            ->where('season', '<>', 0)
            ->orderBy('season')
            ->get();

        $episodes = DB::table('tvseasons')
            ->join('tvepisodes', 'tvseasons.id', '=', 'tvepisodes.seasonid')
            ->select('tvepisodes.seriesid', 'season', 'episodenumber', 'episodename')
            ->where('tvepisodes.seriesid', $id)
            ->where('season', '<>', 0)
            ->orderBy('season')
            ->orderBy('episodenumber')
            ->get();

        return view('shows_details', compact('show', 'seasons', 'episodes'));
    }
}
