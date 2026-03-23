@extends('layouts.master')

@section('content')

<div class="container mt-4">

<h3 class="fw-bold mb-4">Confirmar solicitud</h3>

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

@if(empty($cart))
<div class="alert alert-warning">
    No hay productos en el carrito
</div>
@else

<table class="table table-bordered">
<thead>
<tr>
<th>Medicamento</th>
<th>Presentación</th>
<th>MG</th>
<th>Cantidad</th>
</tr>
</thead>

<tbody>
@foreach($cart as $item)
<tr>
<td>{{ $item['nombre'] }}</td>
<td>{{ $item['presentacion'] }}</td>
<td>{{ $item['mg'] }}</td>
<td>{{ $item['cantidad'] }}</td>
</tr>
@endforeach
</tbody>
</table>

<form action="{{ route('peticion.store') }}" method="POST">
@csrf

<button type="submit" class="btn btn-success w-100">
Enviar solicitud
</button>

</form>

@endif

</div>

@endsection