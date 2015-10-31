@extends('master')

@section('title', 'Search')

@section('content')
    <div class="container">
        <h1>Search</h1>

        <p>
            Enter your search terms in the search box below.
            Use the drop down menu to search for either Shows or Users.
        </p>

        {!! Form::open(['route'=> 'search', 'class' => 'search-form', 'role' => 'search']) !!}
            @include('search.searchbox')
        {!! Form::close() !!}

        @yield('search_results')
    </div>
@stop

@section('javascript')
    <script>
        $(document).ready(function () {
            $('.search-box').focus();
        });
    </script>
@stop
