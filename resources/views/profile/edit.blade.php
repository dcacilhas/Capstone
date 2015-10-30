@extends('master')

@section('title', 'Edit Profile')

@section('content')
    <div class="container">
        @include('includes.profile_submenu')

        {!! Form::model($user, ['route' => ['profile.postProfile', $user->username], 'class' => 'form-horizontal', 'files' => true]) !!}

        <!-- TODO: Separate errors for each form: http://laravel.com/docs/5.1/validation#other-validation-approaches -->
        @include('errors.errors')

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <h2>Edit Profile</h2>

        <h3>Details</h3>

        <div class="form-group">
            {!! Form::label('avatar', 'Avatar: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-10 text-center">
                <div id="kv-avatar-errors" class="center-block" style="width:800px;display:none"></div>
                <div class="kv-avatar center-block" style="width:200px">
                    <input id="avatar" name="avatar" type="file" class="file-loading">
                </div>
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('gender', 'Gender: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-10">
                {!! Form::select('gender', ['NULL' => '', 'M' => 'Male', 'F' => 'Female'], null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('birthday', 'Birthday: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-10">
                <!-- TODO: Use jQuery UI datepicker instead of HTML5 (not compatible with non-Chrome) -->
                {!! Form::date('birthday', null, ['class' => 'form-control', 'max' => \Carbon\Carbon::now()->toDateString()]) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('location', 'Location: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-10">
                {!! Form::text('location', null, ['class' => 'form-control', 'size' => '40']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('about', 'About Me: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-10">
                {!! Form::textarea('about', null, ['class' => 'form-control']) !!}
            </div>
        </div>


        <h3>Settings</h3>

        <div class="form-group">
            {!! Form::label('notification_email', 'Notification Email: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-10">
                {!! Form::select('notification_email', ['1' => 'Yes', '0' => 'No'], null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <h3>Privacy</h3>

        <div class="form-group">
            {!! Form::label('profile_visibility', 'Profile Visibility: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-10">
                {!! Form::select('profile_visibility', ['0' => 'Public', '1' => 'Private', '2' => 'Friends Only'], null, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('list_visibility', 'List Visibility: ', ['class' => 'col-sm-2 control-label']) !!}
            <div class="col-md-10">
                {!! Form::select('list_visibility', ['0' => 'Public', '1' => 'Private', '2' => 'Friends Only'], null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-10 col-md-offset-2">
                {!! Form::submit('Save Profile', ['class' => 'btn btn-primary btn-lg btn-block']) !!}
            </div>
        </div>

        {!! Form::close() !!}
    </div>
@stop

@section('css')
    <link href="{{ asset('assets/css/vendor/fileinput.min.css') }}" media="all" rel="stylesheet" type="text/css" />
    <style>
        .kv-avatar .file-preview-frame,.kv-avatar .file-preview-frame:hover {
            margin: 0;
            padding: 0;
            border: none;
            box-shadow: none;
            text-align: center;
        }
        .kv-avatar .file-input {
            display: table-cell;
            max-width: 220px;
        }
    </style>
@stop

@section('javascript')
    <script src="{{ asset('assets/js/vendor/fileinput.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#avatar").fileinput({
                overwriteInitial: true,
                maxFileSize: 1500,
                showClose: false,
                showCaption: false,
                browseLabel: '',
                removeLabel: '',
                browseIcon: '<i class="glyphicon glyphicon-folder-open"></i>',
                removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
                removeTitle: 'Cancel or reset changes',
                elErrorContainer: '#kv-avatar-errors',
                msgErrorClass: 'alert alert-block alert-danger',
                defaultPreviewContent: '{!! Html::image($user->avatar->url(), 'Avatar') !!}',
                layoutTemplates: {main2: '{preview} {remove} {browse}'},
                allowedFileExtensions: ["jpeg", "png", "gif", "svg", "jpg"],
                previewSettings: {
                    image: {width: "128px", height: "128px"}
                }
            });
        });
    </script>
@stop
