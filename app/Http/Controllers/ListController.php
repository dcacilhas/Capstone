<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use Input;

class ListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.profile');
    }

    public function index($username)
    {
        // TODO: Remove. Test data for table structure. Replace with actual Model info from database.
        $series = array(
            array(
                'list_status' => 0,
                'SeriesName' => 'Watching 1',
                'rating' => 7,
                'last_episode_watched' => 'S01E02',
                'progress' => '1/100'
            ),
            array(
                'list_status' => 0,
                'SeriesName' => 'Watching 2',
                'rating' => 8,
                'last_episode_watched' => 'S01E02',
                'progress' => '2/100'
            ),
            array(
                'list_status' => 0,
                'SeriesName' => 'Watching 3',
                'rating' => 9,
                'last_episode_watched' => 'S01E03',
                'progress' => '3/100'
            ),
            array(
                'list_status' => 1,
                'SeriesName' => 'Plan To Watch 1',
                'rating' => 5,
                'last_episode_watched' => 'S01E01',
                'progress' => '3/100'
            ),
            array(
                'list_status' => 1,
                'SeriesName' => 'Plan To Watch 2',
                'rating' => 7.6,
                'last_episode_watched' => 'S02E03',
                'progress' => '7/100'
            ),
            array(
                'list_status' => 2,
                'SeriesName' => 'Completed 1',
                'rating' => 6,
                'last_episode_watched' => 'S01E03',
                'progress' => '3/100'
            ),
            array(
                'list_status' => 2,
                'SeriesName' => 'Completed 2',
                'rating' => 8,
                'last_episode_watched' => 'S01E03',
                'progress' => '3/100'
            ),
            array(
                'list_status' => 2,
                'SeriesName' => 'Completed 3',
                'rating' => 9,
                'last_episode_watched' => 'S01E03',
                'progress' => '3/100'
            ),
            array(
                'list_status' => 3,
                'SeriesName' => 'On Hold 1',
                'rating' => 6,
                'last_episode_watched' => 'S01E03',
                'progress' => '3/100'
            ),
            array(
                'list_status' => 3,
                'SeriesName' => 'On Hold 2',
                'rating' => 7,
                'last_episode_watched' => 'S01E03',
                'progress' => '3/100'
            ),
        );

        $user = User::where('username', $username)->first();
        $status = Input::get('status');

        return view('profile/list', ['user' => $user, 'series' => $series, 'status' => $status]);
    }
}
