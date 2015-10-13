@extends('master')

@section('title', 'Favourites')

@section('content')
    <div class="container">
        @include('includes.profile_submenu')

        <h2>Favourite Shows</h2><!-- TODO: Add plus icon and JS to add to list -->
        <ul>
            @foreach($favourites as $favourite)
                <li>{!! link_to_route('shows/details', $favourite->SeriesName, ['seriesId' => $favourite->series_id]) !!}</li>
                <!-- TODO: Remove button -->
                <!-- TODO: Reordering (jQuery UI) - update sortOrder in DB -->
            @endforeach
        </ul>
    </div>
@stop
