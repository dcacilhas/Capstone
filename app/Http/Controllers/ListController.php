<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Lists;
use App\User;
use DB;
use Illuminate\Http\Request;
use Input;

class ListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.profile');
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

        foreach($series as $s) {
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
                $last_ep_watched_formatted = sprintf('S%02dE%02d', $last_ep_watched->season, $last_ep_watched->EpisodeNumber);
            }

            $s->last_episode_watched = $last_ep_watched_formatted;
        }

        return view('profile/list',
            ['user' => $user, 'series' => $series, 'status' => $status, 'listStatuses' => $listStatuses]);
    }

    public function updateList(Request $request) {
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

        if ($input['page_status'] != "") {
            return redirect()->route('profile/list', ['username' => $request->username, 'status' => $input['page_status']]);
        }
        return redirect()->route('profile/list', ['username' => $request->username]);
    }

    public function removeFromList(Request $request) {
        $input = $request->all();
        $user = User::where('username', $request->username)->first();
        Lists::where('user_id', $user->id)->where('series_id', $input['series_id'])->delete();

        if ($input['page_status'] != "") {
            return redirect()->route('profile/list', ['username' => $request->username, 'status' => $input['page_status']]);
        }
        return redirect()->route('profile/list', ['username' => $request->username]);
    }
}
