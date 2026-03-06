<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sitio web</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Estilos personalizados -->
    <style>
        .navbar-custom {
            background-color: #018d97;
        }

        .navbar-custom .nav-link,
        .navbar-custom .navbar-brand {
            color: #fff;
        }

        .navbar-custom .nav-link:hover,
        .navbar-custom .navbar-brand:hover {
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

        .avatar-dropdown img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            margin-right: 0.5rem;
        }

        .admin-badge {
            background-color: #cfcfcf;
            color: #000;
            font-size: 0.7rem;
            padding: 3px 8px;
            border-radius: 10px;
            margin-right: 6px;
            font-weight: bold;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">

        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="me-2" width="50">
            ALPHAMEDIC
        </a>

        <div class="collapse navbar-collapse" id="navbarTop">

            <!-- Categorías -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a href="{{ route('medicamentos.index') }}"
                       style="text-decoration: none; color: white;"
                       onmouseover="this.style.opacity='0.7'"
                       onmouseout="this.style.opacity='1'">
                        Categorías
                    </a>
                </li>
            </ul>

            <!-- Buscador -->
            <form class="d-flex mx-auto">
                <input class="form-control search-input"
                       type="search"
                       placeholder="Buscar Medicina">
                <button class="btn btn-light ms-2">
                    Buscar
                </button>
            </form>

            <!-- Iconos -->
            <ul class="navbar-nav ms-auto d-flex align-items-center">

                <!-- Carrito -->
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

                <!-- Favoritos -->
                <li class="nav-item">
                    <a class="icon-btn" href="#">
                        <i class="bi bi-heart"></i>
                    </a>
                </li>

                <!-- Usuario logueado -->
                @if(session('firebase_user'))
                    <li class="nav-item dropdown avatar-dropdown d-flex align-items-center">

                        @if(session('firebase_user.role') === 'admin')
                            <span class="admin-badge">
                                ADMINISTRADOR
                            </span>
                        @endif

                        <a class="nav-link dropdown-toggle d-flex align-items-center"
                           href="#"
                           data-bs-toggle="dropdown">

                            <img src="https://ui-avatars.com/api/?name={{ session('firebase_user')['name'] }}"
                                 alt="Avatar">

                            {{ session('firebase_user')['name'] }}
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">

                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    Editar perfil
                                </a>
                            </li>

                            @php
                                $firebaseUser = session('firebase_user');
                            @endphp

                            @if($firebaseUser && isset($firebaseUser['role']) && $firebaseUser['role'] === 'admin')
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

                <!-- Usuario no logueado -->
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
@yield("content")

<!-- FOOTER -->
<footer class="bg-primary text-white mt-5">
    <div class="container">
        <div class="row">

            <div class="col-lg-3 p-4">
                <img src="https://placehold.jp/100x100.png">
            </div>

            <div class="col-lg-3 p-4">
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                </p>
            </div>

            <div class="col-lg-3 p-4">
                <ol>
                    <li>Inicio</li>
                    <li>Acerca</li>
                    <li>Servicios</li>
                    <li>Contacto</li>
                </ol>
            </div>

            <div class="col-lg-3 p-4">
                <ul>
                    <li>Facebook</li>
                    <li>Instagram</li>
                    <li>Whatsapp</li>
                    <li>LinkedIn</li>
                </ul>
            </div>

        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>