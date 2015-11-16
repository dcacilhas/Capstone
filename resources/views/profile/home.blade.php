@extends('master')

@section('title', 'Profile')

@section('content')
    <div class="container">
        @include('includes.profile_submenu')

        @if($canViewProfile)
            <div class="col-md-4 col-sm-6">
                {!! Html::image($user->avatar->url('large'), 'Avatar', ['class' => 'img-responsive img-thumbnail center-block']) !!}
                <h3 class="text-center">{{ $user->username }}</h3>
                <dl class="dl-horizontal">
                    <dt>Gender:</dt>
                    <dd>{{ $user->gender }}</dd>
                    <dt>Birthday:</dt>
                    <dd>@if (!empty($user->birthday)) {{ \Carbon\Carbon::parse($user->birthday)->format('F j, Y') }} @endif</dd>
                    <dt>Location:</dt>
                    <dd> {{ $user->location }}</dd>
                    <dt>Join Date:</dt>
                    <dd>{{ \Carbon\Carbon::parse($user->created_at)->format('F j, Y') }}</dd>
                </dl>
                <p class="text-justify">{!! nl2br(e($user->about)) !!}</p>
            </div>

            <div class="col-md-4 col-sm-6">
                <h3>Recently Watched</h3>
                <ul class="list-unstyled">
                    <!-- TODO: Format this better. Separate series/episode links. -->
                    @foreach($recentEpsWatched as $ep)
                        <li>{!! link_to_route('shows.episode', $ep->SeriesName . ' ' . $ep->formatted, ['seriesId' => $ep->series_id, 'seasonNum' => $ep->season, 'episodeNum' => $ep->EpisodeNumber]) !!}</li>
                    @endforeach
                </ul>

                <h3>Favourite Shows</h3>
                <ul class="list-unstyled">
                    @foreach($favourites as $favourite)
                        <li>{!! link_to_route('shows.details', $favourite->SeriesName, ['seriesId' => $favourite->series_id]) !!}</li>
                    @endforeach
                </ul>
            </div>

            <div class="col-md-4 col-sm-6">
                <h3>Statistics</h3>
                <dl class="dl-horizontal">
                    @foreach($statistics as $statistic)
                        <dt>{{ $statistic['title'] }}:</dt>
                        <dd>{{ $statistic['value'] }}</dd>
                    @endforeach
                </dl>

                <h3>Genres Watched</h3>
                <div id="chart_div"></div>
            </div>
        @else
            <div class="alert alert-danger">
                @if($user->profile_visibility === 1)
                    The user has chosen to make their profile private. Only they may view it.
                @elseif($user->profile_visibility === 2)
                    The user has chosen to make their profile visible to friends only.
                    Send them a friend request for access.
                @endif
            </div>
        @endif
    </div>
@stop

@section('javascript')
    @if($canViewProfile)
        <script src="https://www.google.com/jsapi"></script>
        <script>
            google.load('visualization', '1.0', {'packages':['corechart']});
            google.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Genre');
                data.addColumn('number', 'Count');
                data.addRows([
                    @foreach($genres as $genre)
                        @if(isset($genre->count))
                            ['{{ $genre->genre }}', {{ $genre->count }}],
                        @endif
                    @endforeach
                ]);

                var options = {
                    'width':'100%',
                    'height':'100%',
                    sliceVisibilityThreshold: .05,
                    chartArea:{left:0,top:10,width:'100%',height:'100%'}
                };

                var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
                chart.draw(data, options);
            }
        </script>
    @endif
@stop
