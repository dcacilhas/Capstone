@extends('master')

@section('title', 'Search')

@section('content')
    <div class="container">
        <h1>Search</h1>
        <p></p>
    </div>
@stop

@section('javascript')
    <script>
        $(document).ready(function () {
            $('#searchBox').focus();
        });
    </script>
@stop
