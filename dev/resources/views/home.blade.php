@extends('layouts.master')

@section('content')

{{-- HERO --}}
<div class="position-relative">

    <img src="{{ asset('img/Medicines_share.jpg') }}"
         class="w-100"
         style="height:420px; object-fit:cover;">

    <div class="position-absolute top-50 start-50 translate-middle text-center text-white"
         style="text-shadow:2px 2px 6px rgba(0,0,0,0.7);">

        <h1 class="fw-bold" style="font-size:3rem;">
            Donación de medicamentos
        </h1>

    </div>

</div>

{{-- ESTILOS --}}
<style>
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

.carousel-indicators button {
    width: 30px;
    height: 4px;
    border-radius: 10px;
    background-color: #cfcfcf;
    border: none;
}

.carousel-indicators .active {
    background-color: #1a848a;
}
</style>

{{-- ================= MEDICAMENTOS ================= --}}
@php $i = 0; @endphp

@foreach($medicamentos->take(2) as $categoria => $items)

<div style="background:#f5f5f5; padding:30px 0; margin-bottom:30px;">

    <div class="container">

        <div class="position-relative mb-4">

            <h4 class="text-center fw-bold text-capitalize m-0">
                {{ $categoria }}
            </h4>

            <a href="{{ route('categoria.ver', $categoria) }}"
               class="position-absolute top-0"
               style="right:1cm; color:black; text-decoration:underline;">
                Ver más
            </a>

        </div>

        <div id="carousel{{ $i }}" class="carousel slide" data-bs-ride="false">

            <div class="carousel-inner">

                @foreach($items->chunk(3) as $chunkIndex => $chunk)

                <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">

                    <div class="d-flex justify-content-center">

                        @foreach($chunk as $med)

                        <div class="card mx-3 shadow-sm"
                             style="width:250px; border-radius:18px;">

                            <div class="text-center p-3">
                                <img src="{{ $med->image_path 
                                    ? asset('storage/'.$med->image_path) 
                                    : asset('img/Logo.png') }}"
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

{{-- ================= LUGARES PARA DONAR ================= --}}
<div class="container mt-5 mb-5">

    <h2 class="text-center mb-4">
        Lugares para donar
    </h2>

    <div class="row g-4">

        <div class="col-lg-4 col-md-6">
            <div class="card shadow-sm">

                <img src="{{ asset('img/images.jpg') }}"
                     class="d-block w-100"
                     style="height:250px; object-fit:cover;">

                <div class="card-body text-center">

                    <h5 class="card-title">Cruz Roja Mexicana</h5>

                    <p class="card-text">Durango, Dgo. México</p>

                    <a href="{{ route('cruzroja') }}"
                       class="btn text-white fw-semibold"
                       style="background:#0A9A9E; border:none;">
                        Saber más
                    </a>

                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card shadow-sm">

                <img src="{{ asset('img/hospitalNene.jpeg') }}"
                     class="d-block w-100"
                     style="height:250px; object-fit:cover;">

                <div class="card-body text-center">

                    <h5 class="card-title">Hospital Municipal Del Niño</h5>

                    <p class="card-text">Durango, Dgo. México</p>

                    <a href="{{ route('cruzroja') }}"
                       class="btn text-white fw-semibold"
                       style="background:#0A9A9E; border:none;">
                        Saber más
                    </a>

                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card shadow-sm">

                <img src="{{ asset('img/ImagePrincipal.jpeg') }}"
                     class="d-block w-100"
                     style="height:250px; object-fit:cover;">

                <div class="card-body text-center">

                    <h5 class="card-title">Caritas De Durango</h5>

                    <p class="card-text">Durango, Dgo. México</p>

                    <a href="{{ route('cruzroja') }}"
                       class="btn text-white fw-semibold"
                       style="background:#0A9A9E; border:none;">
                        Saber más
                    </a>

                </div>
            </div>
        </div>

    </div>

</div>

<script>
const usuarioLogeado = @json(session('firebase_user'));

function verificarLogin(id, lugar) {
    if (!usuarioLogeado) {
        alert("Debes iniciar sesión primero");
        window.location.href = "/login";
        return;
    }

    agregarCarrito(id, lugar);
}

function agregarCarrito(id, lugar) {
    fetch("{{ url('/cart/add') }}/" + id + "?lugar=" + encodeURIComponent(lugar))
        .then(response => response.json())
        .then(data => {

            if (data.ok) {
                alert("Solicitud enviada correctamente");
                actualizarCarrito(data.count);
            } else {
                alert(data.message);
            }

        })
        .catch(error => console.error("Error:", error));
}
</script>

@endsection