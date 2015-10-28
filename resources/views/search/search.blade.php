@extends('master')

@section('title', 'Search')

@section('content')
    <div class="container">
        <h1>Search</h1>
        <p>
            Enter your search terms in the search box in the navigation bar.
            Use the drop down menu to search for either Shows or Users.
        </p>

        @yield('search_results')
    </div>
@stop

@section('javascript')
    <script>
        $(document).ready(function () {
            $('#searchBox').focus();
        });
    </script>
@stop
