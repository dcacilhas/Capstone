@extends('master')

@section('title', 'Profile')

@section('content')
    <div class="container">
        <h1>Profile</h1>
        @if (Auth::check() && Auth::user()->username === $user['username'])
            <p>Authenticated user</p>
            {{ $user }} <br><br>
        @else
            <p>Not authenticated user</p>
        @endif

        {!! link_to_route('profile', 'Profile', ['username' => $user['username']]) !!}
        {{--@if (Auth::check() && Auth::user()->username === $user['username'])--}}
        {!! link_to_route('profile/edit', 'Edit Profile', ['username' => $user['username']]) !!}
        {!! link_to_route('profile/account', 'Edit Account', ['username' => $user['username']]) !!}
        {{--@endif--}}

        @if (!empty($user['about']))
            <h2>About {{ $user['username'] }}</h2>
            {{ $user['about'] }}
        @endif

        <h2>Details</h2>
        <ul>
            <li>Gender: {{ $user['gender'] }}</li>
            <li>
                Birthday: @if (!empty($user['birthday'])) {{ \Carbon\Carbon::parse($user['birthday'])->format('F j, Y') }} @endif</li>
            <li>Location: {{ $user['location'] }}</li>
        </ul>
    </div>
@stop
