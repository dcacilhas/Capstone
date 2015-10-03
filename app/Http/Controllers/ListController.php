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

        // Get all series if status is not set, otherwise get matching series by status
        if ($status === null) {
            $series = User::find($user->id)->getListWithSeries()->get();
        } else {
            $series = User::find($user->id)->getListWithSeries()->where('list_status', $status)->get();
        }

        return view('profile/list',
            ['user' => $user, 'series' => $series, 'status' => $status, 'listStatuses' => $listStatuses]);
    }
}
