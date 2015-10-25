@extends('master')

@section('title', $episode->episodename)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1>
                    {!! link_to_route('shows/details', $series->SeriesName, ['seriesId' => $series->id]) !!}:
                    {{ $episode->episodename }}
                    @if(Auth::check() && $episode->seriesIsOnList)
                        <small>
                            @if($episode->isOnList)
                                <input type="checkbox" id="{{ $episode->id }}" checked />
                                <label for="{{ $episode->id }}">Watched</label>
                            @else
                                <input type="checkbox" id="{{ $episode->id }}" />
                                <label for="{{ $episode->id }}">Unwatched</label>
                            @endif
                        </small>
                    @endif
                </h1>
                <h4>Season {{ $episode->season }} Episode {{ $episode->episodenumber }}</h4>

                <ul>
                    <li>Links:
                        <a href="http://www.imdb.com/title/{{ $episode->IMDB_ID }}">IMDB</a>,
                        <a href="http://thetvdb.com/?tab=episode&seriesid={{ $series->id }}&seasonid={{ $episode->season }}&id={{ $episode->id }}&lid=7">TVDB</a>
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
        $(document).ready(function() {
            @if(Auth::check() && $episode->seriesIsOnList)
                // TODO: Cleanup. Maybe add success/error messages for user.
                $('#{{ $episode->id }}').change(function () {
                    if (this.checked) {
                        $('label[for="' + this.id + '"]').html('Watched');
                        $('#' + this.id  +'').prop('checked', true);
                    } else {
                        $('label[for="' + this.id + '"]').html('Unwatched');
                        $('#' + this.id  +'').prop('checked', false);
                    }

                    $.ajax({
                        type: "POST",
                        url: "{{ route('list/updateListEpisodesWatched', ['seriesId' => $episode->seriesid]) }}",
                        beforeSend: function (xhr) {
                            var token = $("meta[name='csrf_token']").attr('content');

                            if (token) {
                                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                            }
                        },
                        data: { episodeId: $(this).attr('id') },
//                        success: function (data) {
//
//                        },
                        error: function () {
                            alert("error!!!!");
                        }
                    });
                });
            @endif
        });
    </script>
@stop
