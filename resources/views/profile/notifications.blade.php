@extends('master')

@section('title', 'Notifications')

@section('content')
    <div class="container">
        @include('includes.profile_submenu')

        @if (Auth::check() && Auth::user()->username === $user->username)
            <h2>Notifications</h2>
            <p>
                @if($user->countNotificationsNotRead() === 1)
                    You have {{ $user->countNotificationsNotRead() }} unread notification.
                @else
                    You have {{ $user->countNotificationsNotRead() }} unread notifications.
                @endif
            </p>

            <ul class="list-unstyled">
                @foreach($notifications as $notification)
                    <li>{{ $notification->text }}
                        @if($notification->category_id == 1)
                            <button type="button" class="btn btn-primary btn-xs acceptFriendRequest" data-from-id="{{ $notification->from_id }}" data-notification-id="{{ $notification->id }}">Accept</button>
                            <button type="button" class="btn btn-danger btn-xs declineFriendRequest" data-from-id="{{ $notification->from_id }}" data-notification-id="{{ $notification->id }}">Decline</button>
                        @elseif($notification->category_id == 2 || $notification->category_id == 3)
                            <button type="button" class="btn btn-default btn-xs dismissNotification" data-notification-id="{{ $notification->id }}">Dismiss</button>
                        @endif
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

                that.closest('li').hide('fast');

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
                    },
                    error: function () {
                        that.closest('li').show();
                        alert("error!!!!");
                    }
                });
            });

            $('.declineFriendRequest').click(function () {
                var that = $(this),
                        url = "{{ route('profile.friends', ['username' => Auth::user()->username]) }}" + "/request/" + that.data('fromId') + "/" + that.data('notificationId') + "/decline";

                that.closest('li').hide('fast');

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
                    },
                    error: function () {
                        that.closest('li').show();
                        alert("error!!!!");
                    }
                });
            });

            $('.dismissNotification').click(function () {
                var that = $(this),
                        url = "{{ route('profile.notifications', ['username' => Auth::user()->username]) }}" + "/" + that.data('notificationId') + "/dismiss";

                that.closest('li').hide('fast');

                $.ajax({
                    type: "POST",
                    url: url,
                    beforeSend: function (xhr) {
                        var token = $("meta[name='csrf_token']").attr('content');
                        if (token) {
                            return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                        }
                    },
                    data: {notificationId: that.data('notificationId')},
                    success: function () {
                    },
                    error: function () {
                        that.closest('li').show();
                        alert("error!!!!");
                    }
                });
            });
        });
    </script>
@stop
