@extends('master')

@section('title', 'Profile')

@section('content')
    <div class="container">
        @include('includes.profile_submenu')

        @if($user['profile_visibility'] === 0 || (Auth::check() && Auth::user()->username === $user['username']))
            @if (!empty($user['about']))
                <h2>About {{ $user['username'] }}</h2>
                <p>{!! nl2br(e($user['about'])) !!}</p>
            @endif

            <h2>Details</h2>
            <ul>
                <li>Gender: {{ $user['gender'] }}</li>
                <li>
                    Birthday: @if (!empty($user['birthday'])) {{ \Carbon\Carbon::parse($user['birthday'])->format('F j, Y') }} @endif</li>
                <li>Location: {{ $user['location'] }}</li>
                <li>Join Date: {{ \Carbon\Carbon::parse($user['created_at'])->format('F j, Y') }}</li>
            </ul>
        @else
            <div class="alert alert-danger">The user has chosen to make their profile private. Only they may view it.
            </div>
        @endif
    </div>
@stop
