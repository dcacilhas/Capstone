@extends('master')

@section('title', 'Favourites')

@section('content')
    <div class="container">
        @include('includes.profile_submenu')

        @if($user['list_visibility'] === 0 || (Auth::check() && Auth::user()->username === $user->username))
            <h2>
                Favourite Shows
                @if (Auth::check() && Auth::getUser()->username === $user->username)
                    <small>
                        <a href="#" class="add"
                           data-toggle="modal"
                           data-target="#addModal"
                           title="Add to Favourites">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                        </a>
                    </small>
                @endif
            </h2>

            @if (Auth::check() && Auth::getUser()->username === $user->username)
                <p>Click and drag one of your favourites to reorder the list. Your first five favourites will be
                    displayed
                    in your profile.</p>
            @endif

            <ol id="sortable">
                <?php $i = 1; ?>
                @foreach($favourites as $favourite)
                    <li id="item-{{ $favourite->series_id }}">
                        {!! link_to_route('shows.details', $favourite->SeriesName, ['seriesId' => $favourite->series_id]) !!}
                        @if (Auth::check() && Auth::getUser()->username === $user->username)
                            <a href="#" class="remove"
                               data-toggle="modal"
                               data-target="#removeModal"
                               data-series-id="{{ $favourite->series_id }}"
                               data-series-title="{{ $favourite->SeriesName }}"
                               title="Remove">
                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                            </a>
                        @endif
                    </li>
                @endforeach
            </ol>
        @else
            <div class="alert alert-danger">The user has chosen to make their list private. Only they may view it.</div>
        @endif
    </div>

    @if (Auth::check() && Auth::getUser()->username === $user->username)
        @include('includes.modals.add_favourites')
        @include('includes.modals.remove_favourites')
    @endif
@stop

@section('javascript')
    @if (Auth::check() && Auth::getUser()->username === $user->username)
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#sortable').sortable({
                    placeholder: 'sortable-placeholder',
                    update: function (event, ui) {
                        var data = $(this).sortable('serialize');

                        $.ajax({
                            type: "POST",
                            url: "{{ route('profile.favourites.reorder', ['username' => $user->username]) }}",
                            beforeSend: function (xhr) {
                                var token = $("meta[name='csrf_token']").attr('content');

                                if (token) {
                                    return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                                }
                            },
                            data: data,
//                            success: function () {
//                                alert('success');
//                            },
                            error: function () {
                                alert("error!!!!");
                            }
                        });
                    }
                });

                $('#removeModal').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget),
                            title = button.data('series-title'),
                            id = button.data('series-id'),
                            modal = $(this);
                    modal.find('#series_id').val(id);
                    modal.find('.modal-title').text(title);
                    modal.find('p').html("Are you sure you want to remove <strong>" + title + "</strong> from your favourites?");
                });
            });
        </script>
    @endif
@stop

@section('css')
    <style>
        .sortable-placeholder {
            height: 20px;
            display: block;
        }
    </style>
@stop
