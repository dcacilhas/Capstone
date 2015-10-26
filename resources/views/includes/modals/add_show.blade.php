<!-- AddShowModal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addModalLabel"></h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => ['profile/list/add', $user->username], 'class' => 'form-horizontal']) !!}

                {!! Form::hidden('series_id', null, ['id' => 'series_id']) !!}
                {!! Form::hidden('series_name', null, ['id' => 'series_name']) !!}

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
                {!! Form::submit('Add Show To List', ['class' => 'btn btn-primary']) !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>