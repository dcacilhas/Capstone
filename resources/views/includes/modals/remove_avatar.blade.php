<!-- RemoveAvatarModal -->
<div class="modal fade" id="removeAvatarModal" tabindex="-1" role="dialog" aria-labelledby="removeAvatarModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="removeAvatarModalLabel">Remove Avatar</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove your avatar?</p>
            </div>
            <div class="modal-footer">
                {!! Form::model($user, ['route' => ['profile.removeAvatar']]) !!}
                <button class="btn btn-primary" id="submit">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>