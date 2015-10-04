@extends('master')

@section('title', 'Shows')

@section('content')
    <div class="container">
        <h1>TV Shows</h1>

        <!-- TODO: Add filters (All Shows, Genres, Top Rated) -->
        <ul class="nav nav-pills nav-justified">
            {{--<li role="presentation" class="{{ (Request::route()->getName() == 'profile/list') ? 'active' : '' }}">{!! link_to_route('profile/list', 'List', ['username' => $user['username']]) !!}</li>--}}
            <li role="presentation"><a href="#">All Shows</a></li>
            <li role="presentation" class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    Genres <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    @foreach($genres as $genre)
                        <li>{!! link_to_route('shows', $genre->genre, array('genre' => $genre->genre)) !!}</li>
                    @endforeach
                </ul>
            </li>
        </ul>

        @foreach ($filters as $filter)
            {!! link_to_route('shows', $filter, array('filter' => $filter)) !!}
        @endforeach

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if (isset($shows))
            <table class="table table-striped table-bordered">
                <caption>Shows</caption>
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="col-md-6">Series Title</th>
                    <th class="col-md-1 text-center">Status</th>
                    <th class="col-md-1 text-center">FirstAired</th>
                    <th class="col-md-1 text-center">Network</th>
                    <th class="col-md-1 text-center">Runtime (mins)</th>
                    <th class="col-md-1 text-center">Rating</th>
                </tr>
                </thead>
                <tbody>
                <?php $i = $shows->firstItem(); ?>
                @foreach($shows as $show)
                    <tr>
                        <th scope="row" class="text-center">{{ $i++ }}</th>
                        <td>
                            {{ $show->SeriesName }}
                            @if ((Auth::check() && Auth::user()->username === $user->username))
                                @if (!$show->is_in_list)
                                <div class="pull-right">
                                    <a href="#" class="edit"
                                       data-toggle="modal"
                                       data-target="#addModal"
                                       data-series-id="{{ $show->id }}"
                                       data-series-title="{{ $show->SeriesName }}"
                                       title="Add to List">
                                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    </a>
                                </div>
                                @endif
                            @endif
                        </td>
                        <td class="text-center">{{ $show->Status }}</td>
                        <td class="text-center">{{ $show->FirstAired }}</td>
                        <td class="text-center">{{ $show->Network }}</td>
                        <td class="text-center">{{ $show->Runtime }}</td>
                        <td class="text-center">{{ $show->Rating }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            @if (isset($selectedFilter))
                {!! $shows->appends(['filter' => $selectedFilter])->render() !!}
            @else
                {!! $shows->appends(['genre' => $selectedGenre])->render() !!}
            @endif
        @else
            <p>Click to filter shows starting with that letter.</p>
        @endif

            <!-- AddModal -->
            <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="addModalLabel"></h4>
                        </div>
                        <div class="modal-body">
                            {!! Form::open(['route' => ['profile/addToList', $user->username], 'class' => 'form-horizontal']) !!}

                            {!! Form::hidden('series_id', null, ['id' => 'series_id']) !!}
                            {!! Form::hidden('series_name', null, ['id' => 'series_name']) !!}

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
                            {!! Form::submit('Add Show To List', ['class' => 'btn btn-primary']) !!}
                        </div>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
    </div>
@stop

@section('javascript')
    <script>
        $('#addModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget),
                    title = button.data('series-title'),
                    id = button.data('series-id'),
                    modal = $(this);
            modal.find('#series_id').val(id);
            modal.find('#series_name').val(title);
            modal.find('.modal-title').text(title);
            modal.find('#status_0').prop('checked', true);
        });
    </script>
@stop