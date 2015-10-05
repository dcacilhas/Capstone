<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Lists;
use App\Show;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ShowsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $filter = Input::get('filter');
        $genre = Input::get('genre');
        $filters = range('A', 'Z');
        array_unshift($filters, '#');
        $listStatuses = DB::table('list_statuses')->get();
        $genres = DB::table('genres')->get();

        // Shows Starting With filter
        if (in_array(strtoupper($filter), $filters)) {
            $shows = ($filter === '#') ? Show::whereRaw("SeriesName REGEXP '^[0-9]+'") : Show::where('SeriesName',
                'LIKE', $filter . '%');
            $shows = $shows->orderBy('SeriesName', 'asc')
                ->select('id', 'SeriesName', 'Status', 'FirstAired', 'Network', 'Runtime', 'Rating')
                ->paginate(50);

            $this->addAdditionalShowInfo($shows, $user);

            return view('shows', [
                'user' => $user,
                'genres' => $genres,
                'filters' => $filters,
                'shows' => $shows,
                'selectedFilter' => urlencode($filter),
                'listStatuses' => $listStatuses
            ]);
        }

        // Genres filter
        if (!empty($genre)) {
            $shows = Show::where('Genre', 'LIKE', '%' . $genre . '%')
                ->select('id', 'SeriesName', 'Status', 'FirstAired', 'Network', 'Runtime', 'Rating')
                ->orderBy('SeriesName', 'asc')
                ->whereNotNull('SeriesID')
                ->whereNotNull('SeriesName')
                ->where('SeriesName', 'NOT LIKE', '*%')
                ->where('SeriesName', '<>', '')
                ->paginate(50);

            $this->addAdditionalShowInfo($shows, $user);

            return view('shows', [
                'user' => $user,
                'genres' => $genres,
                'selectedGenre' => $genre,
                'filters' => $filters,
                'shows' => $shows,
                'listStatuses' => $listStatuses
            ]);
        }

        return view('shows',
            ['user' => $user, 'genres' => $genres, 'filters' => $filters, 'listStatuses' => $listStatuses]);
    }

    /**
     * @param $shows
     * @param $user
     */
    private function addAdditionalShowInfo($shows, $user)
    {
        // Check if show exists in user's list so we can display the Add Show button or not
        // Also add Site Rating to results
        if (Auth::check()) {
            foreach ($shows as $show) {
                $show->is_in_list = Lists::where('user_id', $user->id)->where('series_id', $show->id)->exists();
                $show->SiteRating = Lists::where('series_id', $show->id)->whereNotNull('rating')->avg('rating');
                if ($show->SiteRating) {
                    $show->SiteRating = number_format($show->SiteRating, 1);
                }
                $show->FirstAired = Carbon::createFromTimeStamp(strtotime($show->FirstAired))->toFormattedDateString();
            }
        } else {
            foreach ($shows as $show) {
                $show->SiteRating = Lists::where('series_id', $show->id)->whereNotNull('rating')->avg('rating');
                if ($show->SiteRating) {
                    $show->SiteRating = number_format($show->SiteRating, 1);
                }
                $show->FirstAired = Carbon::createFromTimeStamp(strtotime($show->FirstAired))->toFormattedDateString();
            }
        }
    }
}
