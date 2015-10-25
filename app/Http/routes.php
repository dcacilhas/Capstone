<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Static Pages
Route::get('/', ['as' => 'home', 'uses' => 'PagesController@home']);
Route::get('home', function () {
    return redirect('/');
});
Route::get('about', ['as' => 'about', 'uses' => 'PagesController@about']);

// Shows
Route::get('shows', ['as' => 'shows', 'uses' => 'ShowsController@index']);
Route::get('shows/{seriesId}', ['as' => 'shows/details', 'uses' => 'ShowsDetailsController@index']);
// TODO: Maybe don't need this?
Route::get('shows/{seriesId}/season/{seasonNum}', ['as' => 'shows/season', 'uses' => 'ShowsDetailsController@showSeason']);

// Episodes
Route::get('shows/{seriesId}/season/{seasonNum}/episode/{episodeNum}', ['as' => 'shows/episode', 'uses' => 'EpisodesController@index']);

// Profile
Route::get('profile', function () {
    if (Auth::check()) {
        return redirect()->route('profile', [Auth::user()->username]);
    }
    return back();
});
Route::get('profile/{username}', ['as' => 'profile', 'uses' => 'ProfileController@index']);
Route::get('profile/{username}/edit', ['as' => 'profile/edit', 'uses' => 'ProfileController@showEditProfile']);
Route::get('profile/{username}/account', ['as' => 'profile/account', 'uses' => 'ProfileController@showEditAccount']);
Route::post('profile/{username}/postProfile',
    ['as' => 'profile/postProfile', 'uses' => 'ProfileController@postProfile']);
Route::post('profile/{username}/postEmail', ['as' => 'profile/postEmail', 'uses' => 'ProfileController@postEmail']);
Route::post('profile/{username}/postPassword',
    ['as' => 'profile/postPassword', 'uses' => 'ProfileController@postPassword']);

// Favourites
Route::get('profile/{username}/favourites', ['as' => 'profile/favourites', 'uses' => 'FavouritesController@index']);
Route::post('profile/{username}/favourites/add', ['as' => 'profile/favourites/add', 'uses' => 'FavouritesController@add']);
Route::post('profile/{username}/favourites/remove', ['as' => 'profile/favourites/remove', 'uses' => 'FavouritesController@remove']);
Route::post('profile/{username}/favourites/{seriesId}/update', ['as' => 'profile/favourites/update', 'uses' => 'FavouritesController@update']);
Route::post('profile/{username}/favourites/reorder', ['as' => 'profile/favourites/reorder', 'uses' => 'FavouritesController@reorder']);

// List
Route::get('profile/{username}/list', ['as' => 'profile/list', 'uses' => 'ListController@index']);
Route::get('profile/{username}/list/watchHistory', ['as' => 'profile/list/watchHistory', 'uses' => 'ListController@showWatchHistory']);
Route::get('profile/{username}/list/watchHistory/show/{seriesId}', ['as' => 'profile/list/watchHistory/show', 'uses' => 'ListController@showWatchHistoryFilter']);
Route::post('profile/{username}/list/update', ['as' => 'profile/list/update', 'uses' => 'ListController@updateList']);
Route::post('profile/{username}/list/remove', ['as' => 'profile/list/remove', 'uses' => 'ListController@removeFromList']);
Route::post('profile/{username}/list/add', ['as' => 'profile/list/add', 'uses' => 'ListController@addToList']);
Route::post('list/{seriesId}/updateListEpisodesWatched', ['as' => 'list/updateListEpisodesWatched', 'uses' => 'ListController@updateListEpisodesWatched']);

// Authentication
Route::get('login', ['as' => 'login', 'uses' => 'Auth\AuthController@getLogin']);
Route::post('login', ['as' => 'login', 'uses' => 'Auth\AuthController@postLogin']);
Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);
Route::get('register', ['as' => 'register', 'uses' => 'Auth\AuthController@getRegister']);
Route::post('register', ['as' => 'register', 'uses' => 'Auth\AuthController@postRegister']);

// Password Reset Request
Route::get('password/email', ['as' => 'password/email', 'uses' => 'Auth\PasswordController@getEmail']);
Route::post('password/email', ['as' => 'password/email', 'uses' => 'Auth\PasswordController@postEmail']);

// Password Reset
Route::get('password/reset/{token}', ['as' => 'password/reset', 'uses' => 'Auth\PasswordController@getReset']);
Route::post('password/reset', ['as' => 'password/reset', 'uses' => 'Auth\PasswordController@postReset']);
