@extends('layouts.master')

@section('content')

<div class="container py-5">

    <h6 class="text-secondary fw-semibold mb-1">Administración</h6>
    <h2 class="fw-bold mb-4">Administradores</h2>

    {{-- ALERTAS --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- 🔍 BUSCADOR --}}
    <form method="GET" class="mb-4">
        <input type="text"
               name="search"
               class="form-control"
               placeholder="Buscar por ID, nombre o email..."
               value="{{ $search }}">
    </form>

    <div class="card shadow-sm border-0">

        <div class="card-header text-white fw-semibold"
             style="background-color:#008C8C;">
            Lista de administradores
        </div>

        <div class="table-responsive">
            <table class="table mb-0">

                <thead style="background-color:#008C8C; color:white;">
                    <tr>
                        <th class="px-4 py-3 border-end">ID</th>
                        <th class="py-3 border-end">Nombre</th>
                        <th class="py-3 border-end">Email</th>
                        <th class="py-3 text-center">Opciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($admins as $user)
                    <tr>
                        <td class="px-4 border-end">{{ $user->id }}</td>
                        <td class="border-end">{{ $user->name }}</td>
                        <td class="border-end">{{ $user->email }}</td>

                        <td class="text-center">

                            <button class="btn btn-sm text-white me-2"
                                style="background-color:#008C8C;"
                                data-bs-toggle="modal"
                                data-bs-target="#editAdmin{{ $user->id }}">
                                Editar
                            </button>

                            <button class="btn btn-outline-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteAdmin{{ $user->id }}">
                                Eliminar
                            </button>

                        </td>
                    </tr>

                    {{-- MODAL EDITAR --}}
                    <div class="modal fade" id="editAdmin{{ $user->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <form action="{{ route('dashboard.admin.update', $user->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="modal-header text-white" style="background-color:#008C8C;">
                                        <h5>Editar administrador</h5>
                                        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <input type="text" name="name" class="form-control mb-2"
                                               value="{{ $user->name }}" required>

                                        <input type="email" name="email" class="form-control"
                                               value="{{ $user->email }}" required>
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button class="btn text-white" style="background-color:#008C8C;">Guardar</button>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>

                    {{-- MODAL ELIMINAR --}}
                    <div class="modal fade" id="deleteAdmin{{ $user->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <div class="modal-header text-white" style="background-color:#008C8C;">
                                    <h5>Eliminar administrador</h5>
                                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    ¿Eliminar a <strong>{{ $user->name }}</strong>?
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

                                    <form action="{{ route('dashboard.admin.delete', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger">Eliminar</button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>

                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-3">No hay administradores</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>

@endsection