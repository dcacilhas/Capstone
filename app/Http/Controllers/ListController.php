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
        $listStatusesArray = DB::table('list_statuses')->lists('list_status');
        $listStatusesArray = array_map('strval', $listStatusesArray);   // Convert to string for comparison
        $series = User::find(1)->getList()
            ->join('tvseries', 'list.series_id', '=', 'tvseries.id')
            ->where('user_id', $user->id);

        // Filter which series info to retrieve based on list_status
        if (in_array($status, $listStatusesArray, true)) {
            $series = $series->where('list_status', $status)->get();
        } else {
            $status = null;
            $series = $series->get();
        }

        return view('profile/list',
            ['user' => $user, 'series' => $series, 'status' => $status, 'listStatuses' => $listStatuses]);
    }
}
