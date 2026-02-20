<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sitio web</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary">

    <div class="container-fluid">

        <a class="navbar-brand" href="/">Navbar</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>


        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link active" href="/">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>


                {{-- DASHBOARD SOLO SI ESTA LOGUEADO --}}
                @if(session('firebase_user'))

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        Dashboard
                    </a>
                </li>

                @endif


                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        Dropdown
                    </a>

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                    </ul>

                </li>

            </ul>


            {{-- USUARIO LOGUEADO --}}
            @if(session('firebase_user'))

            <ul class="navbar-nav">

                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle d-flex align-items-center"
                       href="#"
                       data-bs-toggle="dropdown">

                        <img
                            src="https://ui-avatars.com/api/?name={{ session('firebase_user')['name'] }}"
                            width="32"
                            height="32"
                            class="rounded-circle me-2">

                        {{ session('firebase_user')['name'] }}

                    </a>


                    <ul class="dropdown-menu dropdown-menu-end">

                        <li>
                            <a class="dropdown-item"
                               href="{{ route('profile.edit') }}">
                                Editar perfil
                            </a>
                        </li>

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

            </ul>

            @endif



            {{-- USUARIO NO LOGUEADO --}}
            @if(!session('firebase_user'))

            <a class="btn btn-primary" href="{{ route('login') }}">
                Iniciar sesión
            </a>

            @endif


        </div>

    </div>

</nav>



{{-- CONTENIDO DE CADA PAGINA --}}
@yield("content")



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



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>