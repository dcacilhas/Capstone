<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Favourite;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class FavouritesController extends Controller
{
    public function index($username)
    {
        $user = User::where('username', $username)->first();
        $canViewList = $this->canViewList($user);
        if ($canViewList) {
            $favourites = $user->favourites()->orderBy('sort_order', 'asc')->with('show')->get();
            // Add series information for each favourite
            foreach ($favourites as $favourite) {
                $favourite->series = $favourite->show;
            }
            $favouriteSeriesIds = $favourites->lists('series_id');
            $notFavourites = $user->getListWithSeries()
                ->whereNotIn('series_id', $favouriteSeriesIds)
                ->get();
        }

        return view('profile.favourites', compact('user', 'favourites', 'notFavourites', 'canViewList'));
    }

    /**
     * @param $user
     * @return bool
     */
    // TODO: Use authorization for this? http://laravel.com/docs/5.1/authorization
    private function canViewList($user)
    {
        // If user is viewing their own profile
        if (Auth::check() && Auth::user()->username === $user->username || $user->list_visibility === 0) {
            return true;
        } else {
            // If user's profile is private
            if ($user->list_visibility === 1) {
                return false;
            }

            // If user's list is set to friends only
            if ($user->list_visibility === 2) {
                // TODO: Extract this to model (Friends::getFriendIds($user))
                $friendIds = DB::table('friends as f1')->join('friends as f2', function ($query) use ($user) {
                    $query->on('f1.user_id', '=', 'f2.friend_id')->on('f1.friend_id', '=',
                        'f2.user_id')->where('f1.user_id',
                        '=', $user->id);
                })->select('f1.friend_id')->lists('friend_id');

                if (Auth::check() && in_array(Auth::user()->id, $friendIds)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * Route that handles adding favourites from the Favourites page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Request $request)
    {
        // TODO: Abstract this out: http://laravel.com/docs/5.1/validation#form-request-validation
        $this->validate($request, [
            'favouritesToAdd' => 'required',
        ], ['favouritesToAdd.required' => 'A show must be selected to be added to favourites.']);

        $seriesIds = Input::get('favouritesToAdd');
        $this->addFavourite($seriesIds);

        return back();
    }

    /**
     * Add favourites.
     *
     * @param $seriesIds
     */
    private function addFavourite($seriesIds)
    {
        $user = Auth::user();
        $sortOrder = $user->favourites()->max('sort_order');
        if (is_array($seriesIds)) {
            $now = Carbon::now('utc')->toDateTimeString();
            foreach ($seriesIds as $seriesId) {
                $sortOrder = $sortOrder ? ++$sortOrder : 1;
                $data[] = [
                    'user_id' => $user->id,
                    'series_id' => $seriesId,
                    'sort_order' => $sortOrder,
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }
            Favourite::insert($data);
        } else {
            $sortOrder = $sortOrder ? ++$sortOrder : 1;
            Favourite::create(['user_id' => $user->id, 'series_id' => $seriesIds, 'sort_order' => $sortOrder]);
        }
    }

    /**
     * Route that handles adding favourites from the Favourites page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove()
    {
        $seriesId = Input::get('series_id');
        $this->removeFavourite($seriesId);

        return back();
    }

    /**
     * Remove favourites
     *
     * @param $seriesId
     */
    private function removeFavourite($seriesId)
    {
        $user = Auth::user();
        $user->favourites()->where('series_id', $seriesId)->delete();
        $favouritesSeriesIds = $user->favourites()->orderBy('sort_order', 'asc')->lists('series_id');
        $this->updateSortOrder($favouritesSeriesIds);
    }

    /**
     * Update the sort order of a user's favourites.
     *
     * @param $seriesIds
     */
    private function updateSortOrder($seriesIds)
    {
        $user = Auth::user();
        $sortOrder = 1;
        foreach ($seriesIds as $seriesId) {
            $favourite = $user->favourites()->where('series_id', $seriesId)->first();
            $favourite->sort_order = $sortOrder++;
            $favourite->save();
        }
    }

    /**
     * Route that handles AJAX request for adding or removing a favourite when clicking on star button.
     *
     * @param $username
     * @param $seriesId
     */
    public function update($username, $seriesId)
    {
        $user = User::where('username', $username)->first();
        $isFavourited = $user->isShowFavourited($seriesId);
        $isFavourited ? $this->removeFavourite($seriesId) : $this->addFavourite($seriesId);

        echo true;
    }

    /**
     * Route that handles AJAX request for reordering favourites using jQuery UI Sortable.
     */
    public function reorder()
    {
        $seriesIds = Input::get('item');
        $this->updateSortOrder($seriesIds);

        echo true;
    }
}
