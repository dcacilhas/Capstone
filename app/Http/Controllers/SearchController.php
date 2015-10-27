<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Show;
use App\Models\User;
use Input;

class SearchController extends Controller
{
    public function index()
    {
//        Add tvseries to ElasticSearch (avoid memory limit)
//        Show::chunk(200, function ($shows) {
//            $shows->addToIndex();
//        });

        return view('search.search');
    }

    public function postUserSearch($query)
    {
        $query = Input::get('query');

        $users = User::searchByQuery([
            'multi_match' => [
                'query' => $query,
                'fields' => ['username', 'email']
            ]
        ]);

        $users = User::searchByQuery([
            'query_string' => [
                'query' => $query,
                'fields' => ['username', 'email']
            ]
        ]);

        return $users;
    }

    public function postShowSearch($query)
    {
        $query = Input::get('query') ?: $query;
//        $shows = Show::searchByQuery([
//            'match' => [
//                'SeriesName' => [
//                    'query' => $query,
//                    'fuzziness' => 2,
//                    'prefix_length' => 1
//                ]
//            ]
//        ]);

        $shows = Show::searchByQuery([
            'query_string' => [
                'query' => $query,
                'fields' => ['SeriesName'],
                'default_operator' => 'AND'
            ]
        ], null, null, 1000);

        if ($shows->totalHits() === 0) {
            $shows = Show::searchByQuery([
                'query_string' => [
                    'query' => $query,
                    'fields' => ['SeriesName']
                ]
            ], null, null, 1000);
        }

        if ($shows->totalHits() === 0) {
            $shows = Show::searchByQuery([
                'match' => [
                    'SeriesName' => [
                        'query' => $query
                    ]
                ]
            ]);
        }

        return $shows;
    }
}
