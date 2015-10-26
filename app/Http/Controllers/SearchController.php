<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Show;
use App\Models\User;

class SearchController extends Controller
{
    public function index()
    {
//        Add tvseries to ElasticSearch (avoid memory limit)
//        Show::chunk(200, function ($shows) {
//            $shows->addToIndex();
//        });

        return view('search');
    }

    public function postUserSearch($query)
    {
        $users = User::searchByQuery([
            'multi_match' => [
                'query' => $query,
                'fields' => ['username', 'email'],
                'fuzziness' => 2,
                'prefix_length' => 1
            ]
        ]);

        return $users;
    }

    public function postShowSearch($query)
    {
        $shows = Show::searchByQuery([
            'match' => [
                'SeriesName' => [
                    'query' => $query,
                    'fuzziness' => 2,
                    'prefix_length' => 1
                ]
            ]
        ]);

        return $shows;
    }
}
