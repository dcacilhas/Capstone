@extends('master')

@section('title', $episode->episodename)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1>{{ $episode->episodename }}</h1>
                <h4>Season {{ $episode->season }} Episode {{ $episode->episodenumber }}</h4>

                <ul>
                    <li>Links: <a href="http://www.imdb.com/title/{{ $episode->IMDB_ID }}">IMDB</a></li>
                    <li>Aired: {{ $episode->firstaired }}</li>
                    <li>Director: {{ $episode->director }}</li>
                    <li>Writer: {{ $episode->writer }}</li>
                </ul>

                {{--<ul>--}}
                    {{--<li>Genre: {{ $show->Genre }}</li>--}}
                    {{--<li>Rating: {{ $show->Rating }}</li>--}}
                    {{--<li>Status: {{ $show->Status }}</li>--}}
                    {{--<li>Network: {{ $show->Network }}</li>--}}
                    {{--<li>Airs: {{ $show->Airs_DayOfWeek }} at {{ $show->Airs_Time }}</li>--}}
                    {{--<li>Site Rating: {{ $show->SiteRating }}</li>--}}
                    {{--<li>IMDB: <a href="http://www.imdb.com/title/{{ $show->IMDB_ID }}">IMDB</a></li>--}}
                {{--</ul>--}}

                <h3>Overview</h3>

                <p>{{ $episode->overview }}</p>
            </div>
        </div>
    </div>
@stop
