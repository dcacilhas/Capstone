<!-- UpdateModal -->
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="updateModalLabel"></h4>
            </div>
            <div class="modal-body">
                {!! Form::model($shows, ['route' => ['profile/list/update', $user->username], 'class' => 'form-horizontal']) !!}

                {!! Form::hidden('series_id', null, ['id' => 'series_id']) !!}

                <div class="form-group">
                    {!! Form::label('rating', 'Rating: ', ['class' => 'col-sm-2 control-label']) !!}
                    <div class="col-sm-2">
                        {!! Form::selectRange('rating', 1, 10, null, ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('status', 'Status: ', ['class' => 'col-sm-2 control-label']) !!}

                    <div class="col-sm-10">
                        @foreach($listStatuses as $listStatus)
                            <div class="radio">
                                <label>
                                    {!! Form::radio('status', $listStatus->list_status, null, ['id' => 'status_' . $listStatus->list_status]) !!}
                                    {{ $listStatus->description }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                {!! Form::submit('Update Show', ['class' => 'btn btn-primary']) !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>