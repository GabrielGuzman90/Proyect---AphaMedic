@extends('layouts.master')

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="container mt-5">

    <div class="row">

        <!-- FORMULARIO -->
        <div class="col-md-6">

            <h4 class="mb-4">Datos del Solicitante</h4>

            @if(count($cart) > 0)

            <form id="formPedido" action="{{ route('peticion.store') }}" method="POST">
                @csrf

                <input class="form-control mb-3"
                       name="nombre"
                       placeholder="Nombre completo"
                       required>

                <input class="form-control mb-3"
                       name="telefono"
                       placeholder="Número telefónico"
                       required>

                <input class="form-control mb-3"
                       name="correo"
                       type="email"
                       placeholder="Correo electrónico"
                       required>

                @foreach($cart as $item)
                    <input type="hidden"
                        name="medicamentos[]"
                        value="{{ $item['nombre'] }} - {{ $item['cantidad'] }} caja(s)">
                    <input type="hidden"
                        name="instituciones[]"
                        value="{{ $item['lugar'] ?? 'No definido' }}">
                @endforeach

                <!-- BOTON MODIFICADO -->
                <button type="button"
                        class="btn btn-success w-100"
                        onclick="mostrarModal()">
                    Enviar Pedido
                </button>

            </form>

            @else

                <div class="alert alert-info">
                    Agrega medicamentos al carrito para poder enviar el pedido.
                </div>

            @endif

        </div>


        <!-- CARRITO -->
        <div class="col-md-6">

            <h4 class="mb-4">Tu pedido</h4>

            @forelse($cart as $item)

                <div class="card mb-3 shadow-sm p-3">

                    <div class="d-flex align-items-center">

                        <img src="{{ $item['image'] ? asset('storage/'.$item['image']) : asset('img/Logo.png') }}"
                             width="80"
                             class="me-3">

                        <div class="flex-grow-1">

                            <h6 class="mb-1">
                                {{ $item['nombre'] }}
                            </h6>

                            <small class="text-muted">
                                {{ $item['mg'] }} mg |
                                {{ $item['presentacion'] }}
                            </small>

                            <p class="mb-1" style="font-size:13px">
                                Instituto: <strong>{{ $item['lugar'] ?? 'No definido' }}</strong>
                            </p>

                            <p class="mb-2">
                                Cantidad actual: {{ $item['cantidad'] }} caja(s)
                            </p>

                            <!-- ACTUALIZAR -->
                            <form action="{{ route('cart.update', $item['id']) }}"
                                  method="POST"
                                  class="d-flex align-items-center gap-2">
                                @csrf

                                <input type="number"
                                       name="cantidad"
                                       value="{{ $item['cantidad'] }}"
                                       min="1"
                                       max="{{ $item['max'] }}"
                                       class="form-control form-control-sm"
                                       style="width:80px;">

                                <button class="btn btn-sm btn-success">
                                    Actualizar
                                </button>
                            </form>

                            <small class="text-muted">
                                Máximo permitido: {{ $item['max'] }}
                            </small>

                        </div>

                        <!-- ELIMINAR -->
                        <a href="{{ route('cart.remove', $item['id']) }}"
                           class="btn btn-sm btn-danger ms-2">
                            Eliminar
                        </a>

                    </div>

                </div>

            @empty

                <div class="alert alert-info">
                    Tu carrito está vacío
                </div>

            @endforelse

        </div>

    </div>

</div>

<!-- MODAL DE CONFIRMACION -->
<div class="modal fade" id="modalConfirmacion" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Pedido enviado correctamente</h5>
      </div>

      <div class="modal-body">

        <p>
            Tu solicitud ha sido enviada con éxito
        </p>

        <p>
            Para recoger tu medicamento, sigue estas indicaciones:
        </p>

        <ul>
            <li>
                Si tus medicamentos requieren receta, recuerda llevarla.
            </li>
            <li>
                Acude al instituto correspondiente:
                <strong id="institutoModal"></strong>
            </li>
            <li>
                Ahí se evaluará si es posible entregarte el medicamento.
            </li>
        </ul>

      </div>

      <div class="modal-footer">
        <button class="btn btn-primary" onclick="aceptarYEnviar()">
            Acepto / Enterado
        </button>
      </div>

    </div>
  </div>
</div>

<!-- SCRIPT -->
<script>
function mostrarModal() {

    let instituto = document.querySelector('input[name="instituciones[]"]');

    if (instituto) {
        document.getElementById('institutoModal').innerText = instituto.value;
    }

    let modal = new bootstrap.Modal(document.getElementById('modalConfirmacion'));
    modal.show();
}

function aceptarYEnviar() {

    let btn = document.querySelector('.btn-success');
    btn.disabled = true;
    btn.innerText = "Enviando...";

    document.getElementById('formPedido').submit();
}
</script>

@endsection