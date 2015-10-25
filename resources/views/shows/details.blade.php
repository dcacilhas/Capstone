@extends('master')

@section('title', $show->SeriesName)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1>
                    {{ $show->SeriesName }}
                    @if (Auth::check())
                        <small>
                            @if (!$list)
                                <a href="#" class="add"
                                   data-toggle="modal"
                                   data-target="#addModal"
                                   data-series-id="{{ $show->id }}"
                                   data-series-title="{{ $show->SeriesName }}"
                                   title="Add to List">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                </a>
                            @else
                                @if ($favourited)
                                    <a href="#" id="favourite"
                                       data-series-id="{{ $show->id }}"
                                       title="Remove from Favourites">
                                        <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                    </a>
                                @else
                                    <a href="#" id="favourite" data-series-id="{{ $show->id }}"
                                       title="Add to Favourites">
                                        <span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>
                                    </a>
                                @endif

                                <a href="#" class="edit"
                                   data-toggle="modal"
                                   data-target="#updateModal"
                                   data-series-id="{{ $list->series_id }}"
                                   data-series-title="{{ $show->SeriesName }}"
                                   data-series-rating="{{ $list->rating }}"
                                   data-series-status="{{ $list->list_status }}"
                                   title="Edit">
                                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                </a>
                                <a href="#" class="remove"
                                   data-toggle="modal"
                                   data-target="#removeModal"
                                   data-series-id="{{ $list->series_id }}"
                                   data-series-title="{{ $show->SeriesName }}"
                                   title="Remove">
                                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                </a>
                            @endif
                        </small>
                    @endif
                </h1>

                <ul>
                    <li>Genre: {{ $show->Genre }}</li>
                    <li>Rating: {{ $show->Rating }}</li>
                    <li>Status: {{ $show->Status }}</li>
                    <li>Network: {{ $show->Network }}</li>
                    <li>Airs: {{ $show->Airs_DayOfWeek }} at {{ $show->Airs_Time }}</li>
                    @if(Auth::check() && $list)
                        <li>Your Rating: {{ $list->rating }}</li>
                    @endif
                    <li>Site Rating: {{ $show->SiteRating }}</li>
                    <li>Links:
                        <a href="http://www.imdb.com/title/{{ $show->IMDB_ID }}">IMDB</a>,
                        <a href="http://thetvdb.com/?tab=series&id={{ $show->id }}">TVDB</a>
                    </li>
                </ul>

                <h3>Overview</h3>

                <p>{{ $show->Overview }}</p>
            </div>

            <div class="col-md-6">
                <h3>Episodes</h3>
                <a href="#" id="showAllEpisodes">Show All</a> | <a href="#" id="hideAllEpisodes">Hide All</a> <br>
                @if ($list)
                    <a href="#" id="checkAllEpisodes">Check All</a> | <a href="#" id="uncheckAllEpisodes">Uncheck All</a>
                @endif
                @foreach($seasons as $season)
                    <h4 class="seasons"><a href="#" class="seasons">Season {{ $season->season }}</a></h4>
                    <div class="episodes">
                    @if ($list)
                        <a href="#" class="checkSeason">Check Season {{ $season->season }}</a> | <a href="#" class="uncheckSeason">Uncheck Season {{ $season->season }}</a>
                    @endif
                        <ol>
                            @foreach($episodes as $episode)
                                @if($episode->season === $season->season)
                                    <li>
                                        @if($list)
                                            <input type="checkbox" id="{{ $episode->id }}" class="episode" {{ $episode->checked or '' }} />
                                        @endif
                                        {!! link_to_route('shows/episode', $episode->episodename, ['seriesId' => $episode->seriesid, 'seasonNum' => $season->season, 'episodeNum' => $episode->episodenumber]) !!}
                                    </li>
                                @endif
                            @endforeach
                        </ol>
                    </div>
                @endforeach
            </div>
        </div>

        @if (Auth::check())
            @if (!$list)
                <!-- AddModal -->
                <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="addModalLabel"></h4>
                            </div>
                            <div class="modal-body">
                                {!! Form::open(['route' => ['profile/list/add', $user->username], 'class' => 'form-horizontal']) !!}

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
            @else
                <!-- UpdateModal -->
                <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="updateModalLabel"></h4>
                            </div>
                            <div class="modal-body">
                                {!! Form::model($show, ['route' => ['profile/list/update', $user->username], 'class' => 'form-horizontal']) !!}

                                {!! Form::hidden('series_id', null, ['id' => 'series_id']) !!}

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
                                {!! Form::model($show, ['route' => ['profile/list/remove', $user->username], 'class' => 'form-horizontal']) !!}

                                {!! Form::hidden('series_id', null, ['id' => 'series_id']) !!}

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
            @endif
        @endif
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $(document).ready(function() {
            $('.seasons').click(function () {
                $(this).next().toggle();
            });

            $('#showAllEpisodes').click(function() {
                $('.episodes').show();
            });

            $('#hideAllEpisodes').click(function() {
                $('.episodes').hide();
            });

            @if (Auth::check())
                @if(!$list)
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
                @else
                    $('#checkAllEpisodes').click(function() {
                        $('.episode:not(:checked)').prop('checked', true).trigger('change');
                    });

                    $('#uncheckAllEpisodes').click(function() {
                        $('.episode:checked').prop('checked', false).trigger('change');
                    });

                    $('.checkSeason').click(function() {
                        $(this).siblings().find('.episode:not(:checked)').prop('checked', true).trigger('change');
                    });

                    $('.uncheckSeason').click(function() {
                        $(this).siblings().find('.episode:checked').prop('checked', false).trigger('change');
                    });

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

                    // TODO: Cleanup. Maybe add success/error messages for user.
                    $('.episode').change(function () {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('list/updateListEpisodesWatched', ['seriesId' => $show->id]) }}",
                            beforeSend: function (xhr) {
                                var token = $("meta[name='csrf_token']").attr('content');

                                if (token) {
                                    return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                                }
                            },
                            data: { episodeId: $(this).attr('id') },
        //                    success: function (data) {
        //                        alert(data);
        //                    },
                            error: function () {
                                alert("error!!!!");
                            }
                        });
                    });
                @endif

                $('#favourite').click(function () {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('profile/favourites/update', ['seriesId' => $show->id]) }}",
                        beforeSend: function (xhr) {
                            var token = $("meta[name='csrf_token']").attr('content');

                            if (token) {
                                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                            }
                        },
                        data: { seriesId: $(this).data('seriesId') },
                        success: function () {
                            var star = $('#favourite').find('span');
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
            @endif
        });
    </script>
@stop
