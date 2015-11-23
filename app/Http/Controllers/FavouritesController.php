<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Favourite;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Support\Facades\Input;

class FavouritesController extends Controller
{
    public function index($username)
    {
        // TODO: Extract to model (User::getUser($username))
        $user = User::where('username', $username)->first();
        $canViewList = $this->canViewList($user);
        if ($canViewList) {
            // TODO: Extract to model (Favourite::getUserFavouritesWithSeries($userId))
            $favourites = Favourite::join('tvseries', 'favourites.series_id', '=', 'tvseries.id')
                ->where('user_id', $user->id)
                ->orderBy('sort_order', 'asc')
                ->get();
            // TODO: Extract to model (Favourite::getUserFavouritesIds($userId))
            $favouritesIds = Favourite::where('user_id', $user->id)->lists('series_id');
            $showsNotFavourited = $user->getListWithSeries()
                ->whereNotIn('series_id', $favouritesIds)
                ->get();
        }

        return view('profile.favourites', compact('user', 'favourites', 'showsNotFavourited', 'canViewList'));
    }

    public function add($username)
    {
        // TODO: Extract to model (User::getUser($username))
        $user = User::where('username', $username)->first();
        $seriesIds = Input::get('favouritesToAdd');
        $this->addFavourite($user->id, $seriesIds);

        return back();
    }

    private function addFavourite($userId, $seriesIds)
    {
        $sortOrder = Favourite::where('user_id', $userId)->max('sort_order');
        if (is_array($seriesIds)) {
            foreach ($seriesIds as $seriesId) {
                $sortOrder = $sortOrder ? ++$sortOrder : 1;
                Favourite::create(['user_id' => $userId, 'series_id' => $seriesId, 'sort_order' => $sortOrder]);
            }
        } else {
            $sortOrder = $sortOrder ? ++$sortOrder : 1;
            Favourite::create(['user_id' => $userId, 'series_id' => $seriesIds, 'sort_order' => $sortOrder]);
        }
    }

    public function remove($username)
    {
        // TODO: Extract to model (User::getUser($username))
        $user = User::where('username', $username)->first();
        $seriesId = Input::get('series_id');
        $this->removeFavourite($user->id, $seriesId);

        return back();
    }

    private function removeFavourite($userId, $seriesId)
    {
        // TODO: Extract to model (Favourite::delete($userId, $seriesId))
        Favourite::where('user_id', $userId)->where('series_id', $seriesId)->delete();

        // Update sort_order for all favourites
        // TODO: Extract to model (Favourite::getUserFavouritesIds($userId))
        $favourites = Favourite::where('user_id', $userId)->orderBy('sort_order', 'asc')->get();
        $sortOrder = 1;
        foreach ($favourites as $favourite) {
            $favourite->sort_order = $sortOrder++;
            $favourite->save();
        }
    }

    public function update($username, $seriesId)
    {
        // TODO: Extract to model (User::getUser($username))
        $user = User::where('username', $username)->first();
        // TODO: Extract to model (Favourite::isFavourited($userId, $seriesId))
        $isFavourited = Favourite::where('user_id', $user->id)->where('series_id', $seriesId)->exists();
        $isFavourited ? $this->removeFavourite($user->id, $seriesId) : $this->addFavourite($user->id, $seriesId);

        echo true;
    }

    public function reorder()
    {
        $userId = Auth::id();
        $seriesIds = Input::get('item');

        // Update sort_order for all favourites
        $sortOrder = 1;
        foreach ($seriesIds as $seriesId) {
            // TODO: Extract to model (Favourite::getFavourite($userId, $seriesId))
            $favourite = Favourite::where('user_id', $userId)->where('series_id', $seriesId)->first();
            $favourite->sort_order = $sortOrder++;
            $favourite->save();
        }

        echo true;
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
}
