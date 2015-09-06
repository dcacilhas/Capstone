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
Route::get('home', function() { return redirect('/'); });
Route::get('profile', ['as' => 'profile', 'uses' => 'PagesController@profile']);
Route::get('about', ['as' => 'about', 'uses' => 'PagesController@about']);
Route::get('shows', ['as' => 'shows', 'uses' => 'ShowsController@index']);

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
