@extends('master')

@section('title', 'Reset Your Password')

@section('content')
    <div class="container">
        <h1>Reset Your Password</h1>

        <div class="panel panel-info">
            <div class="panel-heading">
                <h2 class="panel-title">Reset Your Password</h2>
            </div>

            @include('errors.errors')

            <div class="panel-body">
                {!! Form::open(['route' => 'password.reset']) !!}
                {!! Form::hidden('token', $token) !!}
                <div class="form-group">
                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                        {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email Address']) !!}
                    </div>
                    <div class="form-group">
                        <div style="margin-bottom: 25px" class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div style="margin-bottom: 25px" class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Confirm Password']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::submit('Change Password', ['class' => 'btn btn-primary btn-lg btn-block']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop