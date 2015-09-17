@extends('master')

@section('title', 'Edit Profile')

@section('content')
    <div class="container">
        <h1>Edit Profile</h1>

        <pre>{{ json_encode($user, JSON_PRETTY_PRINT) }}</pre>

        {!! link_to_route('profile', 'Profile', ['username' => Auth::user()->username]) !!}
        {!! link_to_route('profile/edit', 'Edit Profile', ['username' => Auth::user()->username]) !!}
        {!! link_to_route('profile/account', 'Edit Account', ['username' => Auth::user()->username]) !!}

        {!! Form::model($user, ['route' => ['profile/postProfile', $user->username], 'class' => 'form-horizontal']) !!}

                <!-- TODO: Separate errors for each form: http://laravel.com/docs/5.1/validation#other-validation-approaches -->
        @include('errors.errors')

        <h2>Details</h2>

        <div class="form-group">
            {!! Form::label('gender', 'Gender: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
                {!! Form::select('gender', ['NULL' => '', 'M' => 'Male', 'F' => 'Female'], null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('birthday', 'Birthday: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
                <!-- TODO: Use jQuery UI datepicker instead of HTML5 (not compatible with non-Chrome) -->
                {!! Form::date('birthday', null, ['class' => 'form-control', 'max' => \Carbon\Carbon::now()->toDateString()]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('location', 'Location: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
                {!! Form::text('location', null, ['class' => 'form-control', 'size' => '40']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('about', 'About Me: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
                {!! Form::textarea('about', null, ['class' => 'form-control']) !!}
            </div>
        </div>


        <h2>Settings</h2>

        <div class="form-group">
            {!! Form::label('notification_email', 'Notification Email: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
                {!! Form::select('notification_email', ['1' => 'Yes', '0' => 'No'], null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <h2>Privacy</h2>

        <div class="form-group">
            {!! Form::label('profile_visibility', 'Profile Visibility: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
                {!! Form::select('profile_visibility', ['0' => 'Public', '1' => 'Private', '2' => 'Friends Only'], null, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('list_visibility', 'List Visibility: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-sm-10">
                {!! Form::select('list_visibility', ['0' => 'Public', '1' => 'Private', '2' => 'Friends Only'], null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::submit('Save Profile', ['class' => 'btn btn-primary btn-lg btn-block']) !!}
        </div>

        {!! Form::close() !!}
    </div>
@stop
