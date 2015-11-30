<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Lists;
use App\Models\Show;
use Auth;
use Carbon\Carbon;
use DB;
use Input;

class ShowsController extends Controller
{
    public function index()
    {
        $filters = range('A', 'Z');
        array_unshift($filters, '#');
        $listStatuses = DB::table('list_statuses')->get();
        $genres = DB::table('genres')->get();

        // TODO: Do filtering better. Maybe use separate routes (/filter/letter/A, /filter/genre/Action)?

        // Shows Starting With filter
        $filter = Input::get('filter');
        if (in_array(strtoupper($filter), $filters)) {
            $shows = ($filter === '#') ?
                Show::whereRaw("SeriesName REGEXP '^[0-9]+'") :
                Show::where('SeriesName', 'LIKE', $filter . '%');
            $shows = $shows->orderBy('SeriesName', 'asc')
                ->select('id', 'SeriesName', 'Status', 'FirstAired', 'Network', 'Runtime', 'Rating')
                ->paginate(25);
            $this->addAdditionalShowInfo($shows, Auth::user());
            $selectedFilter = $filter;

            return view('shows.shows', compact('genres', 'filters', 'shows', 'listStatuses', 'selectedFilter'));
        }

        // Genres filter
        $genre = Input::get('genre');
        if (!empty($genre)) {
            $shows = Show::where('Genre', 'LIKE', '%' . $genre . '%')
                ->select('id', 'SeriesName', 'Status', 'FirstAired', 'Network', 'Runtime', 'Rating')
                ->orderBy('SeriesName', 'asc')
                ->whereNotNull('SeriesID')
                ->whereNotNull('SeriesName')
                ->where('SeriesName', 'NOT LIKE', '*%')
                ->where('SeriesName', '<>', '')
                ->paginate(25);
            $this->addAdditionalShowInfo($shows, Auth::user());
            $selectedGenre = $genre;

            return view('shows.shows', compact('genres', 'filters', 'shows', 'listStatuses', 'selectedGenre'));
        }

        return view('shows.shows', compact('genres', 'filters', 'listStatuses'));
    }

    /**
     * Adds site rating and first aired date.
     * If user is logged in:
     *  Checks if show is in list to display Add/Remove/Edit buttons
     *  Adds user's rating and list status
     *
     * @param $shows
     * @param $user
     */
    private function addAdditionalShowInfo($shows, $user)
    {
        if ($user) {
            foreach ($shows as $show) {
                $listQuery = $user->getList()->where('series_id', $show->id);
                $show->is_in_list = $listQuery->exists();
                $show->rating = $listQuery->value('rating');
                $show->list_status = $listQuery->value('list_status');
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
