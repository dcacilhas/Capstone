@extends('search.search')

@section('search_results')
    <h3>Search TV Shows: Results for "{{ $query }}"</h3>

    @include('includes.shows.table')

    {!! $shows->render() !!}

    @if (Auth::check())
        @include('includes.modals.add_show')

        @if(Auth::check())
            @include('includes.modals.update_show')
            @include('includes.modals.remove_show')
        @endif
    @endif
@stop

@section('javascript')
    <script>
        $(document).ready(function () {
            $('#searchBox').focus();
        });

        @if (Auth::check())
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
                var that = $(this),
                    url = "{{ route('profile.favourites', ['username' => Auth::user()->username]) }}" + "/" + that.data('seriesId') + "/update";

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
                        // TODO: Make this so it happens regardless, but reverts on AJAX error for better use experience
                        var star = that.find('span');
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
    </script>
@stop
