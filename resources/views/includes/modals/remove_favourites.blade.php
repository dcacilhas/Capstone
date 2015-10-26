<!-- RemoveFavouritesModal -->
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