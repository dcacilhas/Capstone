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
        $filters = ['#', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

        if (in_array(strtoupper($filter), $filters)) {
            $shows = ($filter === '#') ? Show::whereRaw("SeriesName regexp '^[0-9]+'") : $shows = Show::where('SeriesName', 'LIKE', $filter . '%');
            $shows = $shows->orderBy('SeriesName', 'asc')->paginate(50);

            return view('shows', ['filters' => $filters, 'shows' => $shows, 'selectedFilter' => urlencode($filter)]);
        }

        return view('shows', ['filters' => $filters]);
    }
}
