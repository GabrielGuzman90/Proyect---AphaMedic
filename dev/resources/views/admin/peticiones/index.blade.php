@extends('layouts.master')

@section('content')

<div class="container py-5">

    <h6 class="text-secondary fw-semibold mb-1">Administración</h6>
    <h2 class="fw-bold mb-4">Pedidos recibidos</h2>

    {{-- 🔥 BOTONES MEJORADOS --}}
    <div class="d-flex gap-2 mb-4">

        <a href="{{ route('admin.peticiones') }}"
           class="btn filtro-btn filtro-activo">
            Pendientes
        </a>

        <a href="{{ route('admin.peticiones.aceptados') }}"
           class="btn filtro-btn btn-outline-success">
            Aprobados
        </a>

        <a href="{{ route('admin.peticiones.rechazados') }}"
           class="btn filtro-btn btn-outline-danger">
            Rechazados
        </a>

    </div>

    @if(session('success'))
        <div class="alert alert-success fade show">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-4">
        <input 
            type="text" 
            id="buscador"
            class="form-control shadow-sm"
            placeholder="Buscar por N° pedido, nombre o correo..."
        >
    </div>

    {{-- TABLA --}}
    <div class="fade-tab">
        @include('admin.peticiones.tabla')
    </div>

</div>

@endsection