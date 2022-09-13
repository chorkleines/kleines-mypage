<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar"
                aria-controls="sidebar" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-white sidebar collapse">
                <div class="position-sticky h-100 d-flex flex-column">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">
                                <span class="me-2"><i class="fa-solid fa-house me-2"></i>Home</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="me-2"><i class="fa-solid fa-clipboard me-2"></i>Bulletin Board</span>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav flex-column mt-auto mb-3">
                        <li class="nav-item dropup">
                            <a class="nav-link dropdown-toggle" href="#" id="sidebarDropdownMenuLink"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="me-2"><i
                                        class="fa-solid fa-user me-2"></i>{{ Auth::user()->profile->display_name() }}</span>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="sidebarDropdownMenuLink">
                                <li>
                                    <a class="dropdown-item" href="#">Setting</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">Another action</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                             document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <main>
                    @yield('content')
                </main>
            </div>
        </div>
    </div>
</body>

</html>
