<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Lists;
use App\User;
use DB;
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

        // TODO: Clean this up. Should be passing pre-filtered (by status) series' to view.
        if (!in_array($status, ['0', '1', '2', '3'], true)) {
            $status = null;

            $series = Lists::join('tvseries', 'list.series_id', '=', 'tvseries.id')
                ->select('list.*', 'tvseries.SeriesName')
                ->where('user_id', $user->id)
                ->get();
        } else {
            $series = Lists::join('tvseries', 'list.series_id', '=', 'tvseries.id')
                ->select('list.*', 'tvseries.SeriesName')
                ->where('user_id', $user->id)
                ->where('list_status', (int)$status)
                ->get();
        }

        return view('profile/list', ['user' => $user, 'series' => $series, 'status' => $status, 'listStatuses' => $listStatuses]);
    }
}
