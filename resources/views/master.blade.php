<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf_token" content="{{ csrf_token() }}" />
    <title>@yield('title') - {{ Config::get('app.app_name') }}</title>

    <!-- Bootstrap core CSS -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <style>
        body {
            padding-top: 60px;
            /* Margin bottom by footer height */
            margin-bottom: 60px;
            overflow-y: scroll;
        }
        /* Sticky footer styles
-------------------------------------------------- */
        html {
            position: relative;
            min-height: 100%;
        }
        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            /* Set the fixed height of the footer here */
            height: 60px;
            padding-top: 20px;
            background-color: #f5f5f5;
        }
        @media (min-width: 768px) {
            .navbar-form {
                padding: 0;
            }
        }
        .filter-text {
            margin-right: 5px;
        }
    </style>
    @yield('css')
</head>

<body>

<div class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">{{ Config::get('app.app_name') }} <sup>alpha</sup></a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                @if (Auth::check())
                    <li class="{{ starts_with(Request::route()->getName(), 'profile') ? 'active' : '' }}">
                        {!! link_to_route('profile', 'Profile', ['username' => Auth::user()->username]) !!}
                    </li>
                @endif
                <li class="{{ starts_with(Request::route()->getName(), 'shows') ? 'active' : '' }}">{!! link_to_route('shows', 'TV Shows') !!}</li>
                <li class="{{ starts_with(Request::route()->getName(), 'search') ? 'active' : '' }}">{!! link_to_route('search', 'Search') !!}</li>
                @if (Auth::check())
                    <li>{!! link_to_route('logout', 'Log Out') !!}</li>
                @else
                    <li class="{{ isActiveRoute('login') }}">{!! link_to_route('login', 'Log In') !!}</li>
                    <li class="{{ isActiveRoute('register') }}">{!! link_to_route('register', 'Register') !!}</li>
                @endif
            </ul>

            @if(!starts_with(Request::route()->getName(), 'search'))
                {!! Form::open(['route'=> 'search', 'class' => 'search-form navbar-form navbar-right', 'role' => 'search']) !!}
                    @include('search.searchbox')
                {!! Form::close() !!}
            @endif
        </div>
        <!--/.nav-collapse -->
    </div>
</div>

@yield('content')

<footer class="footer">
    <div class="container">
        <p class="text-muted text-center">
            {!! link_to_route('about', 'About') !!}
            &nbsp;&nbsp;&nbsp;
            <!-- TODO: Change mailto to config value -->
            {!! Html::mailto('david.cacilhas@mohawkcollege.ca', 'Contact Us') !!}</p>
    </div>
</footer>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        $('.filter-select').click(function () {
            $('.search-form').find('.filter-text').text($(this).text());
        });

        $('.search-form').submit(function (event) {
            var that = $(this),
                query = that.find('.search-box').val().trim(),
                url = that.attr('action'),
                filter = that.find('.filter-text').text();
            that.attr('action', url + '/' + filter.toLowerCase() + '/' + query);
        });
    })
</script>
@yield('javascript')
</body>
</html>
