@extends('master')

@section('title', 'List')

@section('content')
    <div class="container">
        @include('includes.profile_submenu')

        @if($user['list_visibility'] === 0 || (Auth::check() && Auth::user()->username === $user['username']))
            <ul class="nav nav-pills nav-justified">
                <li role="presentation"
                    @if($status === null) class="active" @endif>{!! link_to_route('profile/list', 'All', ['username' => $user['username']]) !!}</li>
                <li role="presentation"
                    @if($status === '0') class="active" @endif>{!! link_to_route('profile/list', 'Watching', ['username' => $user['username'], 'status' => '0']) !!}</li>
                <li role="presentation"
                    @if($status === '1') class="active" @endif>{!! link_to_route('profile/list', 'Plan To Watch', ['username' => $user['username'], 'status' => '1']) !!}</li>
                <li role="presentation"
                    @if($status === '2') class="active" @endif>{!! link_to_route('profile/list', 'Completed', ['username' => $user['username'], 'status' => '2']) !!}</li>
                <li role="presentation"
                    @if($status === '3') class="active" @endif>{!! link_to_route('profile/list', 'On Hold', ['username' => $user['username'], 'status' => '3']) !!}</li>
            </ul>

            @foreach($listStatuses as $listStatus)
                @if($series->contains('list_status', $listStatus->list_status))
                    <table class="table table-striped">
                        <caption>{{ $listStatus->description }}</caption>
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Series Title</th>
                            <th>Rating</th>
                            <th>Last Episode Watched</th>
                            <th>Progress</th>
                            <th>Edit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; ?>
                        @foreach($series as $s)
                            @if($s['list_status'] === $listStatus->list_status)
                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>{{ $s['SeriesName'] }}</td>
                                    <td>{{ $s['rating'] }}</td>
                                    <td>{{ $s['last_episode_watched'] }}</td>
                                    <td>{{ $s['progress'] }}</td>
                                    <td>
                                        @if((Auth::check() && Auth::user()->username === $user['username']))
                                            E
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                @endif
            @endforeach
        @else
            <div class="alert alert-danger">The user has chosen to make their list private. Only they may view it.
            </div>
        @endif
    </div>
@stop
