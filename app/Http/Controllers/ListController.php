<?php

namespace App\Http\Controllers;

use App\Models\Favourite;
use App\Models\ListEpisodesWatched;
use App\Models\Lists;
use App\Models\Show;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Input;


class ListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.profile', ['except' => ['updateEpisodesWatched']]);
    }

    public function index($username)
    {
        $user = User::where('username', $username)->first();
        $canViewList = $this->canViewList($user);
        if ($canViewList) {
            $status = Input::get('status') !== null ? (int)Input::get('status') : null;
            $listStatuses = DB::table('list_statuses')->get();
            $shows = $this->getShows($status, $user);
            $this->addExtras($shows);
        }

        return view('profile.list', compact('user', 'shows', 'status', 'listStatuses', 'canViewList'));
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
        $shows = User::findOrFail($user->id)->getListWithSeries()->orderBy('SeriesName', 'asc');
        is_null($status) ? $shows = $shows->get() : $shows = $shows->where('list_status', $status)->get();

        return $shows;
    }

    /**
     * Adds calculated progress (total episodes/episodes watched) to each show.
     * Adds last episode watched to each show.
     * Adds if show is favourited or not.
     *
     * @param $shows
     */
    private function addExtras($shows)
    {
        foreach ($shows as $show) {
            $epsTotal = Show::find($show->series_id)->getEpisodes()->count();
            $epsWatched = ListEpisodesWatched::getListEpisodesWatched($show->id)->count();
            ($show->list_status === 2) ?
                $show->progress = 100 :
                $show->progress = number_format($epsWatched / $epsTotal * 100, 0);

            $lastEpWatched = ListEpisodesWatched::getListEpisodesWatched($show->id)
                ->select('tvepisodes.EpisodeNumber', 'tvseasons.season')
                ->getMostRecent()
                ->first();

            $lastEpWatchedFormatted = null;
            if (!empty($lastEpWatched)) {
                $lastEpWatchedFormatted = sprintf('S%02dE%02d', $lastEpWatched->season, $lastEpWatched->EpisodeNumber);
                $show->season_number = $lastEpWatched->season;
                $show->episode_number = $lastEpWatched->EpisodeNumber;
            }
            $show->last_episode_watched_formatted = $lastEpWatchedFormatted;
            if (Auth::check()) {
                $show->favourited = Favourite::where('series_id', $show->series_id)
                    ->where('user_id', Auth::user()->id)
                    ->exists();
            }
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
        isset($input['rating']) ?
            $list->fill(['rating' => (int)$input['rating'], 'list_status' => (int)$input['status']]) :
            $list->fill(['list_status' => (int)$input['status']]);
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

    public function showHistory($username)
    {
        $user = User::where('username', $username)->first();

        $canViewList = $this->canViewList($user);
        if ($canViewList) {
            $epsWatched = ListEpisodesWatched::getUserEpisodesWatched($user->id)
                ->select('users.id', 'tvseries.SeriesName', 'tvepisodes.seriesid', 'tvepisodes.EpisodeName',
                    'tvepisodes.EpisodeNumber', 'tvseasons.season', 'list_episodes_watched.updated_at')
                ->getMostRecent();
            $shows = $epsWatched->get()->unique('seriesid')->lists('SeriesName', 'seriesid')->sort();
            $epsWatched = $epsWatched->paginate(25);
        }

        return view('profile.history', compact('user', 'epsWatched', 'shows', 'canViewList'));
    }

    public function showHistoryFilter($username, $seriesId)
    {
        $user = User::where('username', $username)->first();
        $canViewList = $this->canViewList($user);
        if ($canViewList) {
            $epsWatched = ListEpisodesWatched::getUserEpisodesWatched($user->id)
                ->select('users.id', 'tvseries.SeriesName', 'tvepisodes.seriesid', 'tvepisodes.EpisodeName',
                    'tvepisodes.EpisodeNumber', 'tvseasons.season', 'list_episodes_watched.updated_at');
            $shows = $epsWatched->get()->unique('seriesid')->lists('SeriesName', 'seriesid')->sort();
            $epsWatched = $epsWatched->where('list.series_id', '=', $seriesId)
                ->getMostRecent()
                ->paginate(25);
            if ($epsWatched->count() === 0) {
                abort(404);
            }
        }

        return view('profile.history', compact('user', 'epsWatched', 'shows', 'seriesId', 'canViewList'));
    }

    public function updateEpisodesWatched($seriesId)
    {
        if (!$episodeIds = Input::get('episodeIds')) {
            return;
        }

        $listId = Lists::where('series_id', $seriesId)
            ->where('user_id', Auth::user()->id)
            ->value('id');

        // If checking more than one episode at once
        if (is_array($episodeIds)) {
            $action = Input::get('action');
            if ($action === 'add') {
                $now = Carbon::now('utc')->toDateTimeString();
                foreach ($episodeIds as $episodeId) {
                    $data[] = [
                        'episode_id' => $episodeId,
                        'list_id' => $listId,
                        'created_at' => $now,
                        'updated_at' => $now
                    ];
                }
                ListEpisodesWatched::insert($data);
            } else {
                if ($action === 'remove') {
                    ListEpisodesWatched::where('list_id', $listId)->whereIn('episode_id', $episodeIds)->delete();
                }
            }
        } else {
            $ep = ListEpisodesWatched::where('episode_id', $episodeIds)->where('list_id', $listId)->first();
            $ep ? $ep->delete() : ListEpisodesWatched::create(['episode_id' => $episodeIds, 'list_id' => $listId]);
        }
    }

    /**
     * @param $user
     * @return bool
     */
    private function canViewList($user)
    {
        // If user is viewing their own profile
        if (Auth::check() && Auth::user()->username === $user->username || $user->profile_visibility === 0) {
            $canViewList = true;
            return $canViewList;
        } else {
            // If user's profile is private
            if ($user->list_visibility === 1) {
                $canViewList = false;
            }

            // If user's list is set to friends only
            if ($user->list_visibility === 2) {
                // TODO: Extract this to model
                $friendIds = DB::table('friends as f1')->join('friends as f2', function ($query) use ($user) {
                    $query->on('f1.user_id', '=', 'f2.friend_id')->on('f1.friend_id', '=',
                        'f2.user_id')->where('f1.user_id',
                        '=', $user->id);
                })->select('f1.friend_id')->lists('friend_id');

                if (Auth::check() && in_array(Auth::user()->id, $friendIds)) {
                    $canViewList = true;
                    return $canViewList;
                } else {
                    $canViewList = false;
                    return $canViewList;
                }
            }
            return $canViewList;
        }
    }
}
