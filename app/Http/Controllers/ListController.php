<?php

namespace App\Http\Controllers;

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
    // TODO: Make show remove AJAX
    // TODO: Make show rating update AJAX
    public function __construct()
    {
        $this->middleware('auth.profile', ['only' => ['addToList', 'removeFromList', 'updateList']]);
    }

    public function index($username)
    {
        $user = User::where('username', $username)->first();
        $canViewList = $this->canViewList($user);
        if ($canViewList) {
            $status = Input::get('status') !== null ? (int)Input::get('status') : null;
            $listStatuses = DB::table('list_statuses')->get();
            $shows = $this->getLists($status, $user);
            $this->addExtras($shows, $user);
        }

        return view('profile.list', compact('user', 'shows', 'status', 'listStatuses', 'canViewList'));
    }

    // TODO: Use authorization for this? http://laravel.com/docs/5.1/authorization
    private function canViewList($user)
    {
        // If user is viewing their own profile
        if (Auth::check() && Auth::user()->username === $user->username || $user->list_visibility === 0) {
            return true;
        } else {
            // If user's profile is private
            if ($user->list_visibility === 1) {
                return false;
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
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * Get shows, either filtered by status or all.
     *
     * @param $status
     * @param $user
     * @return mixed
     */
    private function getLists($status, $user)
    {
        $listsQuery = $user->getList()->withSeries()->orderBy('SeriesName', 'asc');
        is_null($status) ? $lists = $listsQuery->get() : $lists = $listsQuery->where('list_status', $status)->get();

        return $lists;
    }

    /**
     * Adds calculated progress (total episodes/episodes watched) to each show.
     * Adds last episode watched to each show.
     * Adds if show is favourited or not.
     *
     * @param $lists
     */
    private function addExtras($lists, $user)
    {
        // TODO: Optimize this
        foreach ($lists as $list) {
            $epsTotal = Show::find($list->series_id)->getEpisodes()->count();
            $epsWatched = $list->episodesWatched();
            $epsWatchedCount = $epsWatched->count();
            ($list->list_status === 2) ?
                $list->progress = 100 :
                $list->progress = number_format($epsWatchedCount / $epsTotal * 100, 0);
            if ($epsWatchedCount) {
                $lastEpWatched = $epsWatched->withSeries()->mostRecent()->first();
                $list->last_episode_watched_formatted = sprintf('S%02dE%02d', $lastEpWatched->season,
                    $lastEpWatched->EpisodeNumber);
                $list->season_number = $lastEpWatched->season;
                $list->episode_number = $lastEpWatched->EpisodeNumber;
            }
            if (Auth::check()) {
                $list->favourited = $user->isShowFavourited($list->series_id);
            }
        }
    }

    /**
     * Route that handles updating a show in a user's list (status or rating).
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateList(Request $request)
    {
        $this->validate($request, [
            'rating' => 'numeric|between:1,10',
            'status' => 'required|numeric|between:0,3'
        ]);

        $input = $request->all();
        $user = User::where('username', $request->username)->first();
        $list = $user->getList()->where('series_id', $input['series_id'])->first();
        isset($input['rating']) ?
            $list->fill(['rating' => (int)$input['rating'], 'list_status' => (int)$input['status']]) :
            $list->fill(['list_status' => (int)$input['status']]);
        $list->save();

        return back();
    }

    /**
     * Route that handles removing a show from a user's list.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeFromList(Request $request)
    {
        $input = $request->all();
        $user = User::where('username', $request->username)->first();
        $user->getList()->where('series_id', $input['series_id'])->delete();

        return back();
    }

    /**
     * Route that handles adding a show to a user's list.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Route that handles showing a user's watch history.
     *
     * @param $username
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showHistory($username)
    {
        $user = User::where('username', $username)->first();

        $canViewList = $this->canViewList($user);
        if ($canViewList) {
            $query = $user->episodesWatched()->withSeries()->mostRecent();
            $epsWatched = $query->paginate(25);
            $shows = $query->get()->unique(function ($item) {
                return $item['SeriesName'];
            })->lists('SeriesName', 'seriesid');
        }

        return view('profile.history', compact('user', 'epsWatched', 'shows', 'canViewList'));
    }

    /**
     * Route that handles showing a user's watch history filtered by a show.
     *
     * @param $username
     * @param $seriesId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showHistoryFilter($username, $seriesId)
    {
        $user = User::where('username', $username)->first();

        $canViewList = $this->canViewList($user);
        if ($canViewList) {
            $query = $user->episodesWatched()->withSeries()->mostRecent();
            $shows = $query->get()->unique(function ($item) {
                return $item['SeriesName'];
            })->lists('SeriesName', 'seriesid');
            $epsWatched = $query->where('list.series_id', $seriesId)->paginate(25);
            if ($epsWatched->count() === 0) {
                abort(404);
            }
        }

        return view('profile.history', compact('user', 'epsWatched', 'shows', 'seriesId', 'canViewList'));
    }

    /**
     * @param $user
     * @return bool
     */
    // TODO: Use authorization for this? http://laravel.com/docs/5.1/authorization
    /**
     * Route that handles AJAX request for updating whether an episode is watched or not.
     *
     * @param $seriesId
     * @throws \Exception
     */
    public function updateEpisodesWatched($seriesId)
    {
        if (!$episodeIds = Input::get('episodeIds')) {
            return;
        }

        $listId = Lists::where('series_id', $seriesId)
            ->where('user_id', Auth::user()->id)
            ->value('id');

        // If checking/unchecking more than one episode at once
        if (is_array($episodeIds)) {
            $action = Input::get('action');
            if ($action === 'add') {
                $now = Carbon::now();
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
}
