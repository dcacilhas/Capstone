@extends('master')

@section('title', 'Register')

@section('content')
    <h1>Register</h1>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h2 class="panel-title">Register</h2>
        </div>

        <div class="panel-body">
            @include('errors.errors')

            {!! Form::open() !!}
            <div class="form-group">
                <div style="margin-bottom: 25px" class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    {!! Form::text('username', null, ['class' => 'form-control', 'placeholder' => 'Username']) !!}
                </div>
            </div>
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
                <div style="margin-bottom: 25px" class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Confirm Password']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::submit('Register', ['class' => 'btn btn-primary btn-lg btn-block']) !!}
            </div>
            {!! Form::close() !!}
            <div class="form-group">
                <div class="col-md-12 control">
                    <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" class="text-center">
                        Already have an account? {!! link_to_route('login', 'Log In') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
