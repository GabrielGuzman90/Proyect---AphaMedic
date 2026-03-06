@extends('layouts.master')

@section('content')

<div class="container py-5">

    <!-- TÍTULO -->
    <h6 class="text-secondary fw-semibold mb-1">Administración</h6>
    <h2 class="fw-bold mb-4">Panel principal</h2>

    <div class="row g-4">

        <!-- Medicamentos -->
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h6 class="text-muted">INVENTARIO</h6>
                    <h5 class="fw-bold">Medicamentos</h5>

                    <p class="text-muted small">
                        Gestiona los medicamentos disponibles en la plataforma.
                    </p>

                    <a href="{{ route('medicamentos.index') }}" class="btn btn-primary mt-auto">
                        Ver medicamentos
                    </a>
                </div>
            </div>
        </div>

        <!-- Agregar Medicamento -->
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm h-100 border-success">
                <div class="card-body d-flex flex-column">
                    <h6 class="text-muted">INVENTARIO</h6>
                    <h5 class="fw-bold text-success">Agregar Medicamento</h5>

                    <p class="text-muted small">
                        Registra un nuevo medicamento o aumenta la disponibilidad existente.
                    </p>

                    <a href="{{ route('medicamentos.create') }}" class="btn btn-success mt-auto">
                        Agregar ahora
                    </a>
                </div>
            </div>
        </div>

        <!-- Donaciones -->
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h6 class="text-muted">APORTES</h6>
                    <h5 class="fw-bold">Donaciones</h5>

                    <p class="text-muted small">
                        Consulta las donaciones realizadas por los usuarios.
                    </p>

                    <a href="{{ route('admin.peticiones') }}" class="btn btn-primary mt-auto">
                        Ver pedidos
                    </a>
                </div>
            </div>
        </div>

        <!-- Contactos -->
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h6 class="text-muted">MENSAJES</h6>
                    <h5 class="fw-bold">Contactos</h5>

                    <p class="text-muted small">
                        Revisa los mensajes enviados por los usuarios.
                    </p>

                    <a href="{{ url('leer-contactos') }}" class="btn btn-primary mt-auto">
                        Ver contactos
                    </a>
                </div>
            </div>
        </div>

        <!-- Usuarios -->
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h6 class="text-muted">USUARIOS</h6>
                    <h5 class="fw-bold">Usuarios</h5>

                    <p class="text-muted small">
                        Administra los usuarios registrados en el sistema.
                    </p>

                    <a href="{{ url('dashboard') }}" class="btn btn-primary mt-auto">
                        Ver usuarios
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection