@extends('master')

@section('title', 'Home')

@section('content')
    <style>
        /* TODO: Clean up and include in CSS file */

        /*
        * Base structure
        */

        html,
        body {
            height: 100%;
        }

        body {
            text-align: center;
        }

        /* Extra markup and styles for table-esque vertical and horizontal centering */
        .site-wrapper {
            display: table;
            width: 100%;
            height: 100%; /* For at least Firefox */
            min-height: 100%;
        }

        .site-wrapper-inner {
            display: table-cell;
            vertical-align: top;
        }

        .cover-container {
            margin-right: auto;
            margin-left: auto;
        }

        /* Padding for spacing */
        .inner {
            padding: 30px;
        }

        /*
        * Cover
        */

        .cover {
            padding: 0 20px;
        }

        /*
        * Affix and center
        */

        @media (min-width: 768px) {
            /* Start the vertical centering */
            .site-wrapper-inner {
                vertical-align: middle;
            }

            /* Handle the widths */
            .cover-container {
                width: 100%; /* Must be percentage or pixels for horizontal alignment */
            }
        }

        @media (min-width: 992px) {
            .cover-container {
                width: 700px;
            }
        }
    </style>
    
    <div class="site-wrapper">
        <div class="site-wrapper-inner">
            <div class="cover-container">
                <div class="inner cover">
                    <h1 class="cover-heading">MyMediaList</h1>
                    <br>
                    <p class="lead">Track the TV shows you're watching. Invite your friends and share your progress.</p>

                    @if (!Auth::check())
                        <p class="lead">
                            {!! link_to_route('register', 'Register', [], ['class' => 'btn btn-lg btn-default']) !!}
                            or
                            {!! link_to_route('login', 'Log In', [], ['class' => 'btn btn-lg btn-default']) !!}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
