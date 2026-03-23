@extends('layouts.master')

@section('content')

<div class="container py-5">

    <h6 class="text-secondary fw-semibold mb-1">Administración</h6>
    <h2 class="fw-bold mb-4">Mensajes de contacto</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">

        <!-- BARRA VERDE -->
        <div class="card-header text-white fw-semibold"
             style="background-color:#008C8C;">
            Lista de mensajes recibidos
        </div>

        <div class="table-responsive">
            <table class="table mb-0">

                <!-- ENCABEZADO -->
                <thead style="background-color:#008C8C; color:white;">
                    <tr>
                        <th class="px-4 py-3 border-end">Nombre</th>
                        <th class="py-3 border-end">Correo</th>
                        <th class="py-3 border-end">Prioridad</th>
                        <th class="py-3 border-end">Estado</th>
                        <th class="py-3 text-center">Opciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($mensajes as $mensaje)
                    <tr style="border-bottom:1px solid #dee2e6;">

                        <td class="px-4 border-end">{{ $mensaje->nombre }}</td>
                        <td class="border-end">{{ $mensaje->correo }}</td>
                        <td class="border-end">{{ $mensaje->prioridad }}</td>

                        <!-- BADGE ESTADO -->
                        <td class="border-end">
                            @if($mensaje->status == 'realizado')
                                <span class="badge bg-success">Realizado</span>
                            @else
                                <span class="badge bg-warning text-dark">Pendiente</span>
                            @endif
                        </td>

                        <!-- BOTÓN VER -->
                        <td class="text-center">
                            <button 
                                type="button"
                                class="btn btn-sm text-white"
                                style="background-color:#008C8C;"
                                data-bs-toggle="modal"
                                data-bs-target="#viewModal{{ $mensaje->id }}">
                                Ver
                            </button>
                        </td>

                    </tr>

                    <!-- MODAL -->
                    <div class="modal fade" id="viewModal{{ $mensaje->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">

                                <!-- HEADER -->
                                <div class="modal-header text-white"
                                     style="background-color:#008C8C;">
                                    <h5 class="modal-title">Detalle del mensaje</h5>
                                    <button type="button"
                                            class="btn-close btn-close-white"
                                            data-bs-dismiss="modal">
                                    </button>
                                </div>

                                <!-- BODY -->
                                <div class="modal-body">

                                    <div class="mb-3">
                                        <label class="fw-semibold">Nombre</label>
                                        <input type="text"
                                               class="form-control"
                                               value="{{ $mensaje->nombre }}"
                                               disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label class="fw-semibold">Correo</label>
                                        <input type="text"
                                               class="form-control"
                                               value="{{ $mensaje->correo }}"
                                               disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label class="fw-semibold">Prioridad</label>
                                        <input type="text"
                                               class="form-control"
                                               value="{{ $mensaje->prioridad }}"
                                               disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label class="fw-semibold">Asunto</label>
                                        <input type="text"
                                               class="form-control"
                                               value="{{ $mensaje->asunto }}"
                                               disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label class="fw-semibold">Mensaje</label>
                                        <textarea class="form-control"
                                                  rows="4"
                                                  disabled>{{ $mensaje->mensaje }}</textarea>
                                    </div>

                                    <hr>

                                    <!-- FORM CAMBIAR ESTADO -->
                                    <form method="POST"
                                          action="{{ route('contacto.status', [$mensaje->id, $mensaje->status]) }}"
                                          id="formEstado{{ $mensaje->id }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="mb-3">
                                            <label class="fw-semibold">Estado</label>

                                            <select name="estado"
                                                    class="form-control"
                                                    onchange="document.getElementById('formEstado{{ $mensaje->id }}').action = 
                                                    '{{ url('/contacto/'.$mensaje->id.'/status') }}/' + this.value;">

                                                <option disabled>Selecciona estado</option>

                                                <option value="pendiente"
                                                    {{ $mensaje->status == 'pendiente' ? 'selected' : '' }}>
                                                    Pendiente
                                                </option>

                                                <option value="realizado"
                                                    {{ $mensaje->status == 'realizado' ? 'selected' : '' }}>
                                                    Realizado
                                                </option>

                                            </select>
                                        </div>

                                        <div class="d-flex justify-content-end gap-2">

                                            <button type="button"
                                                    class="btn btn-secondary"
                                                    data-bs-dismiss="modal">
                                                Cancelar
                                            </button>

                                            <button type="submit"
                                                    class="btn text-white"
                                                    style="background-color:#008C8C;">
                                                Guardar cambios
                                            </button>

                                        </div>

                                    </form>

                                </div>

                                <!-- FOOTER ELIMINAR -->
                                <div class="modal-footer border-0">

                                    <form method="POST"
                                          action="{{ route('contacto.delete', $mensaje->id) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger">
                                            Eliminar mensaje
                                        </button>
                                    </form>

                                </div>

                            </div>
                        </div>
                    </div>

                    @endforeach
                </tbody>

            </table>
        </div>

    </div>

</div>

@endsection