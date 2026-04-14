@extends('layouts.master')

@section('content')

<style>
.filtros-box{
    background:#f5f5f5;
    padding:20px;
    border-radius:15px;
}

.card{
    border-radius:18px;
}
</style>

<div class="container mt-4">

    <h2 class="text-center fw-bold text-capitalize mb-4">
        {{ $categoria }}
    </h2>

    <div class="filtros-box mb-4">

        <form method="GET">

            <div class="row g-3 justify-content-center">

                <div class="col-md-3">
                    <input class="form-control text-center fw-semibold"
                           value="{{ $categoria }}"
                           disabled>
                </div>

                <div class="col-md-3">
                    <select name="mg" class="form-select">
                        <option value="">MG</option>

                        @foreach($mgs as $mg)
                            <option value="{{ $mg }}"
                                {{ request('mg') == $mg ? 'selected' : '' }}>
                                {{ $mg }} mg
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="presentacion" class="form-select">
                        <option value="">Presentación</option>

                        @foreach($presentaciones as $p)
                            <option value="{{ $p }}"
                                {{ request('presentacion') == $p ? 'selected' : '' }}>
                                {{ $p }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn w-100 text-white fw-semibold"
                            style="background:#1a848a;">
                        Filtrar
                    </button>
                </div>

            </div>

        </form>

    </div>

    <div class="row justify-content-center">

        @forelse($medicamentos as $med)

        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">

            <div class="card shadow-sm position-relative h-100">

                <div class="text-center p-3">
                    <img src="{{ $med->image_path
                        ? asset('storage/'.$med->image_path)
                        : asset('img/Logo.png') }}"
                        height="120">
                </div>

                <div class="card-body pt-0 text-center">

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

                    <button onclick="agregarCarrito('{{ $med->id }}', '{{ $med->lugar }}')"
                            class="btn w-100 mt-2 text-white"
                            style="background:#1a848a; border-radius:10px;">
                        Solicitar
                    </button>

                </div>

            </div>

        </div>

        @empty

        <div class="text-center mt-5">
            <h5>No se encontraron medicamentos</h5>
        </div>

        @endforelse

    </div>

</div>

<script>
function agregarCarrito(id, lugar) {

    fetch("{{ url('/cart/add') }}/" + id + "?lugar=" + encodeURIComponent(lugar))
        .then(response => response.json())
        .then(data => {
            if (data.ok) {
                alert("Agregado al carrito");
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error("Error:", error));
}
</script>

@endsection