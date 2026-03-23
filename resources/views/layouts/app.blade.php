<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sitio web</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>

        .admin-badge {
            background-color: #cfcfcf;
            color: #000;
            font-size: 0.7rem;
            padding: 3px 8px;
            border-radius: 10px;
            margin-right: 6px;
            font-weight: bold;
        }

        html, body {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        /* CONTENIDO CRECE PARA EMPUJAR FOOTER */
        .main-content {
            flex: 1;
        }
        /* NAVBAR */

        .navbar-custom {
            background-color: #018d97;
        }

        .navbar-custom .nav-link,
        .navbar-custom .navbar-brand {
            color: #fff;
        }

        .navbar-custom .nav-link:hover {
            color: #e0f7fa;
        }

        .search-input {
            width: 400px;
            max-width: 100%;
        }

        .icon-btn {
            color: #fff;
            font-size: 1.3rem;
            margin-left: 1rem;
            cursor: pointer;
            position: relative;
        }

        .icon-btn:hover {
            color: #e0f7fa;
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -10px;
            background: red;
            color: white;
            border-radius: 50%;
            font-size: 12px;
            padding: 2px 6px;
        }

        /* FOOTER */

        .footer-custom {
            background: #018d97;
            color: white;
        }

        .footer-custom h5 {
            margin-bottom: 15px;
        }

        .footer-custom p {
            font-size: 14px;
            margin-bottom: 8px;
        }

        .footer-custom a {
            color: white;
            text-decoration: none;
        }

        .footer-custom a:hover {
            text-decoration: underline;
        }

        .social-icon {
            font-size: 20px;
            margin-right: 10px;
        }

    </style>

</head>

<body>

<!-- NAVBAR -->

<nav class="navbar navbar-expand-lg navbar-custom">

    <div class="container-fluid">

        <!-- LOGO -->

        <a class="navbar-brand d-flex align-items-center" href="{{ url('/home') }}">
            <img src="{{ asset('img/logo.png') }}" class="me-2" width="50">
            ALPHAMEDIC
        </a>

        <div class="collapse navbar-collapse">

            <!-- CATEGORIAS -->

            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a href="{{ route('medicamentos.index') }}" class="nav-link">
                        Categorías
                    </a>
                </li>

            </ul>

                <!-- USUARIO LOGUEADO -->

                @if(session('firebase_user'))

                    <li class="nav-item dropdown d-flex align-items-center">
                        
                        @if(session('firebase_user.role') === 'admin')
                            <span class="admin-badge">
                                ADMINISTRADOR
                            </span>
                        @endif

                        <a
                            class="nav-link dropdown-toggle d-flex align-items-center"
                            href="#"
                            data-bs-toggle="dropdown"
                        >

                            <img
                                src="https://ui-avatars.com/api/?name={{ session('firebase_user')['name'] }}"
                                width="32"
                                class="rounded-circle me-2"
                            >

                            {{ session('firebase_user')['name'] }}

                        </a>


                        <ul class="dropdown-menu dropdown-menu-end">

                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    Editar perfil
                                </a>
                            </li>

                            @if(session('firebase_user.role') === 'admin')
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.panel') }}">
                                        Panel principal
                                    </a>
                                </li>
                            @endif

                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item">
                                        Cerrar sesión
                                    </button>
                                </form>
                            </li>

                        </ul>

                    </li>

                @endif

                <!-- USUARIO NO LOGUEADO -->

                @if(!session('firebase_user'))

                    <li class="nav-item ms-3">
                        <a class="btn btn-light" href="{{ route('login') }}">
                            Iniciar sesión
                        </a>
                    </li>

                @endif

            </ul>
        </div>
    </div>
</nav>

<!-- CONTENIDO -->

<div class="main-content">

    @yield("content")

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>