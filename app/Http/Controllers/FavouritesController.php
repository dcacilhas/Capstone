<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Favourite;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Input;

class FavouritesController extends Controller
{
    public function index($username)
    {
        $user = User::where('username', $username)->first();
        $favourites = Favourite::join('tvseries', 'favourites.series_id', '=', 'tvseries.id')
            ->where('user_id', $user->id)
            ->orderBy('sort_order', 'asc')
            ->get();
        $favouritesIds = Favourite::where('user_id', $user->id)->lists('series_id');
        $showsNotFavourited = $user->getListWithSeries()
            ->whereNotIn('series_id', $favouritesIds)
            ->get();

        return view('profile.favourites', compact('user', 'favourites', 'showsNotFavourited'));
    }

    public function add($username)
    {
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
        $user = User::where('username', $username)->first();
        $seriesId = Input::get('series_id');
        $this->removeFavourite($user->id, $seriesId);

        return back();
    }

    private function removeFavourite($userId, $seriesId)
    {
        Favourite::where('user_id', $userId)->where('series_id', $seriesId)->delete();

        // Update sort_order for all favourites
        $favourites = Favourite::where('user_id', $userId)->orderBy('sort_order', 'asc')->get();
        $sortOrder = 1;
        foreach ($favourites as $favourite) {
            $favourite->sort_order = $sortOrder++;
            $favourite->save();
        }
    }

    public function update($username, $seriesId)
    {
        $user = User::where('username', $username)->first();
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
            $favourite = Favourite::where('user_id', $userId)->where('series_id', $seriesId)->first();
            $favourite->sort_order = $sortOrder++;
            $favourite->save();
        }

        echo true;
    }
}
