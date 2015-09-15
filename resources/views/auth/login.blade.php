@extends('master')

@section('title', 'Log In')

@section('content')
    <h1>Log In</h1>

    <div class="panel panel-info">
        <div class="panel-heading">
            <h2 class="panel-title">Log In</h2>
        </div>

        <div class="panel-body">
            @include('errors.errors')

            {!! Form::open() !!}
            <div class="form-group">
                <div style="margin-bottom: 25px" class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                    <!-- TODO: Add username OR email login: https://gist.github.com/AlexanderPoellmann/61feafa59963854009b7 -->
                    {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email Address']) !!}
                </div>
                <div class="form-group">
                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) !!}
                    </div>
                </div>
            </div>
            <div class="input-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember" > Remember Me
                    </label>
                </div>
            </div>
            <div class="form-group">
                {!! Form::submit('Login', ['class' => 'btn btn-primary btn-lg btn-block']) !!}
            </div>
            {!! Form::close() !!}
            <div class="form-group">
                <div class="col-md-12 control">
                    <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" class="text-center">
                        {!! link_to_route('password/email', 'Forgot your password?') !!} <br>
                        Don't have an account? {!! link_to_route('register', 'Register') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
