<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\ListEpisodesWatched;
use App\Models\Lists;
use App\Models\Show;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShowsDetailsController extends Controller
{
    public function index($seriesId)
    {
        $listStatuses = DB::table('list_statuses')->get();
        $show = Show::find($seriesId);
        if (!$show) {
            abort(404);
        }

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
            ->select('tvepisodes.id', 'tvepisodes.seriesid', 'season', 'episodenumber', 'episodename')
            ->where('tvepisodes.seriesid', $seriesId)
            ->where('season', '<>', 0)
            ->whereNotNull('episodename')
            ->orderBy('season')
            ->orderBy('episodenumber')
            ->get();

        if (Auth::check()) {
            $user = Auth::user();
            $list = Lists::where('user_id', $user->id)->where('series_id', $show->id)->first();
            $epsWatched = ListEpisodesWatched::where('user_id', $user->id)
                ->where('list.series_id', $seriesId)
                ->select('list_episodes_watched.episode_id')
                ->join('list', 'list_episodes_watched.list_id', '=', 'list.id')
                ->get();

            foreach ($episodes as $episode) {
                if ($epsWatched->contains('episode_id', $episode->id)) {
                    $episode->checked = 'checked';
                }
            }
        } else {
            $list = false;
        }

        return view('shows/details', compact('show', 'seasons', 'episodes', 'listStatuses', 'user', 'list', 'epsWatched'));
    }

    // TODO: Maybe don't need this?
    public function showSeason($seriesId, $seasonNum)
    {
        $episodes = DB::table('tvepisodes')
            ->join('tvseasons', 'tvepisodes.seasonid', '=', 'tvseasons.id')
            ->select('tvepisodes.seriesid', 'season', 'episodenumber', 'episodename', 'overview', 'tvepisodes.IMDB_ID')
            ->where('tvepisodes.seriesid', $seriesId)
            ->where('season', $seasonNum)
            ->where('season', '<>', 0)
            ->whereNotNull('episodename')
            ->orderBy('season')
            ->orderBy('episodenumber')
            ->get();

        return $episodes;
    }
}
