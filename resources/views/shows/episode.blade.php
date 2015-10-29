@extends('master')

@section('title', $episode->episodename)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1>
                    {!! link_to_route('shows.details', $series->SeriesName, ['seriesId' => $series->id]) !!}:
                    {{ $episode->episodename }}
                    @if(Auth::check() && $episode->seriesIsOnList)
                        <small>
                            @if($episode->isOnList)
                                <input type="checkbox" id="{{ $episode->id }}" checked/>
                                <label for="{{ $episode->id }}">Watched</label>
                            @else
                                <input type="checkbox" id="{{ $episode->id }}"/>
                                <label for="{{ $episode->id }}">Unwatched</label>
                            @endif
                        </small>
                    @endif
                </h1>
                <h4>Season {{ $episode->season }} Episode {{ $episode->episodenumber }}</h4>

                <ul>
                    <li>Links:
                        <a href="http://www.imdb.com/title/{{ $episode->IMDB_ID }}" target="_blank">IMDB</a>,
                        <a href="http://thetvdb.com/?tab=episode&seriesid={{ $series->id }}&seasonid={{ $episode->season }}&id={{ $episode->id }}&lid=7"
                           target="_blank">TVDB</a>
                    </li>
                    <li>Aired: {{ $episode->firstaired }}</li>
                    <li>Director: {{ $episode->director }}</li>
                    <li>Writer: {{ $episode->writer }}</li>
                </ul>

                <h3>Overview</h3>
                <p>{{ $episode->overview }}</p>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $(document).ready(function () {
            @if(Auth::check() && $episode->seriesIsOnList)
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
            @endif
        });
    </script>
@stop
