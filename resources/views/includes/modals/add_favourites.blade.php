<!-- AddFavouritesModal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addModalLabel">Add To Favourites</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => ['profile.favourites.add', $user->username], 'class' => 'form-horizontal']) !!}

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