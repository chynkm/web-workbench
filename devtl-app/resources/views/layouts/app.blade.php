<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">
        <title>{{ (isset($pageTitle) ? $pageTitle.' - ': null).config('app.name') }}</title>
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Bootstrap core CSS -->
        @if (config('app.env') == 'production')
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        @else
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        @endif
        <link href="/vendor/open-iconic/css/open-iconic-bootstrap.min.css" rel="stylesheet">
        <link href="{{ mix('css/all.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="navbar fixed-top navbar-expand-md p-3 px-md-4 mb-3 bg-white border-bottom">
            <a class="navbar-brand" href="{{ Auth::check() ? route('home') : route('login') }}">{{ config('app.name') }}</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="oi oi-menu"></span>
            </button>
            <nav class="collapse navbar-collapse my-2 my-md-0 mr-md-3" id="navbarCollapse">
                <ul class="navbar-nav mr-auto">
                </ul>
                <ul class="navbar-nav">
                    @if(Auth::check())
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Features <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Enterprise</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Support</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript: APP.common.signOut()" id="sign_out_btn">@lang('form.sign_out')</a>
                    </li>
                    <form id="sign_out_form" action="{{ route('logout') }}" method="POST">
                        @csrf
                    </form>
                    @else
                    <li class="nav-item mr-4">
                        <a class="nav-link" href="{{ route('login') }}">Sign In</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-primary" href="{{ route('register') }}">Sign up</a>
                    </li>
                    @endif
                </ul>
            </nav>
        </div>

        <div class="main_toast_div" aria-live="polite" aria-atomic="true">
            <div id="toast_div">
                @include('layouts.toast')
            </div>
        </div>

        <div class="{{ request()->route()->getName() == 'schemas.show' ? null : 'container' }}">
            @yield('content')
        </div>

        <footer class="footer">
            <div class="container">
                <span class="text-muted">&copy; {{ config('app.name') }}</span>
            </div>
        </footer>

        <div id="overlay" class="d-none">
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">@lang('form.loading')</span>
                </div>
            </div>
        </div>
        <!-- Bootstrap core JavaScript -->
        @if (config('app.env') == 'production')
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
        @else
        <script src="/js/jquery-3.4.1.min.js"></script>
        <script src="/js/popper.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        <script src="/js/jquery-ui.min.js"></script>
        <script src="/js/leader-line.min.js"></script>
        @endif
        <script src="{{ mix('/js/app.js') }}"></script>
    </body>
</html>
