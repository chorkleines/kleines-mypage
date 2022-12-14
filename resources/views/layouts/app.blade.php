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
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
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

    <div class="container-fluid vh-100">
        <div class="row h-100">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-white sidebar collapse">
                <div class="position-sticky h-100 d-flex flex-column">
                    <ul class="nav flex-column mt-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->route()->named('home')? 'active': '' }}"
                                aria-current="page" href="{{ route('home') }}">
                                <span class="me-2"><i class="fa-solid fa-house me-2"></i>{{ __('home') }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->route()->named('accounts.*')? 'active': '' }}"
                                aria-current="page" href="{{ route('accounts.list') }}">
                                <span class="me-2"><i
                                        class="fa-solid fa-users me-2"></i>{{ __('accounts_list') }}</span>
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
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                             document.getElementById('logout-form').submit();">{{ __('logout') }}</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="col-md-9 ms-sm-auto col-lg-10 px-0 py-4">
                <main>
                    @yield('content')
                </main>
            </div>
        </div>
    </div>
</body>

</html>
