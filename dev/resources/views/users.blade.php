@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Fecha de creado</th>
                </tr>
            </thead>

            <tbody>
                @foreach($usuarios as $user)
                <tr>
                    <td>{{ $user-> id }}</td>
                    <td>{{ $user-> name }}</td>
                    <td>{{ $user-> email }}</td>
                    <td>{{ $user-> created_at }}</td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>
@endsection