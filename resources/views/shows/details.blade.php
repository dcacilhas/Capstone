@extends('master')

@section('title', $show->SeriesName)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1>
                    {{ $show->SeriesName }}
                    @if ($isOnList)
                        <small>
                            <a href="#" class="edit"
                               data-toggle="modal"
                               data-target="#addModal"
                               data-series-id="{{ $show->id }}"
                               data-series-title="{{ $show->SeriesName }}"
                               title="Add to List">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                            </a>
                        </small>
                    @endif
                </h1>

                <ul>
                    <li>Genre: {{ $show->Genre }}</li>
                    <li>Rating: {{ $show->Rating }}</li>
                    <li>Status: {{ $show->Status }}</li>
                    <li>Network: {{ $show->Network }}</li>
                    <li>Airs: {{ $show->Airs_DayOfWeek }} at {{ $show->Airs_Time }}</li>
                    <li>Site Rating: {{ $show->SiteRating }}</li>
                    <li>Links: <a href="http://www.imdb.com/title/{{ $show->IMDB_ID }}">IMDB</a></li>
                </ul>

                <h3>Overview</h3>

                <p>{{ $show->Overview }}</p>
            </div>

            <div class="col-md-6">
                <h3>Episodes</h3>
                <a href="#" id="showAllEpisodes">Show All</a> | <a href="#" id="hideAllEpisodes">Hide All</a>
                @foreach($seasons as $season)
                    <h4 class="seasons"><a href="#" class="seasons">Season {{ $season->season }}</a></h4>
                    <div class="episodes">
                        <ol>
                            @foreach($episodes as $episode)
                                @if($episode->season === $season->season)
                                    <li>
                                        @if(!$isOnList)
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
        @endif
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

            // TODO: Add Check/Uncheck All buttons for All Episodes and Seasons only.

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
        });
    </script>
@stop
