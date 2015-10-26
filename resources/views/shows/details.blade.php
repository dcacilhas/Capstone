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
                                <a href="#" onClick="return false;" class="add"
                                   data-toggle="modal"
                                   data-target="#addModal"
                                   data-series-id="{{ $show->id }}"
                                   data-series-title="{{ $show->SeriesName }}"
                                   title="Add to List">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                </a>
                            @else
                                @if ($favourited)
                                    <a href="#" onClick="return false;" class="favourite"
                                       data-series-id="{{ $show->id }}"
                                       title="Remove from Favourites">
                                        <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                    </a>
                                @else
                                    <a href="#" onClick="return false;" class="favourite"
                                       data-series-id="{{ $show->id }}"
                                       title="Add to Favourites">
                                        <span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>
                                    </a>
                                @endif

                                <a href="#" onClick="return false;" class="edit"
                                   data-toggle="modal"
                                   data-target="#updateModal"
                                   data-series-id="{{ $list->series_id }}"
                                   data-series-title="{{ $show->SeriesName }}"
                                   data-series-rating="{{ $list->rating }}"
                                   data-series-status="{{ $list->list_status }}"
                                   title="Edit">
                                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                </a>
                                <a href="#" onClick="return false;" class="remove"
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
                <a href="#" onClick="return false;" id="showAllEpisodes">Show All</a> | <a href="#" onClick="return false;" id="hideAllEpisodes">Hide All</a> <br>
                @if ($list)
                    <a href="#" onClick="return false;" id="checkAllEpisodes">Check All</a> | <a href="#" onClick="return false;" id="uncheckAllEpisodes">Uncheck All</a>
                @endif
                @foreach($seasons as $season)
                    <h4 class="seasons"><a href="#" onClick="return false;" onClick="return false;" class="seasons">Season {{ $season->season }}</a></h4>
                    <div class="episodes">
                    @if ($list)
                        <a href="#" onClick="return false;" class="checkSeason">Check Season {{ $season->season }}</a> | <a href="#" onClick="return false;" class="uncheckSeason">Uncheck Season {{ $season->season }}</a>
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
                @include('includes.modals.add_show')
            @else
                @include('includes.modals.update_show', ['shows' => $show])
                @include('includes.modals.remove_show', ['shows' => $show])
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

                $('.favourite').click(function () {
                    var _this = $(this);
                    $.ajax({
                        type: "POST",
                        url: "{{ route('profile/favourites/update', ['username' => $user->username, 'seriesId' => $show->id]) }}",
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
            @endif
        });
    </script>
@stop
