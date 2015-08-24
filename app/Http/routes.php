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

//Route::get('/', function () {
//    return view('home');
//});

//Route::get('/', 'PagesController@home');
//Route::get('home', 'PagesController@home');
Route::get('home', function () {
    return redirect('/');
});
Route::get('/', [
    'as' => 'home', 'uses' => 'PagesController@home'
]);
Route::get('profile', [
    'as' => 'profile', 'uses' => 'PagesController@profile'
]);
Route::get('shows', [
    'as' => 'shows', 'uses' => 'PagesController@shows'
]);
Route::get('login', [
    'as' => 'login', 'uses' => 'PagesController@login'
]);
Route::get('register', [
    'as' => 'register', 'uses' => 'PagesController@register'
]);
Route::get('about', [
    'as' => 'about', 'uses' => 'PagesController@about'
]);
//Route::get('about', 'PagesController@about');

//Route::get('user/profile', [
//    'as' => 'profile', 'uses' => 'UserController@showProfile'
//]);