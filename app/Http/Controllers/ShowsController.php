<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Lists;
use App\Show;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ShowsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $genres = DB::table('genres')->get();
        $filter = Input::get('filter');
        $genre = Input::get('genre');
        $filters = range('A', 'Z');
        array_unshift($filters, '#');
        $listStatuses = DB::table('list_statuses')->get();

        if (in_array(strtoupper($filter), $filters)) {
            $shows = ($filter === '#') ? Show::whereRaw("SeriesName REGEXP '^[0-9]+'") : Show::where('SeriesName',
                'LIKE', $filter . '%');
            $shows = $shows->orderBy('SeriesName', 'asc')->paginate(50);

            return view('shows', ['user' => $user, 'genres' => $genres, 'filters' => $filters, 'shows' => $shows, 'selectedFilter' => urlencode($filter), 'listStatuses' => $listStatuses]);
        }

        if (!empty($genre)) {
            $shows = Show::where('Genre', 'LIKE', '%' . $genre . '%')
                ->orderBy('SeriesName', 'asc')
                ->whereNotNull('SeriesID')
                ->whereNotNull('SeriesName')
                ->where('SeriesName', 'NOT LIKE', '%*')
                ->where('SeriesName', '<>', '')
                ->paginate(50);

            if (Auth::check()) {
                // Check if show exists in user's list so we can display the Add Show button or not
                foreach ($shows as $show) {
                    $show->is_in_list = $is_in_list = Lists::where('user_id', $user->id)->where('series_id', $show->id)->exists();
                }
            }

            return view('shows', ['user' => $user, 'genres' => $genres, 'selectedGenre' => $genre, 'filters' => $filters, 'shows' => $shows, 'listStatuses' => $listStatuses]);
        }

        return view('shows', ['user' => $user, 'genres' => $genres, 'filters' => $filters, 'listStatuses' => $listStatuses]);
    }
}
