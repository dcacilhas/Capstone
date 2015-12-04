@extends('master')

@section('title', 'Friends')

@section('content')
    <div class="container">
        @include('includes.profile_submenu')

        @if($canViewProfile)
            <h2>
                Friends
                @if (Auth::check() && Auth::getUser()->username === $user->username)
                    <small>
                        <a href="#" class="add"
                           data-toggle="modal"
                           data-target="#addModal"
                           title="Add Friend">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                        </a>
                    </small>
                @endif
            </h2>

            @include('errors.errors')
            @if (session('status'))
                <div class="alert alert-success alert-dismissable" >
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @foreach($friends as $friend)
                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 col-fixed-height align-center">
                    <div>
                        <div>
                            <a href="{{ route('profile', ['username' => $friend->username]) }}">
                                {!! Html::image($friend->avatar->url(), 'Avatar', ['class' => 'img-thumbnail center-block']) !!}
                            </a>
                        </div>
                        <div class="text-center">
                            {!! link_to_route('profile', $friend->username, ['username' => $friend->username]) !!}
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- TODO: Make this AJAX instead -->
            @include('includes.modals.add_friends')
        @else
            <div class="alert alert-danger">
                @if($user->profile_visibility == 1)
                    The user has chosen to make their profile private. Only they may view it.
                @elseif($user->profile_visibility == 2)
                    The user has chosen to make their profile visible to friends only.
                    Send them a friend request for access.
                @endif
            </div>
        @endif
    </div>
@stop

@section('css')
    <style>
        .col-fixed-height {
            height: 158px;
        }
        .align-center {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .pagination {
            width: 100%;
            display: inline-block;
            padding-left: 0;
            margin: 20px 0;
            border-radius: 4px;
        }
    </style>
@stop
