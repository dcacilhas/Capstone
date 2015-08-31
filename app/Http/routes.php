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

Route::get('/', ['as' => 'home', 'uses' => 'PagesController@home']);
Route::get('profile', ['as' => 'profile', 'uses' => 'PagesController@profile']);
Route::get('login', ['as' => 'login', 'uses' => 'PagesController@login']);
Route::get('register', ['as' => 'register', 'uses' => 'PagesController@register']);
Route::get('about', ['as' => 'about', 'uses' => 'PagesController@about']);
Route::get('shows', ['as' => 'shows', 'uses' => 'ShowsController@index']);