@extends('layouts.master')

@section('content')

<div class="container mt-5">

    <h3 class="mb-4">Mis pedidos</h3>

    @forelse($pedidos as $pedido)

        <div class="card mb-4 shadow-sm p-3">

            <div class="d-flex justify-content-between align-items-center mb-2">

                <div>
                    <h5 class="mb-1">
                        Pedido: {{ $pedido['numero'] }}
                    </h5>

                    <small class="text-muted">
                        Fecha: {{ \Carbon\Carbon::parse($pedido['fecha'])->format('d/m/Y H:i') }}
                    </small>
                </div>

                <span class="badge 
                    @if($pedido['estado'] == 'Pendiente') bg-warning
                    @elseif($pedido['estado'] == 'Aprobado') bg-success
                    @elseif($pedido['estado'] == 'Rechazado') bg-danger
                    @else bg-secondary
                    @endif
                ">
                    {{ $pedido['estado'] }}
                </span>

            </div>

            <hr>

            @foreach($pedido['medicamentos'] as $med)

                <div class="mb-2">

                    <strong>{{ $med['nombre'] }}</strong>

                    <div style="font-size:13px">
                        {{ $med['mg'] }} mg | {{ $med['presentacion'] }}
                    </div>

                    <div style="font-size:13px">
                        Cantidad: {{ $med['cantidad'] }}
                    </div>

                    <div style="font-size:13px">
                        Instituto: {{ $med['institucion'] }}
                    </div>

                </div>

                <hr>

            @endforeach

        </div>

    @empty

        <div class="alert alert-info">
            No has realizado pedidos aún.
        </div>

    @endforelse

</div>

@endsection