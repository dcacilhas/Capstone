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
                        {!! link_to_route('shows/details', $favourite->SeriesName, ['seriesId' => $favourite->series_id]) !!}
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
        <!-- AddModal -->
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="addModalLabel">Add To Favourites</h4>
                    </div>
                    <div class="modal-body">
                        {!! Form::open(['route' => ['profile/favourites/add', $user->username], 'class' => 'form-horizontal']) !!}

                        <div class="form-group">
                            {!! Form::label('favourites', 'Shows to Favourite: ', ['class' => 'col-sm-4 control-label']) !!}

                            <div class="col-sm-8">
                                @foreach($showsNotFavourited as $show)
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="favouritesToAdd[]" value="{{ $show->series_id }}">
                                            {{ $show->SeriesName }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        {!! Form::submit('Add To Favourites', ['class' => 'btn btn-primary']) !!}
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
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="removeModalLabel"></h4>
                    </div>
                    <div class="modal-body">
                        {!! Form::model($favourites, ['route' => ['profile/favourites/remove', $user->username], 'class' => 'form-horizontal']) !!}

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
                            url: "{{ route('profile/favourites/reorder', ['username' => $user->username]) }}",
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
                //            $( "#sortable" ).disableSelection();

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
