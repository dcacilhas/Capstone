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
                    <li>{{ $notification->text }}
                        <button type="button" class="btn btn-primary btn-xs acceptFriendRequest" data-from-id="{{ $notification->from_id }}" data-notification-id="{{ $notification->id }}">Accept</button>
                        <button type="button" class="btn btn-danger btn-xs declineFriendRequest" data-from-id="{{ $notification->from_id }}" data-notification-id="{{ $notification->id }}">Decline</button>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

@stop

@section('javascript')
    <script>
        $(document).ready(function () {
            $('.acceptFriendRequest').click(function () {
                var that = $(this),
                        url = "{{ route('profile.friends', ['username' => Auth::user()->username]) }}" + "/request/" + that.data('fromId') + "/" + that.data('notificationId') + "/accept";

                $.ajax({
                    type: "POST",
                    url: url,
                    beforeSend: function (xhr) {
                        var token = $("meta[name='csrf_token']").attr('content');
                        if (token) {
                            return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                        }
                    },
                    data: {fromId: that.data('fromId'), notificationId: that.data('notificationId')},
                    success: function () {
                        that.closest('li').hide('fast');
                    },
                    error: function () {
                        alert("error!!!!");
                    }
                });
            });

            $('.declineFriendRequest').click(function () {
                var that = $(this),
                        url = "{{ route('profile.friends', ['username' => Auth::user()->username]) }}" + "/request/" + that.data('fromId') + "/" + that.data('notificationId') + "/decline";

                $.ajax({
                    type: "POST",
                    url: url,
                    beforeSend: function (xhr) {
                        var token = $("meta[name='csrf_token']").attr('content');
                        if (token) {
                            return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                        }
                    },
                    data: {fromId: that.data('fromId'), notificationId: that.data('notificationId')},
                    success: function () {
                        that.closest('li').hide('fast');
                    },
                    error: function () {
                        alert("error!!!!");
                    }
                });
            });
        });
    </script>
@stop
