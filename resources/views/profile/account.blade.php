@extends('master')

@section('title', 'Edit Profile')

@section('content')
    <div class="container">
        @include('includes.profile_submenu')

        {!! Form::model($user, ['route' => ['profile.postEmail', $user->username], 'class' => 'form-horizontal']) !!}
        @include('errors.errors')

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <h2>Edit Account</h2>

        <h3>Change Email</h3>
        <div class="form-group">
            {!! Form::label('current_email', 'Current: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-10">
                <p class="form-control-static">{{ $user->email }}</p>
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('email', 'New: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-10">
                {!! Form::text('email', '', ['class' => 'form-control', 'placeholder' => 'New Email']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('email_confirmation', 'Confirm: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-10">
                {!! Form::text('email_confirmation', null, ['class' => 'form-control', 'placeholder' => 'Confirm New Email']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('password', 'Password: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-10">
                {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) !!}
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-10 col-md-offset-2">
                {!! Form::submit('Change Email', ['class' => 'btn btn-primary btn-lg btn-block']) !!}
            </div>
        </div>

        {!! Form::close() !!}

        {!! Form::model($user, ['route' => ['profile.postPassword', $user->username], 'class' => 'form-horizontal']) !!}
        <h3>Change Password</h3>

        <div class="form-group">
            {!! Form::label('current_password', 'Current: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-10">
                {!! Form::password('current_password', ['class' => 'form-control', 'placeholder' => 'Current Password']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('new_password', 'New: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-10">
                {!! Form::password('new_password', ['class' => 'form-control', 'placeholder' => 'New Password']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('new_password_confirmation', 'Confirm: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-10">
                {!! Form::password('new_password_confirmation', ['class' => 'form-control', 'placeholder' => 'Confirm New Password']) !!}
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-10 col-md-offset-2">
                {!! Form::submit('Change Password', ['class' => 'btn btn-primary btn-lg btn-block']) !!}
            </div>
        </div>

        {!! Form::close() !!}
    </div>
@stop
