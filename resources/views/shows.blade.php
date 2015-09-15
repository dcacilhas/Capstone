@extends('master')

@section('title', 'Shows')

@section('content')
    <h1>TV Shows</h1>
    <!-- TODO: Add filters (All Shows, Genres, Top Rated) -->
    @foreach ($filters as $filter)
        {!! link_to_route('shows', $filter, array('filter' => $filter)) !!}
    @endforeach
    @if (isset($shows))
        <ul>
            @foreach ($shows as $show)
                <li>{{ $show->SeriesName }}</li>
            @endforeach
        </ul>

        {!! $shows->appends(['filter' => $selectedFilter])->render() !!}
    @else
        <p>Click to filter shows starting with that letter.</p>
    @endif
@stop
