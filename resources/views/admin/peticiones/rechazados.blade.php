@extends('layouts.master')

@section('content')

<div class="container py-5">

    <h6 class="text-secondary fw-semibold mb-1">Administración</h6>
    <h2 class="fw-bold mb-4 text-danger">Pedidos Rechazados</h2>

    <div class="d-flex gap-2 mb-4">

        <a href="{{ route('admin.peticiones') }}"
           class="btn btn-warning text-dark fw-semibold">
            Pendientes
        </a>

        <a href="{{ route('admin.peticiones.aceptados') }}"
           class="btn btn-success fw-semibold">
            Aprobados
        </a>

        <a href="{{ route('admin.peticiones.rechazados') }}"
           class="btn btn-danger fw-semibold">
            Rechazados
        </a>

    </div>

    @include('admin.peticiones.tabla')

</div>

@endsection