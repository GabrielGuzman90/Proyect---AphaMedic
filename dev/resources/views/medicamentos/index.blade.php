@extends('layouts.master')

@section('content')

<style>
.categoria-section {
    background: #f5f5f5;
    border-radius: 20px;
    padding: 20px 10px;
    margin-bottom: 30px;
}
</style>

<div class="container mt-4">

@php $i = 0; @endphp

@foreach($medicamentos as $categoria => $items)

<div class="categoria-section">

    <h4 class="text-center mb-4 fw-bold text-capitalize">
        {{ $categoria }}
    </h4>

    <div id="carousel{{ $i }}" class="carousel slide" data-bs-ride="false">

        <div class="carousel-inner">

            @foreach($items->chunk(3) as $chunkIndex => $chunk)

            <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">

                <div class="d-flex justify-content-center">

                    @foreach($chunk as $med)

                    <div class="card mx-3 shadow-sm position-relative"
                         style="width:250px; border-radius:18px;">

                        <!-- BOTÓN GUARDAR -->
                        <button onclick="guardar({{ $med->id }}, this)"
                                class="btn btn-light position-absolute"
                                style="right:10px; top:10px; border-radius:50%; width:40px; height:40px;">

                            <i class="bi {{ $med->guardado ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>

                        </button>

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
                                Disponibles: {{ $med->disponibilidad }} cajas
                            </p>

                            <!-- BOTÓN SOLICITAR -->
                            <button onclick="agregarCarrito({{ $med->id }})"
                                    class="btn w-100 mt-2"
                                    style="background:#009688; color:white; border-radius:10px;">
                                Solicitar
                            </button>

                        </div>
                    </div>

                    @endforeach

                </div>
            </div>

            @endforeach

        </div>

        <!-- INDICADORES -->
        <div class="carousel-indicators position-static mt-3">

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

@php $i++; @endphp

@endforeach

</div>

<script>
function agregarCarrito(id) {

    fetch("{{ url('/cart/add') }}/" + id)
        .then(response => response.json())
        .then(data => {

            if (data.ok) {

                // Solo mostrar mensaje
                alert("Solicitud enviada correctamente");

                // Quitamos esta línea para evitar redirección
                // window.location.href = "{{ route('cart.index') }}";

            } else {
                alert(data.message);
            }

        })
        .catch(error => console.error("Error:", error));
}
</script>

@endsection