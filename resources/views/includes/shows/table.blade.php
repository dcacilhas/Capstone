<div class="table-responsive">
    <table class="table table-striped table-bordered">
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
                    @if (Auth::check())
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
</div>