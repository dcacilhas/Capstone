<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\ListEpisodesWatched;
use App\Lists;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // Get all series if status is not set, otherwise get matching series by status
        if ($status === null) {
            $series = User::find($user->id)->getListWithSeries()->get();
        } else {
            $series = User::find($user->id)->getListWithSeries()->where('list_status', $status)->get();
        }

        foreach ($series as $s) {
            // TODO: Extract out to model
            $eps_total = DB::table('tvepisodes')
                ->where('seriesid', $s->series_id)
                ->whereNull('airsbefore_episode')
                ->whereNull('airsbefore_season')
                ->whereNull('airsafter_season')
                ->count();

            // TODO: Extract out to model
            $eps_watched = DB::table('list_episodes_watched')
                ->where('list_id', $s->id)
                ->count();

            if ($s->list_status === 2) {
                $s->progress = 100;
            } else {
                $s->progress = number_format($eps_watched / $eps_total * 100, 0);
            }

            // TODO: Extract out to model
            $last_ep_watched = DB::table('list_episodes_watched')
                ->where('list_id', $s->id)
                ->select('tvepisodes.EpisodeNumber', 'tvseasons.season')
                ->join('tvepisodes', 'list_episodes_watched.episode_id', '=', 'tvepisodes.id')
                ->join('tvseasons', 'tvepisodes.seasonid', '=', 'tvseasons.id')
                ->orderBy('updated_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->orderBy('tvseasons.season', 'desc')
                ->orderBy('tvepisodes.EpisodeNumber', 'desc')
                ->first();

            $last_ep_watched_formatted = null;
            if (!empty($last_ep_watched)) {
                $last_ep_watched_formatted = sprintf('S%02dE%02d', $last_ep_watched->season,
                    $last_ep_watched->EpisodeNumber);
            }

            $s->last_episode_watched = $last_ep_watched_formatted;
        }


        return view('profile/list', compact('user', 'series', 'status', 'listStatuses'));
    }

    public function updateList(Request $request)
    {
        // TODO: Abstract this out: http://laravel.com/docs/5.1/validation#form-request-validation
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
        // TODO: Abstract this out: http://laravel.com/docs/5.1/validation#form-request-validation
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
        $eps_watched = DB::table('list_episodes_watched')
            ->where('user_id', $user->id)
            ->select('users.id', 'tvseries.SeriesName', 'tvepisodes.seriesid', 'tvepisodes.EpisodeName',
                'tvepisodes.EpisodeNumber', 'tvseasons.season', 'list_episodes_watched.updated_at')
            ->join('tvepisodes', 'list_episodes_watched.episode_id', '=', 'tvepisodes.id')
            ->join('tvseasons', 'tvepisodes.seasonid', '=', 'tvseasons.id')
            ->join('tvseries', 'tvepisodes.seriesid', '=', 'tvseries.id')
            ->join('list', 'list_episodes_watched.list_id', '=', 'list.id')
            ->join('users', 'list.user_id', '=', 'users.id')
            ->orderBy('list_episodes_watched.updated_at', 'desc')
            ->orderBy('list_episodes_watched.created_at', 'desc')
            ->orderBy('tvseasons.season', 'desc')
            ->orderBy('tvepisodes.EpisodeNumber', 'desc')
            ->paginate(10);

        return view('profile/watch_history', compact('user', 'eps_watched'));
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
            ListEpisodesWatched::create(['episode_id' => $episodeId, 'list_id'=> $listId]);
        }

        echo true;
    }
}
