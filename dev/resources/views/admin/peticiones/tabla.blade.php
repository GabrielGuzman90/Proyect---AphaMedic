<div class="card shadow-sm border-0">

    <div class="card-header text-white fw-semibold"
         style="background-color:#008C8C;">
        Lista de pedidos
    </div>

    <div class="table-responsive">
        <table class="table mb-0">

            <thead style="background-color:#008C8C; color:white;">
                <tr>
                    <th class="px-4 py-3 border-end">N° Pedido</th>
                    <th class="py-3 border-end">Cliente</th>
                    <th class="py-3 border-end">Correo</th>
                    <th class="py-3 border-end">Estado</th>
                    <th class="py-3 text-center">Opciones</th>
                </tr>
            </thead>

            <tbody>

            @forelse($peticiones as $peticion)

            <tr style="border-bottom:1px solid #dee2e6;">

                <td class="px-4 border-end">
                    {{ $peticion['numero_pedido'] }}
                </td>

                <td class="border-end">
                    {{ $peticion['nombre'] }}
                </td>

                <td class="border-end">
                    {{ $peticion['correo'] }}
                </td>

                <td class="border-end">
                    @if($peticion['estado'] == 'Aprobado')
                        <span class="badge bg-success">Aprobado</span>
                    @elseif($peticion['estado'] == 'Rechazado')
                        <span class="badge bg-danger">Rechazado</span>
                    @else
                        <span class="badge bg-warning text-dark">Pendiente</span>
                    @endif
                </td>

                <td class="text-center">

                    <button 
                        class="btn btn-sm text-white"
                        style="background-color:#008C8C;"
                        data-bs-toggle="modal"
                        data-bs-target="#modal{{ $peticion['id'] }}">
                        Ver
                    </button>

                </td>
            </tr>

            <!-- MODAL DETALLE -->
            <div class="modal fade" id="modal{{ $peticion['id'] }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-header text-white"
                             style="background-color:#008C8C;">
                            <h5 class="modal-title">
                                Detalle Pedido {{ $peticion['numero_pedido'] }}
                            </h5>
                            <button type="button"
                                    class="btn-close btn-close-white"
                                    data-bs-dismiss="modal">
                            </button>
                        </div>

                        <div class="modal-body">

                            <p><strong>Cliente:</strong> {{ $peticion['nombre'] }}</p>
                            <p><strong>Teléfono:</strong> {{ $peticion['telefono'] }}</p>
                            <p><strong>Correo:</strong> {{ $peticion['correo'] }}</p>

                            <hr>

                            <h6 class="fw-bold">Medicamentos solicitados:</h6>

                            @foreach($peticion['medicamentos'] as $med)
                                <div class="border p-2 mb-2 rounded">
                                    {{ $med['mapValue']['fields']['nombre']['stringValue'] }}
                                    -
                                    {{ $med['mapValue']['fields']['cantidad']['integerValue'] }}
                                    caja(s)
                                </div>
                            @endforeach

                            <hr>

                            {{-- SOLO mostrar botones si está pendiente --}}
                            @if($peticion['estado'] == 'Pendiente')

                            <div class="d-flex justify-content-end gap-2">

                                <form method="POST"
                                      action="{{ route('admin.peticiones.estado', [$peticion['id'], 'Aprobado']) }}">
                                    @csrf
                                    @method('PATCH')

                                    <button class="btn btn-success">
                                        Aprobar
                                    </button>
                                </form>

                                <form method="POST"
                                      action="{{ route('admin.peticiones.estado', [$peticion['id'], 'Rechazado']) }}">
                                    @csrf
                                    @method('PATCH')

                                    <button class="btn btn-danger">
                                        Rechazar
                                    </button>
                                </form>

                            </div>

                            @endif

                        </div>

                    </div>
                </div>
            </div>

            @empty

            <tr>
                <td colspan="5" class="text-center py-4 text-muted">
                    No hay pedidos en esta sección
                </td>
            </tr>

            @endforelse

            </tbody>

        </table>
    </div>

</div>