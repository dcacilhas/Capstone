<?php

namespace App\Http\Controllers;

use App\Http\Requests;
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
        $series = User::find($user->id)->getList()
            ->join('tvseries', 'list.series_id', '=', 'tvseries.id')
            ->where('user_id', $user->id)
            ->get();

        // Only get series that match by filtered status, otherwise return all
        if ($status != null && $series->contains('list_status', (int)$status)) {
            $series = User::find($user->id)->getList()
                ->join('tvseries', 'list.series_id', '=', 'tvseries.id')
                ->where('user_id', $user->id)
                ->where('list_status', $status)
                ->get();
        }

        return view('profile/list',
            ['user' => $user, 'series' => $series, 'status' => $status, 'listStatuses' => $listStatuses]);
    }
}
