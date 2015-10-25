<?php

namespace App\Http\Controllers;

use App\Models\ListEpisodesWatched;
use App\Models\Lists;
use App\Models\Show;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Input;


class ListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.profile', ['except' => ['updateListEpisodesWatched']]);
    }

    public function index($username)
    {
        $user = User::where('username', $username)->first();
        $status = Input::get('status');
        $listStatuses = DB::table('list_statuses')->get();
        $shows = $this->getShows($status, $user);
        $this->addExtras($shows);

        return view('profile.list', compact('user', 'shows', 'status', 'listStatuses'));
    }

    /**
     * Get shows, either filtered by status or all.
     *
     * @param $status
     * @param $user
     * @return mixed
     */
    private function getShows($status, $user)
    {
        if ($status === null) {
            $shows = User::find($user->id)->getListWithSeries()->get();
        } else {
            $shows = User::find($user->id)->getListWithSeries()->where('list_status', $status)->get();
        }
        return $shows;
    }

    /**
     * Adds calculated progress (total episodes/episodes watched) to each show.
     * Adds last episode watched to each show.
     *
     * @param $shows
     */
    private function addExtras($shows)
    {
        foreach ($shows as $show) {
            $epsTotalCount = Show::find($show->series_id)->getEpisodes()->count();
            $epsWatchedCount = ListEpisodesWatched::getListEpisodesWatched($show->id)->count();

            if ($show->list_status === 2) {
                $show->progress = 100;
            } else {
                $show->progress = number_format($epsWatchedCount / $epsTotalCount * 100, 0);
            }

            $lastEpWatched = ListEpisodesWatched::getListEpisodesWatched($show->id)
                ->select('tvepisodes.EpisodeNumber', 'tvseasons.season')
                ->getMostRecent()
                ->first();

            $lastEpWatchedFormatted = null;
            if (!empty($lastEpWatched)) {
                $lastEpWatchedFormatted = sprintf('S%02dE%02d', $lastEpWatched->season,
                    $lastEpWatched->EpisodeNumber);
                $show->season_number = $lastEpWatched->season;
                $show->episode_number = $lastEpWatched->EpisodeNumber;
            }
            $show->last_episode_watched_formatted = $lastEpWatchedFormatted;
        }
    }

    public function updateList(Request $request)
    {
        $this->validate($request, [
            'rating' => 'numeric|between:1,10',
            'status' => 'required|numeric|between:0,3'
        ]);

        $input = $request->all();
        $user = User::where('username', $request->username)->first();
        $list = Lists::where('user_id', $user->id)->where('series_id', $input['series_id'])->first();
        if (isset($input['rating'])) {
            $list->fill(['rating' => (int)$input['rating'], 'list_status' => (int)$input['status']]);
        } else {
            $list->fill(['list_status' => (int)$input['status']]);
        }
        $list->save();

        return back();
    }

    public function removeFromList(Request $request)
    {
        $input = $request->all();
        $user = User::where('username', $request->username)->first();
        Lists::where('user_id', $user->id)->where('series_id', $input['series_id'])->delete();

        return back();
    }

    public function addToList(Request $request)
    {
        $this->validate($request, [
            'status' => 'required|numeric|between:0,3'
        ]);

        $input = $request->all();
        $user = User::where('username', $request->username)->first();
        Lists::create([
            'series_id' => $input['series_id'],
            'user_id' => $user->id,
            'list_status' => (int)$input['status']
        ]);

        return back()->with('status', $input['series_name'] . ' was successfully added to your list!');
    }

    public function showWatchHistory($username)
    {
        $user = User::where('username', $username)->first();
        $epsWatched = ListEpisodesWatched::getUserEpisodesWatched($user->id)
            ->select('users.id', 'tvseries.SeriesName', 'tvepisodes.seriesid', 'tvepisodes.EpisodeName',
                'tvepisodes.EpisodeNumber', 'tvseasons.season', 'list_episodes_watched.updated_at')
            ->getMostRecent();
        $shows = $epsWatched->get()->unique('seriesid')->lists('SeriesName', 'seriesid')->sort();
        $epsWatched = $epsWatched->paginate(10);

        return view('profile.watch_history', compact('user', 'epsWatched', 'shows'));
    }

    public function showWatchHistoryFilter($username, $seriesId)
    {
        $user = User::where('username', $username)->first();
        $epsWatched = ListEpisodesWatched::getUserEpisodesWatched($user->id)
            ->select('users.id', 'tvseries.SeriesName', 'tvepisodes.seriesid', 'tvepisodes.EpisodeName',
                'tvepisodes.EpisodeNumber', 'tvseasons.season', 'list_episodes_watched.updated_at');
        $shows = $epsWatched->get()->unique('seriesid')->lists('SeriesName', 'seriesid')->sort();
        $epsWatched = $epsWatched->where('list.series_id', '=', $seriesId)
            ->getMostRecent()
            ->paginate(10);

        return view('profile.watch_history', compact('user', 'epsWatched', 'shows', 'seriesId'));
    }

    public function updateListEpisodesWatched($seriesId)
    {
        $episodeId = (int)Input::get('episodeId');
        $userId = Auth::user()->id;
        $listId = Lists::where('series_id', $seriesId)
            ->where('user_id', $userId)
            ->value('id');
        $ep = ListEpisodesWatched::where('episode_id', $episodeId)
            ->where('list_id', $listId)
            ->first();

        if ($ep) {
            $ep->delete();
        } else {
            ListEpisodesWatched::create(['episode_id' => $episodeId, 'list_id' => $listId]);
        }

        echo true;
    }
}
