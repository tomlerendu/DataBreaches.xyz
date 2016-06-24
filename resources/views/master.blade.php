<html>
    <head>

        @hasSection('title')
            <title>@yield('title') - DataBreaches.xyz</title>
        @else
            <title>DataBreaches.xyz</title>
        @endunless

        <base href="/">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="{{ url('resources/app.css') }}">
        <script src="{{ url('resources/app.js') }}"></script>

    </head>
    <body>

        <div class="site">
            <nav class="navbar navbar-default navbar-static-top site-nav">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a href="/" class="navbar-brand {{ ifActiveRoute('') }}">{{ config('app.name') }}</a>
                    </div>
                    <ul class="nav navbar-nav">
                        <li class="{{ ifActiveRoute('browse*') }}"><a href="{{ action('BrowseController@browse') }}">Browse</a></li>
                        <li class="{{ ifActiveRoute('recent') }}"><a href="{{ action('BrowseController@recent') }}">Recent</a></li>
                    </ul>

                    <form class="navbar-form navbar-right" action="{{ action('HomeController@search') }}" role="search">
                        <div class="form-group">
                            <input type="text" name="q" class="form-control" placeholder="Search" value="{{ $searchTerm or '' }}">
                        </div>
                    </form>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                {{ Auth::check() ? Auth::user()->trimmed_username : 'Account' }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                @if (Auth::check())
                                    <li><a href="{{ action('AccountController@view') }}">Account</a></li>
                                    <li><a href="{{ action('User\DashboardController@dashboard') }}">User Dashboard</a></li>
                                    @if (Auth::user()->isRank(UserRankEnum::Editor))
                                        <li><a href="{{ action('Editor\DashboardController@dashboard') }}">Editor Dashboard</a></li>
                                    @endif
                                    <li><a href="/logout">Logout</a></li>
                                @else
                                    <li><a href="/login">Login</a></li>
                                    <li><a href="/register">Register</a></li>
                                @endif
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            @yield('header')

            <main>
                @yield('content')
            </main>
        </div>

    </body>
</html>