@extends('master')

@section('title', 'Notifications')

@section('content')
    <div class="container">
        @include('includes.profile_submenu')

        @if (Auth::check() && Auth::user()->username === $user->username)
            <h2>Notifications</h2>
            <p>
                @if($unreadNotificationsCount === 1)
                    You have {{ $unreadNotificationsCount }} unread notification.
                @else
                    You have {{ $unreadNotificationsCount }} unread notifications.
                @endif
            </p>

            <!-- For friend requests, add notification->message [Accept] [Deny] -->
            <h3>Friend Requests</h3>
            <ul>
                @foreach($notifications as $notification)
                    <li>{{ $notification->text }}</li>
                @endforeach
            </ul>
        @endif
    </div>

@stop
