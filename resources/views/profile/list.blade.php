@extends('master')

@section('title', 'List')

@section('content')
    <div class="container">
        @include('includes.profile_submenu')

        @if($user['list_visibility'] === 0 || (Auth::check() && Auth::user()->username === $user->username))
            <ul class="nav nav-pills nav-justified">
                <li role="presentation"
                    @if($status === null) class="active" @endif>{!! link_to_route('profile/list', 'All', ['username' => $user->username]) !!}</li>
                <li role="presentation"
                    @if($status === '0') class="active" @endif>{!! link_to_route('profile/list', 'Watching', ['username' => $user->username, 'status' => '0']) !!}</li>
                <li role="presentation"
                    @if($status === '1') class="active" @endif>{!! link_to_route('profile/list', 'Plan To Watch', ['username' => $user->username, 'status' => '1']) !!}</li>
                <li role="presentation"
                    @if($status === '2') class="active" @endif>{!! link_to_route('profile/list', 'Completed', ['username' => $user->username, 'status' => '2']) !!}</li>
                <li role="presentation"
                    @if($status === '3') class="active" @endif>{!! link_to_route('profile/list', 'On Hold', ['username' => $user->username, 'status' => '3']) !!}</li>
            </ul>

            @foreach($listStatuses as $listStatus)
                @if($shows->contains('list_status', $listStatus->list_status))
                    <table class="table table-striped table-bordered">
                        <caption>{{ $listStatus->description }}</caption>
                        <thead>
                        <tr>
                            <th class="col-md-1 text-center">#</th>
                            <th class="col-md-6">Series Title</th>
                            <th class="col-md-1 text-center">Rating</th>
                            <th class="col-md-2 text-center">Last Episode Watched</th>
                            <th class="col-md-2 text-center">Progress</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i = 1; ?>
                        @foreach($shows as $show)
                            @if($show->list_status === $listStatus->list_status)
                                <tr>
                                    <th scope="row" class="text-center">{{ $i++ }}</th>
                                    <td>
                                        {!! link_to_route('shows/details', $show->SeriesName, ['seriesId' => $show->series_id]) !!}

                                        @if ((Auth::check() && Auth::user()->username === $user->username))
                                            <div class="pull-right">
                                                <a href="#" class="edit"
                                                   data-toggle="modal"
                                                   data-target="#updateModal"
                                                   data-series-id="{{ $show->series_id }}"
                                                   data-series-title="{{ $show->SeriesName }}"
                                                   data-series-rating="{{ $show->rating }}"
                                                   data-series-status="{{ $show->list_status }}"
                                                   title="Edit">
                                                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                                </a>
                                                <a href="#" class="remove"
                                                   data-toggle="modal"
                                                   data-target="#removeModal"
                                                   data-series-id="{{ $show->series_id }}"
                                                   data-series-title="{{ $show->SeriesName }}"
                                                   title="Remove">
                                                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                                </a>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $show->rating }}</td>
                                    <td class="text-center">{{ $show->last_episode_watched_formatted }}</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: {{ $show->progress }}%;"></div>
                                            <span>@if ($show->progress > 0) {{ $show->progress }}% @endif</span>
                                        </div>
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

        <!-- UpdateModal -->
        <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="updateModalLabel"></h4>
                    </div>
                    <div class="modal-body">
                        {!! Form::model($shows, ['route' => ['profile/updateList', $user->username], 'class' => 'form-horizontal']) !!}

                        {!! Form::hidden('series_id', null, ['id' => 'series_id']) !!}
                        {!! Form::hidden('page_status', $status) !!}

                        <div class="form-group">
                            {!! Form::label('rating', 'Rating: ', ['class' => 'col-sm-2 control-label']) !!}
                            <div class="col-sm-2">
                                {!! Form::selectRange('rating', 1, 10, null, ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('status', 'Status: ', ['class' => 'col-sm-2 control-label']) !!}

                            <div class="col-sm-10">
                                @foreach($listStatuses as $listStatus)
                                    <div class="radio">
                                        <label>
                                            {!! Form::radio('status', $listStatus->list_status, null, ['id' => 'status_' . $listStatus->list_status]) !!}
                                            {{ $listStatus->description }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        {!! Form::submit('Update Show', ['class' => 'btn btn-primary']) !!}
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        <!-- RemoveModal -->
        <div class="modal fade" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="removeModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="removeModalLabel"></h4>
                    </div>
                    <div class="modal-body">
                        {!! Form::model($shows, ['route' => ['profile/removeFromList', $user->username], 'class' => 'form-horizontal']) !!}

                        {!! Form::hidden('series_id', null, ['id' => 'series_id']) !!}
                        {!! Form::hidden('page_status', $status) !!}

                        <p></p>
                    </div>
                    <div class="modal-footer">
                        {!! Form::submit('Yes', ['class' => 'btn btn-primary']) !!}
                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@stop

@section('javascript')
    <script>
        $('#updateModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget),
                title = button.data('series-title'),
                rating = button.data('series-rating'),
                status = button.data('series-status'),
                id = button.data('series-id'),
                modal = $(this);
            modal.find('#series_id').val(id);
            modal.find('.modal-title').text(title);
            modal.find('#rating').val(rating);
            modal.find('#status_' + status).prop('checked', true);
        });

        $('#removeModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget),
                title = button.data('series-title'),
                id = button.data('series-id'),
                modal = $(this);
            modal.find('#series_id').val(id);
            modal.find('.modal-title').text(title);
            modal.find('p').html("Are you sure you want to remove <strong>" + title + "</strong> from your list?");
        });
    </script>
@stop

@section('css')
    <style>
        .progress {
            position:relative;
            margin-bottom:0px;
        }
        .progress span {
            position:absolute;
            left:0;
            width:100%;
            text-align:center;
            z-index:2;
            color:black;
        }
    </style>
@stop
