<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\User;
use App\Models\Favourite;
use DB;
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
        $favouritesIds = Favourite::where('user_id', $user->id)
            ->lists('series_id');
        // Add show is in list but not in favourites
        $showsNotFavourited = $user->getListWithSeries()
            ->whereNotIn('series_id', $favouritesIds)
            ->get();

        return view('profile.favourites', compact('user', 'favourites', 'showsNotFavourited'));
    }

    public function add($username)
    {
        $user = User::where('username', $username)->first();
        $seriesIds = Input::get('favouritesToAdd');
        foreach ($seriesIds as $seriesId) {
            $sortOrder = Favourite::max('sort_order');
            $sortOrder = $sortOrder ? $sortOrder++ : 1;
//            if (is_null($sortOrder)) {
//                $sortOrder = 1;
//            } else {
//                $sortOrder += 1;
//            }
            Favourite::create(['user_id' => $user->id, 'series_id' => $seriesId, 'sort_order' => $sortOrder]);
        }

        return back();
    }

    public function remove($username)
    {
        $user = User::where('username', $username)->first();
        Favourite::where('user_id', $user->id)->where('series_id', Input::get('series_id'))->delete();

        // Update sort_order for all favourites
        $favourites = Favourite::orderBy('sort_order', 'asc')->get();
        $sortOrder = 1;
        foreach ($favourites as $favourite) {
            $favourite->sort_order = $sortOrder++;
            $favourite->save();
        }

        return back();
    }

    public function update($username, $seriesId)
    {
        $user = User::where('username', $username)->first();
        $isFavourited = Favourite::where('user_id', $user->id)->where('series_id', $seriesId)->exists();
        if ($isFavourited) {
            // remove
            Favourite::where('user_id', $user->id)->where('series_id', $seriesId)->delete();

            // Update sort_order for all favourites
            $favourites = Favourite::orderBy('sort_order', 'asc')->get();
            $sortOrder = 1;
            foreach ($favourites as $favourite) {
                $favourite->sort_order = $sortOrder++;
                $favourite->save();
            }
        } else {
            // add
            $sortOrder = Favourite::max('sort_order');
            if (is_null($sortOrder)) {
                $sortOrder = 1;
            } else {
                $sortOrder += 1;
            }
            $favourite = new Favourite;
            $favourite->user_id = $user->id;
            $favourite->series_id = $seriesId;
            $favourite->sort_order = $sortOrder;
            $favourite->save();
        }

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
