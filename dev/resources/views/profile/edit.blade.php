@extends('layouts.master')

@section('content')
<div class="container mt-4">

    <h2>Editar Perfil</h2>

    @if(session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mt-2">{{ session('error') }}</div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="name" class="form-control" value="{{ $user['name'] }}" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $user['email'] }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar cambios</button>
    </form>

    <hr>

    <form action="{{ route('profile.delete') }}" method="POST" onsubmit="return confirm('¿Seguro quieres eliminar tu perfil?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Eliminar Perfil</button>
    </form>

</div>
@endsection