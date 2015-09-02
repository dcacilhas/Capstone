<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>@yield('title') - Capstone</title>

    <!-- Bootstrap core CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <style>
        body {
            padding-top: 50px;
        }

        .starter-template {
            padding: 40px 15px;
            text-align: center;
        }

        /* Sticky footer styles
-------------------------------------------------- */
        html {
            position: relative;
            min-height: 100%;
        }

        body {
            /* Margin bottom by footer height */
            margin-bottom: 60px;
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            /* Set the fixed height of the footer here */
            height: 60px;
            background-color: #f5f5f5;
        }
    </style>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Capstone</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                {{--<li class="active"><a href="/">Home</a></li>--}}
                {{--<li><a href="about">Profile</a></li>--}}
                {{--<li><a href="shows">TV Shows</a></li>--}}
                {{--<li><a href="login">Log In</a></li>--}}
                {{--<li><a href="register">Register</a></li>--}}
                <li class="{{ (Request::route()->getName() == 'home') ? 'active' : '' }}">{!! link_to_route('home', 'Home') !!}</li>
                <li class="{{ (Request::route()->getName() == 'profile') ? 'active' : '' }}">{!! link_to_route('profile', 'Profile') !!}</li>
                <li class="{{ (Request::route()->getName() == 'shows') ? 'active' : '' }}">{!! link_to_route('shows', 'TV Shows') !!}</li>
                @if (Auth::check())
                    <li>{!! link_to_route('logout', 'Log Out') !!}</li>
                @else
                    <li class="{{ (Request::route()->getName() == 'login') ? 'active' : '' }}">{!! link_to_route('login', 'Log In') !!}</li>
                    <li class="{{ (Request::route()->getName() == 'register') ? 'active' : '' }}">{!! link_to_route('register', 'Register') !!}</li>
                @endif
            </ul>
        </div>
        <!--/.nav-collapse -->
    </div>
</div>

<div class="container">
    @yield('content')
</div>
<!-- /.container -->

<footer class="footer">
    <div class="container">
        <p class="text-muted text-center">
            {!! link_to_route('about', 'About') !!}
            &nbsp;&nbsp;&nbsp;
            {!! Html::mailto('david.cacilhas@mohawkcollege.ca', 'Contact Us') !!}</p>
    </div>
</footer>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
</html>
