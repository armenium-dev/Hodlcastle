<!DOCTYPE html>
<html>
@include('layouts.common.head')

<body class="skin-blue sidebar-mini">
    <div class="js_ajax_message loader">Sending request...</div>
@if (!Auth::guest())
    <div class="wrapper trans_all_slow">
        <!-- Main Header -->
        <header class="main-header">

            <!-- Logo -->
            <a href="#" class="logo">
                <b>PhishManager</b>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="javascript:void(0);" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- User Account Menu -->
                        <li class="dropdown user user-menu">
                            <!-- Menu Toggle Button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <!-- The user image in the navbar-->
                                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                <span class="hidden-xs">{!! Auth::user()->name !!}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user">
                                    <a href="{{ route('profile.index') }}">
                                        <img src="{{ Auth::user()->company && Auth::user()->company->logo ? Auth::user()->company->logo->crop(100, 100, true) : '/public/img/logo.png' }}" class="img-circle" alt="User Avatar"/>
                                    </a>
                                    <div class="info">
                                        <a href="{{ route('profile.index') }}">{!! Auth::user()->name !!}</a>
                                        <small>Member since {!! Auth::user()->created_at ? Auth::user()->created_at->format('M. Y') : '-' !!}</small>
                                    </div>
                                </li>
                                <li><a href="{{ route('profile.index') }}" class="link"><i class="fa fa-fw fa-user"></i> Profile</a></li>
                                @if(Auth::user()->hasRole('captain'))
                                <li><a href="{{ route('settings.index') }}" class="link"><i class="fa fa-fw fa-sliders"></i> Settings</a></li>
                                @endif
                                <li>
                                    <a href="{!! url('/logout') !!}" class="link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-fw fa-sign-out"></i> Logout</a>
                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <section class="content-layout">
            <!-- Left side column. contains the logo and sidebar -->
            @include('layouts.sidebar')
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                {{--@include('errors')--}}
                @yield('content')
            </div>
        </section>
        <footer class="main-footer">
            <strong>Copyright © <?php echo date('Y'); ?> <a href="#">phishmanager</a>.</strong> All rights reserved.
        </footer>

    </div>
@else
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{!! url('/') !!}">PhishManager</a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <!--
                <ul class="nav navbar-nav">
                    <li><a href="{!! url('/home') !!}">Home</a></li>
                </ul>
                -->

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    <li><a href="{!! url('/login') !!}">Login</a></li>
                    <!--<li><a href="{!! url('/register') !!}">Register</a></li>-->
                </ul>
            </div>
        </div>
    </nav>

    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    @include('adminlte-templates::common.errors')
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
@endif

@include('layouts.common.foot')

</body>
</html>
