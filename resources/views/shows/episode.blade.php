@extends('master')

@section('title', $episode->EpisodeName)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1>
                    {!! link_to_route('shows.details', $series->SeriesName, ['seriesId' => $series->id]) !!}:
                    {{ $episode->EpisodeName }}
                    @if($series->isOnList)
                        <small>
                            @if($episode->isWatched)
                                <input type="checkbox" id="{{ $episode->id }}" checked/>
                                <label for="{{ $episode->id }}">Watched</label>
                            @else
                                <input type="checkbox" id="{{ $episode->id }}"/>
                                <label for="{{ $episode->id }}">Unwatched</label>
                            @endif
                        </small>
                    @endif
                </h1>
                <h4>Season {{ $season->season }} Episode {{ $episode->EpisodeNumber }}</h4>

                <ul>
                    <li>Links:
                        @if($episode->IMDB_ID)
                            <a href="http://www.imdb.com/title/{{ $episode->IMDB_ID }}" target="_blank">IMDB</a>,
                        @endif
                        <a href="http://thetvdb.com/?tab=episode&seriesid={{ $series->id }}&seasonid={{ $episode->season }}&id={{ $episode->id }}&lid=7"
                           target="_blank">TVDB</a>
                    </li>
                    <li>Aired: {{ $episode->FirstAired->format('F j, Y') }}</li>
                    <li>Director: {{ $episode->Director }}</li>
                    <li>Writer: {{ $episode->Writer }}</li>
                </ul>

                <h3>Overview</h3>
                <p>{{ $episode->Overview }}</p>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    @if($series->isOnList)
        <script>
            $(document).ready(function () {
                // TODO: Cleanup. Maybe add success/error messages for user.
                $('#{{ $episode->id }}').click(function () {
                    var that = $(this);
                    toggleLabel(that);
                    $.ajax({
                        type: "POST",
                        url: "{{ route('list.episodes.update', ['seriesId' => $episode->seriesid]) }}",
                        beforeSend: function (xhr) {
                            var token = $("meta[name='csrf_token']").attr('content');
                            if (token) {
                                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                            }
                        },
                        data: {episodeIds: $(this).attr('id')},
                        error: function () {
                            that.prop('checked', !that.prop('checked'));
                            toggleLabel(that);
                            alert("error!!!!");
                        }
                    });

                    function toggleLabel(checkbox) {
                        var checked = checkbox.prop('checked'),
                                label = $('label[for="' + checkbox.attr('id') + '"]');
                        label.text(checked ? 'Watched' : 'Unwatched');
                    }
                });
            });
        </script>
    @endif
@stop
