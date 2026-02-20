<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel Firebase') }}</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body>

<div id="app">

    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">

        <div class="container">

            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel Firebase') }}
            </a>

            <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent">

                <span class="navbar-toggler-icon"></span>

            </button>


            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <!-- LEFT -->
                <ul class="navbar-nav me-auto">

                    @if(session('firebase_user'))
                    
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                    @endif

                </ul>


                <!-- RIGHT -->
                <ul class="navbar-nav ms-auto">

                    @if(!session('firebase_user'))

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                Login
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                Register
                            </a>
                        </li>

                    @else

                        <li class="nav-item dropdown">

                            <a class="nav-link dropdown-toggle"
                                href="#"
                                data-bs-toggle="dropdown">

                                {{ session('firebase_user.name') }}

                            </a>


                            <div class="dropdown-menu dropdown-menu-end">

                                <a class="dropdown-item"
                                    href="{{ route('profile.edit') }}">

                                    Editar perfil

                                </a>


                                <form method="POST"
                                      action="{{ route('logout') }}">

                                    @csrf

                                    <button class="dropdown-item">

                                        Logout

                                    </button>

                                </form>

                            </div>

                        </li>

                    @endif

                </ul>

            </div>

        </div>

    </nav>


    <main class="py-4">

        <div class="container">

            @yield('content')

        </div>

    </main>


</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>