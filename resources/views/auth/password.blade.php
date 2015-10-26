@extends('master')

@section('title', 'Forgot Your Password?')

@section('content')
    <div class="container">
        <h1>Forgot Your Password?</h1>

        <div class="panel panel-info">
            <div class="panel-heading">
                <h2 class="panel-title">Forgot Your Password?</h2>
            </div>

            <div class="panel-body">
                @include('errors.errors')
                <p>Enter the email address address associated with your {!! Config::get('app.app_name') !!} account, then
                    click Send Password Reset Link. We'll email you a link to a page where you can create a new
                    password.</p>
                {!! Form::open() !!}
                <div class="form-group">
                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                        {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email Address']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::submit('Send Password Reset Link', ['class' => 'btn btn-primary btn-lg btn-block']) !!}
                </div>
                {!! Form::close() !!}
                <div class="form-group">
                    <div class="col-md-12 control">
                        <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" class="text-center">
                            Do you want to try again? {!! link_to_route('login', 'Log In') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop