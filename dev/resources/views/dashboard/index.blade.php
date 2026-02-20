@extends('layouts.master')

@section('content')
<div class="container mt-4">

    <h2>Dashboard - Usuarios Registrados</h2>

    @if(session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Opciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($users as $user)
            <tr>

                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>

                <td>

                    <!-- BOTON EDITAR -->
                    <button 
                        type="button"
                        class="btn btn-warning btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#editModal{{ $user->id }}">
                        Editar
                    </button>


                    <!-- BOTON ELIMINAR (SIN FORM) -->
                    <button 
                        type="button"
                        class="btn btn-danger btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteModal{{ $user->id }}">
                        Eliminar
                    </button>



                    <!-- MODAL ELIMINAR -->
                    <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title">Confirmar eliminación</h5>

                                    <button 
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal">
                                    </button>
                                </div>


                                <div class="modal-body">
                                    ¿Estás seguro que deseas eliminar el usuario 
                                    <strong>{{ $user->name }}</strong>?
                                </div>


                                <div class="modal-footer">

                                    <button 
                                        type="button"
                                        class="btn btn-secondary"
                                        data-bs-dismiss="modal">
                                        Cancelar
                                    </button>


                                    <!-- FORM REAL DE ELIMINACION -->
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

                                    <div class="modal-header">

                                        <h5 class="modal-title">
                                            Editar Usuario
                                        </h5>

                                        <button 
                                            type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal">
                                        </button>

                                    </div>


                                    <div class="modal-body">

                                        <div class="mb-3">

                                            <label>Nombre</label>

                                            <input 
                                                type="text" 
                                                name="name" 
                                                class="form-control"
                                                value="{{ $user->name }}"
                                                required>

                                        </div>


                                        <div class="mb-3">

                                            <label>Email</label>

                                            <input 
                                                type="email" 
                                                name="email" 
                                                class="form-control"
                                                value="{{ $user->email }}"
                                                required>

                                        </div>

                                    </div>


                                    <div class="modal-footer">

                                        <button 
                                            type="button"
                                            class="btn btn-secondary"
                                            data-bs-dismiss="modal">
                                            Cancelar
                                        </button>


                                        <button 
                                            type="submit"
                                            class="btn btn-primary">
                                            Guardar cambios
                                        </button>

                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>


                </td>

            </tr>
            @endforeach

        </tbody>
    </table>

</div>
@endsection