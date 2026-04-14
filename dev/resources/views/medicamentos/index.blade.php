@extends('layouts.master')

@section('content')

<style>

/* Flechas */
.carousel-control-prev,
.carousel-control-next {
    width: 5%;
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-color: rgba(0,0,0,0.7);
    border-radius: 20%;
    padding: 18px;
}

/* Indicadores */
.carousel-indicators button {
    width: 30px;
    height: 4px;
    border-radius: 10px;
    background-color: #cfcfcf;
    border: none;
}

.carousel-indicators .active {
    background-color: #1a848a
}

</style>

<div class="mt-4">

@php 
$i = 0; 
$usuarioLogeado = session('firebase_user');
@endphp

@foreach($medicamentos as $categoria => $items)

<div style="background:#f5f5f5; padding:30px 0; margin-bottom:30px;">

    <div class="container">

        <h4 class="text-center mb-4 fw-bold text-capitalize">
            {{ $categoria }}
        </h4>

        <div id="carousel{{ $i }}" class="carousel slide" data-bs-ride="false">

            <div class="carousel-inner">

                @foreach($items->chunk(3) as $chunkIndex => $chunk)

                <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">

                    <div class="d-flex justify-content-center">

                        @foreach($chunk as $med)

                        <div class="card mx-3 shadow-sm"
                             style="width:250px; border-radius:18px;">

                            <div class="text-center p-3">
                                <img src="{{ $med->image_path ? asset('storage/'.$med->image_path) : asset('img/Logo.png') }}"
                                     height="120">
                            </div>

                            <div class="card-body pt-0">

                                <h6 class="fw-bold mb-1">
                                    {{ $med->nombre }}
                                </h6>

                                <small class="text-muted">
                                    {{ $med->mg }} mg | {{ $med->presentacion }}
                                </small>

                                <p class="mt-2 mb-1" style="font-size:13px">
                                    Lugar: <strong>{{ $med->lugar }}</strong>
                                </p>

                                <p class="mb-1" style="font-size:13px">
                                    Disponibles: {{ $med->disponibilidad }} cajas
                                </p>

                                <button onclick="verificarLogin('{{ $med->id }}', '{{ $med->lugar }}')"
                                        class="btn w-100 mt-2"
                                        style="background:#1a848a; color:white; border-radius:10px;">
                                    Solicitar
                                </button>

                            </div>
                        </div>

                        @endforeach

                    </div>

                </div>

                @endforeach

            </div>

            <button class="carousel-control-prev"
                    type="button"
                    data-bs-target="#carousel{{ $i }}"
                    data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <button class="carousel-control-next"
                    type="button"
                    data-bs-target="#carousel{{ $i }}"
                    data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>

            <div class="carousel-indicators position-static mt-4">

                @foreach($items->chunk(3) as $chunkIndex => $chunk)

                <button type="button"
                        data-bs-target="#carousel{{ $i }}"
                        data-bs-slide-to="{{ $chunkIndex }}"
                        class="{{ $chunkIndex == 0 ? 'active' : '' }}">
                </button>

                @endforeach

            </div>

        </div>

    </div>

</div>

@php $i++; @endphp

@endforeach

</div>

<script>
const usuarioLogeado = @json($usuarioLogeado);

// 🔥 NUEVA FUNCIÓN
function verificarLogin(id, lugar) {
    if (!usuarioLogeado) {
        alert("Debes iniciar sesión primero");
        window.location.href = "/login";
        return;
    }

    agregarCarrito(id, lugar);
}

// 🔹 TU FUNCIÓN ORIGINAL (NO SE TOCA)
function agregarCarrito(id, lugar) {
    fetch("{{ url('/cart/add') }}/" + id + "?lugar=" + encodeURIComponent(lugar))
        .then(response => response.json())
        .then(data => {
            console.log(data); // 👈 AGREGA ESTO

            if (data.ok) {
                alert("Solicitud enviada correctamente");
                actualizarCarrito(data.count);
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error("Error:", error));
}

function actualizarCarrito(count) {
    let badge = document.getElementById("cart-count");

    if (!badge) return;

    if (count > 0) {
        badge.style.display = "inline-block";
        badge.innerText = count;
    } else {
        badge.style.display = "none";
        badge.innerText = 0;
    }
}
</script>

@endsection