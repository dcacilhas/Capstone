@extends('master')

@section('title', 'Edit Profile')

@section('content')
    <div class="container">
        <h1>Edit Profile</h1>

        <pre>{{ json_encode($user, JSON_PRETTY_PRINT) }}</pre>

        {!! link_to_route('profile', 'Profile', ['username' => Auth::user()->username]) !!}
        {!! link_to_route('profile/edit', 'Edit Profile', ['username' => Auth::user()->username]) !!}
        {!! link_to_route('profile/account', 'Edit Account', ['username' => Auth::user()->username]) !!}

        {!! Form::model($user, ['route' => ['profile/postEmail', $user->username], 'class' => 'form-horizontal']) !!}
        @include('errors.errors')

        <h2>Change Email</h2>

        <div class="form-group">
            {!! Form::label('current_email', 'Current: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
                <p class="form-control-static">{{ $user->email }}</p>
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('email', 'New: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
                {!! Form::text('email', '', ['class' => 'form-control', 'placeholder' => 'New Email']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('email_confirmation', 'Confirm: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
                {!! Form::text('email_confirmation', null, ['class' => 'form-control', 'placeholder' => 'Confirm New Email']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('password', 'Password: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
                {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::submit('Change Email', ['class' => 'btn btn-primary btn-lg btn-block']) !!}
        </div>

        {!! Form::close() !!}

        {!! Form::model($user, ['route' => ['profile/postPassword', $user->username], 'class' => 'form-horizontal']) !!}
        @include('errors.errors')

        <h2>Change Password</h2>

        <div class="form-group">
            {!! Form::label('current_password', 'Current: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
                {!! Form::password('current_password', ['class' => 'form-control', 'placeholder' => 'Current Password']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('password', 'New: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
                {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'New Password']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('password_confirmation', 'Confirm: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
                {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Confirm New Password']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::submit('Change Password', ['class' => 'btn btn-primary btn-lg btn-block']) !!}
        </div>

        {!! Form::close() !!}
    </div>
@stop
