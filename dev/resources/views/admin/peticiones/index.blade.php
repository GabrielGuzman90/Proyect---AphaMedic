@extends('layouts.master')

@section('content')

<div class="container py-5">

    <h6 class="text-secondary fw-semibold mb-1">Administración</h6>
    <h2 class="fw-bold mb-4">Pedidos recibidos</h2>

    {{-- 🔥 BOTONES DE NAVEGACIÓN --}}
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

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- 🔥 TABLA REUTILIZABLE --}}
    @include('admin.peticiones.tabla')

</div>

@endsection