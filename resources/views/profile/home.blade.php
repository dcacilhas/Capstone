@extends('master')

@section('title', 'Profile')

@section('content')
    <div class="container">
        @include('includes.profile_submenu')

        @if($user['profile_visibility'] === 0 || (Auth::check() && Auth::user()->username === $user['username']))
            <div class="col-md-4">
                @if(!empty($user->about))
                    <h2>About {{ $user->username }}</h2>
                    <p>{!! nl2br(e($user->about)) !!}</p>
                @endif

                <h2>Details</h2>
                <ul>
                    <li>Gender: {{ $user->gender }}</li>
                    <li>Birthday: @if (!empty($user->birthday)) {{ \Carbon\Carbon::parse($user->birthday)->format('F j, Y') }} @endif</li>
                    <li>Location: {{ $user->location }}</li>
                    <li>Join Date: {{ \Carbon\Carbon::parse($user->created_at)->format('F j, Y') }}</li>
                </ul>
            </div>

            <div class="col-md-4">
                <h2>Recently Watched</h2>
                <ul>
                    <!-- TODO: Format this better. Separate series/episode links. -->
                    @foreach($recentEpsWatched as $ep)
                        <li>{!! link_to_route('shows/episode', $ep->SeriesName . ' ' . $ep->formatted, ['seriesId' => $ep->series_id, 'seasonNum' => $ep->season, 'episodeNum' => $ep->EpisodeNumber]) !!}</li>
                    @endforeach
                    <li>{!! link_to_route('profile/list/watchHistory', 'View Full Watch History', ['username' => $user->username]) !!}</li>
                </ul>

                <h2>Favourite Shows</h2>
                <ul>
                    @foreach($favourites as $favourite)
                        <li>{!! link_to_route('shows/details', $favourite->SeriesName, ['seriesId' => $favourite->series_id]) !!}</li>
                    @endforeach
                    <li>{!! link_to_route('profile/favourites', 'View All Favourites', ['username' => $user->username]) !!}</li>
                </ul>
            </div>

            <div class="col-md-4">
                <h2>Statistics</h2>
                <ul>
                    @foreach($statistics as $statistic)
                        <li>{{ $statistic['title'] }}: {{ $statistic['value'] }}</li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="alert alert-danger">The user has chosen to make their profile private. Only they may view it.
            </div>
        @endif
    </div>
@stop
