@extends('layouts.master')

@section('content')

<div class="container py-5">

    <h6 class="text-secondary fw-semibold mb-1">Administración</h6>
    <h2 class="fw-bold mb-4">Usuarios registrados</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">

        <!-- BARRA SUPERIOR VERDE -->
        <div class="card-header text-white fw-semibold"
             style="background-color:#008C8C;">
            Lista de usuarios del sistema
        </div>

        <div class="table-responsive">
            <table class="table mb-0">

                <!-- ENCABEZADO VERDE -->
                <thead style="background-color:#008C8C; color:white;">
                    <tr>
                        <th class="px-4 py-3 border-end">ID</th>
                        <th class="py-3 border-end">Nombre</th>
                        <th class="py-3 border-end">Email</th>
                        <th class="py-3 text-center">Opciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $user)
                    <tr style="border-bottom:1px solid #dee2e6;">

                        <td class="px-4 border-end">{{ $user->id }}</td>
                        <td class="border-end">{{ $user->name }}</td>
                        <td class="border-end">{{ $user->email }}</td>

                        <td class="text-center">

                            <!-- EDITAR -->
                            <button 
                                type="button"
                                class="btn btn-sm text-white me-2"
                                style="background-color:#008C8C;"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal{{ $user->id }}">
                                Editar
                            </button>

                            <!-- ELIMINAR -->
                            <button 
                                type="button"
                                class="btn btn-outline-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal{{ $user->id }}">
                                Eliminar
                            </button>

                        </td>
                    </tr>

                    <!-- MODAL ELIMINAR -->
                    <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-header text-white"
                                     style="background-color:#008C8C;">
                                    <h5 class="modal-title">Confirmar eliminación</h5>
                                    <button type="button" 
                                            class="btn-close btn-close-white"
                                            data-bs-dismiss="modal">
                                    </button>
                                </div>

                                <div class="modal-body">
                                    ¿Seguro que deseas eliminar al usuario 
                                    <strong>{{ $user->name }}</strong>?
                                </div>

                                <div class="modal-footer">
                                    <button type="button" 
                                            class="btn btn-secondary"
                                            data-bs-dismiss="modal">
                                        Cancelar
                                    </button>

                                    <form action="{{ route('dashboard.user.delete', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger">
                                            Sí, eliminar
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- MODAL EDITAR -->
                    <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <form action="{{ route('dashboard.user.update', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="modal-header text-white"
                                         style="background-color:#008C8C;">
                                        <h5 class="modal-title">Editar usuario</h5>
                                        <button type="button" 
                                                class="btn-close btn-close-white"
                                                data-bs-dismiss="modal">
                                        </button>
                                    </div>

                                    <div class="modal-body">

                                        <div class="mb-3">
                                            <label class="fw-semibold">Nombre</label>
                                            <input type="text" 
                                                   name="name" 
                                                   class="form-control"
                                                   value="{{ $user->name }}"
                                                   required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="fw-semibold">Email</label>
                                            <input type="email" 
                                                   name="email" 
                                                   class="form-control"
                                                   value="{{ $user->email }}"
                                                   required>
                                        </div>

                                    </div>

                                    <div class="modal-footer">
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
                        </div>
                    </div>

                    @endforeach
                </tbody>

            </table>
        </div>

    </div>

</div>

@endsection