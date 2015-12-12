<!-- AddFriendsModal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addModalLabel">Add To Friends</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => ['profile.friends.add', $user->username], 'class' => 'form-horizontal']) !!}
                <p>
                    Enter your friend's username or email address.
                    Use the {!! link_to_route('search', 'search') !!} feature to find new friends.
                </p>
                <div class="form-group">
                    <div class="col-sm-8">
                        {!! Form::input('text', 'friendUsernameOrEmail', null, ['class' => 'form-control', 'placeholder' => 'Username or Email']) !!}
                    </div>
                    <div class="col-sm-4">{!! Form::submit('Send Friend Request', ['class' => 'btn btn-primary']) !!}</div>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
