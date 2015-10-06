@extends('master')

@section('title', $show->SeriesName)

@section('content')
    <div class="container">
        <h1>{{ $show->SeriesName }}</h1>

        <ul>
            <li>Genre: {{ $show->Genre }}</li>
            <li>Rating: {{ $show->Rating }}</li>
            <li>Status: {{ $show->Status }}</li>
            <li>Network: {{ $show->Network }}</li>
            <li>Airs: {{ $show->Airs_DayOfWeek }} at {{ $show->Airs_Time }}</li>
            <li>Site Rating: {{ $show->SiteRating }}</li>
        </ul>

        <h2>Overview</h2>

        <p>{{ $show->Overview }}</p>

        <h2>Episodes</h2>
        @foreach($seasons as $season)
            @if($season->season === 0)
                <h3>Specials</h3>
            @else
                <h3>Season {{ $season->season }}</h3>
            @endif
            <ol>
                @foreach($episodes as $episode)
                    @if($episode->season === $season->season)
                        <li>{{ $episode->episodenumber }} - {{ $episode->episodename }}</li>
                    @endif
                @endforeach
            </ol>
        @endforeach
    </div>
@stop
