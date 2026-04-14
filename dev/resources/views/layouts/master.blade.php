<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sitio web</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

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

        .main-content {
            flex: 1;
        }

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

        .btn-custom {
            background-color: #018d97;
            color: white;
        }

        .btn-custom:hover {
            background-color: #016f76;
            color: white;
        }

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

        /* 🔥 BOTONES DE FILTRO (PENDIENTES / APROBADOS / RECHAZADOS) */
        .filtro-btn {
            transition: all 0.3s ease;
            border-radius: 20px;
            padding: 6px 15px;
            font-weight: 500;
        }

        /* Hover bonito */
        .filtro-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        /* BOTÓN ACTIVO */
        .filtro-activo {
            background-color: #018d97 !important;
            color: #fff !important;
            box-shadow: 0 0 10px rgba(1, 141, 151, 0.6);
        }

        /* ANIMACIÓN SUAVE */
        .fade-tab {
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

    </style>

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">

        <a class="navbar-brand d-flex align-items-center" href="{{ url('/home') }}">
            <img src="{{ asset('img/logo.png') }}" class="me-2" width="50">
            ALPHAMEDIC
        </a>

        <div class="collapse navbar-collapse">

            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a href="{{ route('medicamentos.index') }}" class="nav-link">
                        Categorías
                    </a>
                </li>
            </ul>

            <form class="d-flex mx-auto" onsubmit="irABusqueda(event)">
                <input id="busquedaInput" class="form-control search-input" placeholder="Buscar Medicina" required>
                <button class="btn btn-light ms-2">Buscar</button>
            </form>

            <ul class="navbar-nav ms-auto d-flex align-items-center">

                <!-- 🔔 NOTIFICACIONES -->
                <li class="nav-item dropdown">

                    <a class="icon-btn" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>

                        <span id="notif-count" class="cart-badge" style="display:none;">
                            0
                        </span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end" style="width:300px">
                        <li class="dropdown-header">Notificaciones</li>

                        <div id="notif-list">
                            <li>
                                <span class="dropdown-item text-muted">
                                    Cargando...
                                </span>
                            </li>
                        </div>
                    </ul>

                </li>

                <!-- 🛒 CARRITO -->
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

                        <span id="cart-count"
                            class="cart-badge"
                            style="{{ count($cart) > 0 ? '' : 'display:none;' }}">
                            {{ count($cart) }}
                        </span>
                    </a>
                </li>

                @if(session('firebase_user'))
                    <li class="nav-item dropdown d-flex align-items-center ms-3">

                        @if(session('firebase_user.role') === 'admin')
                            <span class="admin-badge">ADMINISTRADOR</span>
                        @endif

                        <a class="nav-link dropdown-toggle d-flex align-items-center"
                           href="#"
                           data-bs-toggle="dropdown">

                            <img
                                src="https://ui-avatars.com/api/?name={{ session('firebase_user')['name'] }}"
                                width="32"
                                class="rounded-circle me-2"
                            >

                            {{ session('firebase_user')['name'] }}
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">

                            <li>
                                <a class="dropdown-item" href="{{ route('pedidos.index') }}">
                                    Peticiones
                                </a>
                            </li>

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

<script>
const firebaseUser = @json(session('firebase_user'));

function actualizarNotificaciones(count) {
    let badge = document.getElementById("notif-count");
    if (!badge) return;

    if (count > 0) {
        badge.style.display = "inline-block";
        badge.innerText = count;
    } else {
        badge.style.display = "none";
    }
}

async function cargarNotificaciones() {

    if (!firebaseUser || !firebaseUser.email) return;

    let correo = firebaseUser.email;

    let url = "https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents/notificaciones";

    let res = await fetch(url);
    let data = await res.json();

    let docs = data.documents || [];

    let lista = document.getElementById("notif-list");
    lista.innerHTML = "";

    let contador = 0;
    let notifs = [];

    docs.forEach(doc => {

        let f = doc.fields;

        if ((f.correo?.stringValue || "") === correo) {

            notifs.push({
                id: doc.name.split("/").pop(),
                mensaje: f.mensaje?.stringValue,
                leido: f.leido?.booleanValue,
                fecha: f.fecha?.timestampValue
            });

            if (!f.leido?.booleanValue) contador++;
        }
    });

    notifs.sort((a, b) => new Date(b.fecha) - new Date(a.fecha));
    notifs = notifs.slice(0, 5);

    notifs.forEach(n => {
        lista.innerHTML += `
            <li onclick="marcarLeido('${n.id}')" style="cursor:pointer;">
                <div class="dropdown-item small ${n.leido ? '' : 'bg-light'}">
                    <div><strong>${n.mensaje}</strong></div>
                    <div class="text-muted" style="font-size:11px;">
                        ${new Date(n.fecha).toLocaleString()}
                    </div>
                </div>
            </li>
        `;
    });

    if (notifs.length === 0) {
        lista.innerHTML = `<li><span class="dropdown-item text-muted">No tienes notificaciones</span></li>`;
    }

    actualizarNotificaciones(contador);
}

async function marcarLeido(id) {
    let url = `https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents/notificaciones/${id}?updateMask.fieldPaths=leido`;

    await fetch(url, {
        method: "PATCH",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            fields: { leido: { booleanValue: true } }
        })
    });

    cargarNotificaciones();
}

setInterval(cargarNotificaciones, 5000);
cargarNotificaciones();
</script>

<script>
function irABusqueda(e) {
    e.preventDefault();

    let input = document.getElementById("busquedaInput");

    if (!input || input.value.trim() === "") {
        alert("Escribe algo para buscar");
        return;
    }

    let query = input.value.trim();

    window.location.href = "{{ route('categoria.ver', '') }}/" + encodeURIComponent(query);
}
</script>

</body>
</html>