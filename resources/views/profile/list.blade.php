@extends('master')

@section('title', 'List')

@section('content')
    <div class="container">
        @include('includes.profile_submenu')

        @if($user['list_visibility'] === 0 || (Auth::check() && Auth::user()->username === $user->username))
            <ul class="nav nav-pills nav-justified">
                <li role="presentation" @if(is_null($status)) class="active" @endif>
                    {!! link_to_route('profile.list', 'All', ['username' => $user->username]) !!}
                </li>
                @foreach($listStatuses as $listStatus)
                    <li role="presentation" @if($status === $listStatus->list_status) class="active" @endif>
                        {!! link_to_route('profile.list', $listStatus->description, ['username' => $user->username, 'status' => $listStatus->list_status]) !!}
                    </li>
                @endforeach
            </ul>

            @foreach($listStatuses as $listStatus)
                @if($shows->contains('list_status', $listStatus->list_status))
                    <h4>{{ $listStatus->description }}</h4>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
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
                                            {!! link_to_route('shows.details', $show->SeriesName, ['seriesId' => $show->series_id]) !!}

                                            @if ((Auth::check() && Auth::user()->username === $user->username))
                                                <div class="pull-right">
                                                    @if ($show->favourited)
                                                        <a href="#" onClick="return false;" class="favourite"
                                                           data-series-id="{{ $show->series_id }}"
                                                           title="Remove from Favourites">
                                                            <span class="glyphicon glyphicon-star"
                                                                  aria-hidden="true"></span>
                                                        </a>
                                                    @else
                                                        <a href="#" onClick="return false;" class="favourite"
                                                           data-series-id="{{ $show->series_id }}"
                                                           title="Add to Favourites">
                                                            <span class="glyphicon glyphicon-star-empty"
                                                                  aria-hidden="true"></span>
                                                        </a>
                                                    @endif
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
                                        <td class="text-center">
                                            @if($show->last_episode_watched_formatted)
                                                {!! link_to_route('shows.episode', $show->last_episode_watched_formatted, ['seriesId' => $show->series_id, 'seasonNum' => $show->season_number, 'episodeNum' => $show->episode_number]) !!}
                                            @endif
                                        </td>
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
                    </div>
                @endif
            @endforeach
        @else
            <div class="alert alert-danger">The user has chosen to make their list private. Only they may view it.
            </div>
        @endif

        @if (Auth::check() && Auth::getUser()->username === $user->username)
            @include('includes.modals.update_show')
            @include('includes.modals.remove_show')
        @endif
    </div>
@stop

@section('javascript')
    @if (Auth::check() && Auth::getUser()->username === $user->username)
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

            $('.favourite').click(function () {
                var _this = $(this),
                    url = "{{ route('profile.favourites', ['username' => $user->username]) }}" + "/" + $(this).data('seriesId') + "/update";

                $.ajax({
                    type: "POST",
                    url: url,
                    beforeSend: function (xhr) {
                        var token = $("meta[name='csrf_token']").attr('content');

                        if (token) {
                            return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                        }
                    },
                    data: { seriesId: $(this).data('seriesId') },
                    success: function () {
                        var star = _this.find('span');
                        if (star.hasClass('glyphicon-star')) {
                            star.removeClass('glyphicon-star').addClass('glyphicon-star-empty').parent().prop('title', 'Add to Favourites');
                        } else {
                            star.removeClass('glyphicon-star-empty').addClass('glyphicon-star').parent().prop('title', 'Remove from Favourites');
                        }
                    },
                    error: function () {
                        alert("error!!!!");
                    }
                });
            });
        </script>
    @endif
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
