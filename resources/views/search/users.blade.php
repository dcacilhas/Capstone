@extends('search.search')

@section('search_results')
    <h3>Search Users: Results for "{{ $query }}"</h3>

    {{ $users }}
@stop