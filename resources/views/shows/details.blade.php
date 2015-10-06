@extends('master')

@section('title', $show->SeriesName)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1>{{ $show->SeriesName }}</h1>

                <ul>
                    <li>Genre: {{ $show->Genre }}</li>
                    <li>Rating: {{ $show->Rating }}</li>
                    <li>Status: {{ $show->Status }}</li>
                    <li>Network: {{ $show->Network }}</li>
                    <li>Airs: {{ $show->Airs_DayOfWeek }} at {{ $show->Airs_Time }}</li>
                    <li>Site Rating: {{ $show->SiteRating }}</li>
                    <li>Links: <a href="http://www.imdb.com/title/{{ $show->IMDB_ID }}">IMDB</a></li>
                </ul>

                <h3>Overview</h3>

                <p>{{ $show->Overview }}</p>
            </div>

            <div class="col-md-6">
                <h3>Episodes</h3>
                @foreach($seasons as $season)
                    <h4>Season {{ $season->season }}</h4>
                    <ol>
                        @foreach($episodes as $episode)
                            @if($episode->season === $season->season)
                                <li>
                                    {!! link_to_route('shows/episode', $episode->episodename, ['seriesId' => $episode->seriesid, 'seasonNum' => $season->season, 'episodeNum' => $episode->episodenumber]) !!}
                                </li>
                            @endif
                        @endforeach
                    </ol>
                @endforeach
            </div>
        </div>
    </div>
@stop
