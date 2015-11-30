<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Lists;
use App\Models\Show;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DB;
use Input;

class SearchController extends Controller
{
    private $limit = 1000;

    public function index()
    {
        return view('search.search');
    }

    /**
     * Route that handles a search request for a user.
     * Uses ElasticSearch as a search engine.
     * If no match found, tries less specific queries.
     *
     * @param null $query
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function postUserSearch($query = null)
    {
        if (is_null($query)) {
            return redirect('search');
        }

        $query = trim(Input::get('query')) ?: $query;

        $users = User::searchByQuery([
            'query_string' => [
                'query' => $query,
                'fields' => ['username', 'email'],
                'default_operator' => 'AND'
            ]
        ], null, null, $this->limit);

        if ($users->totalHits() === 0) {
            $users = Show::searchByQuery([
                'multi_match' => [
                    'query' => $query,
                    'fields' => ['username', 'email']
                ]
            ], null, null, $this->limit);
        }

        if ($users->totalHits() === 0) {
            $users = User::searchByQuery([
                'multi_match' => [
                    'query' => $query,
                    'fields' => ['username', 'email'],
                    'fuzziness' => 1
                ]
            ], null, null, $this->limit);
        }

        if ($users->totalHits() === 0) {
            $users = User::searchByQuery([
                'multi_match' => [
                    'query' => $query,
                    'fields' => ['username', 'email'],
                    'fuzziness' => 2
                ]
            ], null, null, $this->limit);
        }

        $users = $users->paginate(24);

        return view('search.users', compact('query', 'users'));
    }

    /**
     * Route that handles a search request for a show.
     * Uses ElasticSearch as a search engine.
     * If no match found, tries less specific queries.
     *
     * @param null $query
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function postShowSearch($query = null)
    {
        if (is_null($query)) {
            return redirect('search');
        }

        $query = trim(Input::get('query')) ?: $query;

        $shows = Show::searchByQuery([
            'query_string' => [
                'query' => $query,
                'fields' => ['SeriesName'],
                'default_operator' => 'AND'
            ],
        ], null, null, $this->limit);

        if ($shows->totalHits() === 0) {
            $shows = Show::searchByQuery([
                'match' => [
                    'SeriesName' => [
                        'query' => $query,
                        'fuzziness' => 1
                    ]
                ]
            ], null, null, $this->limit);
        }

        $shows = $shows->paginate();
        $this->addAdditionalShowInfo($shows, Auth::user());
        $listStatuses = DB::table('list_statuses')->get();

        return view('search.shows', compact('query', 'shows', 'listStatuses'));
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
    public function addAdditionalShowInfo($shows, $user)
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
