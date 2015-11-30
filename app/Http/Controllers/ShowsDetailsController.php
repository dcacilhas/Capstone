<?php

namespace App\Http\Controllers;

use App\Http\Requests;
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

        $show->SiteRating = Lists::where('series_id', $seriesId)->whereNotNull('rating')->avg('rating');
        if ($show->SiteRating) {
            $show->SiteRating = number_format($show->SiteRating, 1);
        }
        $seasons = $show->seasons()->noSpecials()->orderBy('season')->lists('season');
        $episodes = $show->episodes()
            ->select('tvepisodes.id', 'tvepisodes.seriesid', 'season', 'episodenumber', 'episodename')
            ->noSpecials()
            ->inOrder()
            ->whereNotNull('episodename')
            ->get();
        if (Auth::check()) {
            $user = Auth::user();
            $list = $user->getList()->where('series_id', $show->id)->first();
            if ($list) {
                $epsWatched = $list->episodesWatched()->select('list_episodes_watched.episode_id')->get();
                foreach ($episodes as $episode) {
                    if ($epsWatched->contains('episode_id', $episode->id)) {
                        $episode->checked = 'checked';
                    }
                }
            }
            $show->isFavourited = $user->isShowFavourited($seriesId);
        } else {
            $list = false;
        }

        return view('shows.details', compact('show', 'seasons', 'episodes', 'listStatuses', 'user', 'list'));
    }

    // TODO: Flesh this out with it's own view
    public function showSeason($seriesId, $seasonNum)
    {
        $episodes = Show::findOrFail($seriesId)->episodes()
            ->select('tvepisodes.seriesid', 'season', 'episodenumber', 'episodename', 'overview', 'tvepisodes.IMDB_ID')
            ->where('season', $seasonNum)
            ->whereNotNull('episodename')
            ->noSpecials()
            ->inOrder()
            ->get();

        return $episodes;
    }
}
