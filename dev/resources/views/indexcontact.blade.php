@extends('layouts.app')
@section('content')

<style>
    /* Color turquesa principal de tus diseños */
    .thead-alpha th {
        background-color: #0A8FA0 !important; /* Turquesa */
        color: #ffffff !important;
        padding: 12px;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    table.table {
        border-radius: 10px;
        overflow: hidden;
    }

    tbody tr:hover {
        background: rgba(10, 143, 160, 0.08);
    }
</style>

<div class="container mt-4">
    <div class="row">
        <table class="table">
            <thead class="thead-alpha">
                <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Correo</th>
                    <th scope="col">Prioridad</th>
                    <th scope="col">Asunto</th>
                    <th scope="col">Mensaje</th>
                </tr>
            </thead>

            <tbody>
                @foreach($mensajes as $mensaje)
                <tr>
                    <td>{{$mensaje -> nombre}}</td>
                    <td>{{$mensaje -> correo}}</td>
                    <td>{{$mensaje -> prioridad}}</td>
                    <td>{{$mensaje -> asunto}}</td>
                    <td>{{$mensaje -> mensaje}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
