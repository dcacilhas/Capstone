@extends('search.search')

@section('search_results')
    <table class="table table-striped table-bordered">
        <caption>Search results for "{{ $query }}"</caption>
        <thead>
        <tr>
            <th class="col-md-1 text-center">#</th>
            <th class="col-md-4">Series Title</th>
            <th class="col-md-1 text-center">Status</th>
            <th class="col-md-1 text-center">First Aired</th>
            <th class="col-md-1 text-center">Network</th>
            <th class="col-md-1 text-center">Runtime (mins)</th>
            <th class="col-md-1 text-center">Rating</th>
            <th class="col-md-1 text-center">Site Rating</th>
        </tr>
        </thead>
        <tbody>
        <?php $i = $shows->firstItem(); ?>
        @foreach($shows as $show)
            <tr>
                <th scope="row" class="text-center">{{ $i++ }}</th>
                <td>
                    {!! link_to_route('shows.details', $show->SeriesName, ['id' => $show->id]) !!}
                    @if ((Auth::check()))
                        <div class="pull-right">
                            @if (!$show->is_in_list)
                                <a href="#" class="add"
                                   data-toggle="modal"
                                   data-target="#addModal"
                                   data-series-id="{{ $show->id }}"
                                   data-series-title="{{ $show->SeriesName }}"
                                   title="Add to List">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                </a>
                            @else
                                @if ($show->favourited)
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
                                <a href="#" class="edit"
                                   data-toggle="modal"
                                   data-target="#updateModal"
                                   data-series-id="{{ $show->id }}"
                                   data-series-title="{{ $show->SeriesName }}"
                                   data-series-rating="{{ $show->rating }}"
                                   data-series-status="{{ $show->list_status }}"
                                   title="Edit">
                                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                </a>
                                <a href="#" class="remove"
                                   data-toggle="modal"
                                   data-target="#removeModal"
                                   data-series-id="{{ $show->id }}"
                                   data-series-title="{{ $show->SeriesName }}"
                                   title="Remove">
                                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                </a>
                            @endif
                        </div>
                    @endif
                </td>
                <td class="text-center">{{ $show->Status }}</td>
                <td class="text-center">{{ $show->FirstAired }}</td>
                <td class="text-center">{{ $show->Network }}</td>
                <td class="text-center">{{ $show->Runtime }}</td>
                <td class="text-center">{{ $show->Rating }}</td>
                <td class="text-center">{{ $show->SiteRating }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {!! $shows->render() !!}

    @if (Auth::check())
        @include('includes.modals.add_show')

        @if(isset($shows))
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
    </script>
@stop
