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
                    <table class="table table-striped table-bordered">
                        <caption>{{ $listStatus->description }}</caption>
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Series Title</th>
                            <th>Rating</th>
                            <th>Last Episode Watched</th>
                            <th>Progress</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; ?>
                        @foreach($series as $s)
                            @if($s['list_status'] === $listStatus->list_status)
                                <tr>
                                    <th scope="row">{{ $i++ }}</th>
                                    <td>
                                        {{ $s['SeriesName'] }}
                                        @if ((Auth::check() && Auth::user()->username === $user['username']))
                                            <div class="pull-right">
                                                <a href="#" class="edit" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                                                <a href="#" class="remove" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $s['rating'] }}</td>
                                    <td>{{ $s['last_episode_watched'] }}</td>
                                    <td>{{ $s['progress'] }}</td>
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

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('javascript')
    <script>
    </script>
@stop
