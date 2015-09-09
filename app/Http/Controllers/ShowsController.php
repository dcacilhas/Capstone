<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Show;
use Illuminate\Support\Facades\Input;

class ShowsController extends Controller
{
    public function index()
    {
        $filter = Input::get('filter');
        $filters = range('A', 'Z');
        array_unshift($filters, '#');

        if (in_array(strtoupper($filter), $filters)) {
            $shows = ($filter === '#') ? Show::whereRaw("SeriesName REGEXP '^[0-9]+'") : Show::where('SeriesName',
                'LIKE', $filter . '%');
            $shows = $shows->orderBy('SeriesName', 'asc')->paginate(50);

            return view('shows', ['filters' => $filters, 'shows' => $shows, 'selectedFilter' => urlencode($filter)]);
        }

        return view('shows', ['filters' => $filters]);
    }
}
