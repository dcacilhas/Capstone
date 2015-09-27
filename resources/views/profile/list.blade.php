@extends('master')

@section('title', 'List')

@section('content')
    <div class="container">
        @include('includes.profile_submenu')

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

            @if($status === '0' || $status === null)
                <table class="table table-striped">
                    <caption>Watching</caption>
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
                        @if($s['list_status'] === 0)
                            <tr>
                                <th scope="row">{{ $i++ }}</th>
                                <td>{{ $s['SeriesName'] }}</td>
                                <td>{{ $s['rating'] }}</td>
                                <td>{{ $s['last_episode_watched'] }}</td>
                                <td>{{ $s['progress'] }}</td>
                                <td></td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            @endif

            @if($status === '1' || $status === null)
                <table class="table table-striped">
                    <caption>Plan To Watch</caption>
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
                    @foreach($series as $index => $s)
                        @if($s['list_status'] === 1)
                            <tr>
                                <th scope="row">{{ $i++ }}</th>
                                <td>{{ $s['SeriesName'] }}</td>
                                <td>{{ $s['rating'] }}</td>
                                <td>{{ $s['last_episode_watched'] }}</td>
                                <td>{{ $s['progress'] }}</td>
                                <td></td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            @endif

            @if($status === '2' || $status === null)
                <table class="table table-striped">
                    <caption>Completed</caption>
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
                    @foreach($series as $index => $s)
                        @if($s['list_status'] === 2)
                            <tr>
                                <th scope="row">{{ $i++ }}</th>
                                <td>{{ $s['SeriesName'] }}</td>
                                <td>{{ $s['rating'] }}</td>
                                <td>{{ $s['last_episode_watched'] }}</td>
                                <td>{{ $s['progress'] }}</td>
                                <td></td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            @endif

            @if($status === '3' || $status === null)
                <table class="table table-striped">
                    <caption>On Hold</caption>
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
                    @foreach($series as $index => $s)
                        @if($s['list_status'] === 3)
                            <tr>
                                <th scope="row">{{ $i++ }}</th>
                                <td>{{ $s['SeriesName'] }}</td>
                                <td>{{ $s['rating'] }}</td>
                                <td>{{ $s['last_episode_watched'] }}</td>
                                <td>{{ $s['progress'] }}</td>
                                <td></td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            @endif
    </div>
@stop
