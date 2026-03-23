@extends('layouts.master')

@section('content')

<div class="container py-5">

    <h6 class="text-secondary fw-semibold mb-1">Administrador</h6>

    <h2 class="fw-bold mb-3">
        Panel de Administrador
    </h2>

    <p class="mb-4">
        Bienvenido <strong>{{ session('firebase_user.name') }}</strong>, tienes privilegios de administrador.
    </p>

    <div class="card shadow-sm p-4">
        <h5 class="fw-semibold mb-3">Opciones de administración</h5>

        <ul>
            <li>Gestionar medicamentos</li>
            <li>Ver usuarios</li>
            <li>Administrar sistema</li>
        </ul>

    </div>

</div>

@endsection