@extends('master')

@section('title', 'Watch History')

@section('content')
    <div class="container">
        @include('includes.profile_submenu')

        @if($user['list_visibility'] === 0 || (Auth::check() && Auth::user()->username === $user['username']))
            <table class="table table-striped table-bordered">
                <caption>Watch History</caption>
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="col-md-4 text-center">Series Title</th>
                    <th class="col-md-4 text-center">Episode Title</th>
                    <th class="col-md-1 text-center">Season</th>
                    <th class="col-md-1 text-center">Episode</th>
                    <th class="col-md-2 text-center">Watched On</th>
                </tr>
                </thead>
                <tbody>
                <?php $i = $epsWatched->firstItem(); ?>
                @foreach($epsWatched as $ep)
                    <tr>
                        <th scope="row" class="text-center">{{ $i++ }}</th>
                        <td class="text-center">{!! link_to_route('shows/details', $ep->SeriesName, ['id' => $ep->seriesid]) !!}</td>
                        <td class="text-center">{!! link_to_route('shows/episode', $ep->EpisodeName, ['seriesId' => $ep->seriesid, 'seasonNum' => $ep->season, 'episodeNum' => $ep->EpisodeNumber]) !!}</td>
                        <td class="text-center">{{ $ep->season }}</td>
                        <td class="text-center">{{ $ep->EpisodeNumber }}</td>
                        <!-- TODO: Detect user's timezone. -->
                        <td class="text-center">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($ep->updated_at))->toDayDateTimeString() }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {!! $epsWatched->render() !!}
        @else
            <div class="alert alert-danger">The user has chosen to make their list private. Only they may view it.
            </div>
        @endif
    </div>
@stop
