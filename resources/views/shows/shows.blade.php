@extends('master')

@section('title', 'Shows')

@section('content')
    <div class="container">
        <h1>TV Shows</h1>

        <p>Select a filter below to display a list of TV Shows.</p>

        @include('includes.shows.submenu')

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if (isset($shows))
            <h3>
                @if(isset($selectedGenre))
                    Genre: {{ $selectedGenre }}
                @elseif(isset($selectedFilter))
                    Shows Starting With: {{ $selectedFilter }}
                @endif
            </h3>

            @include('includes.shows.table')

            @if (isset($selectedFilter))
                {!! $shows->appends(['filter' => urlencode($selectedFilter)])->render() !!}
            @else
                {!! $shows->appends(['genre' => $selectedGenre])->render() !!}
            @endif
        @endif

        @if (Auth::check())
            @include('includes.modals.add_show')

            @if(isset($shows))
                @include('includes.modals.update_show')
                @include('includes.modals.remove_show')
            @endif
        @endif
    </div>
@stop

@section('javascript')
    @if (Auth::check())
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
                        url = "{{ route('profile.favourites', ['username' => Auth::user()->username]) }}" + "/" + $(this).data('seriesId') + "/update";

                $.ajax({
                    type: "POST",
                    url: url,
                    beforeSend: function (xhr) {
                        var token = $("meta[name='csrf_token']").attr('content');

                        if (token) {
                            return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                        }
                    },
                    data: {seriesId: $(this).data('seriesId')},
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
        .dropdown-menu {
            min-width: 200px;
        }

        .dropdown-menu.columns-2 {
            min-width: 400px;
        }

        .dropdown-menu.columns-3 {
            /*min-width: 600px;*/
            width: 100%;
        }

        .dropdown-menu li a {
            padding: 5px 15px;
            font-weight: 300;
        }

        .multi-column-dropdown {
            list-style: none;
            padding-left: 0;
        }

        .multi-column-dropdown li a {
            display: block;
            clear: both;
            line-height: 1.428571429;
            color: #333;
            white-space: normal;
        }

        .multi-column-dropdown li a:hover {
            text-decoration: none;
            color: #262626;
            background-color: #f5f5f5;
        }

        .multi-column-dropdown li.active > a {
            color: #fff;
            text-decoration: none;
            background-color: #337ab7;
            outline: 0;
        }

        @media (max-width: 767px) {
            .dropdown-menu.multi-column {
                min-width: 240px !important;
                overflow-x: hidden;
            }
        }
    </style>
@stop
