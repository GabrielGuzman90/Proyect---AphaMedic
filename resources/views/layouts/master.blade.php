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

            <!-- BUSCADOR -->

            <form class="d-flex mx-auto">

                <input
                    class="form-control search-input"
                    placeholder="Buscar Medicina"
                >

                <button class="btn btn-light ms-2">
                    Buscar
                </button>

            </form>
            <!-- ICONOS DERECHA -->

            <ul class="navbar-nav ms-auto d-flex align-items-center">

                <!-- CARRITO -->

                <li class="nav-item">

                    <a class="icon-btn" href="{{ route('cart.index') }}">

                        <i class="bi bi-cart"></i>

                        @php
                            $firebaseUser = session('firebase_user');
                            $cart = [];

                            if ($firebaseUser && isset($firebaseUser['uid'])) {
                                $cartKey = 'cart_' . $firebaseUser['uid'];
                                $cart = session($cartKey, []);
                            }
                        @endphp

                        @if(count($cart) > 0)
                            <span class="cart-badge">
                                {{ count($cart) }}
                            </span>
                        @endif

                    </a>

                </li>

                <!-- FAVORITOS -->

                <li class="nav-item">
                    <a class="icon-btn">
                        <i class="bi bi-heart"></i>
                    </a>
                </li>

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

<!-- FOOTER -->

<footer class="footer-custom pt-5 pb-4">
    <div class="container">
        <div class="row">

            <!-- CONTACTOS -->
            <div class="col-md-3">

                <h5>Contactos</h5>

                <p>
                    Av. Cuauhtemoc 225, Zona Centro <br>
                    34000 Durango, Dgo., México
                </p>

                <p>
                    Blvd. José María Patoni s/n <br>
                    La Forestal <br>
                    34217 Durango, Dgo., México
                </p>

                <p>
                    +52 618 496 4178
                </p>

            </div>

            <!-- POLITICAS -->

            <div class="col-md-3">

                <h5>Políticas</h5>

                <p>
                    <a href="terminos-condicones">• Terminos y condicones</a>
                </p>

                <p>
                    <a href="avisosprivacidad">• Avisos de privacida</a>
                </p>

            </div>

            <!-- ACERCA DE -->
            <div class="col-md-3">

                <h5>Acerca de AlphaMedic</h5>

                <p>
                    <a href="about-us">¿Quiénes somos?</a>
                </p>

                <p>
                    <a href="contacto">Contactar</a>
                </p>

            </div>

            <!-- REDES SOCIALES -->

            <div class="col-md-3">

                <h5>Síguenos</h5>

                <p>
                    <i class="bi bi-facebook social-icon"></i>
                    <a href="https://www.facebook.com/">Facebook</a>
                </p>

                <p>
                    <i class="bi bi-instagram social-icon"></i>
                    <a href="https://www.instagram.com/">Instagram</a>
                </p>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>