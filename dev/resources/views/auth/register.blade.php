@extends('layouts.app')

@php
    $noNavbar = true;
@endphp

@section('content')
<div class="container d-flex justify-content-center">
    <div class="col-lg-7 col-md-8">
        <div class="text-center mt-4 mb-4">
            <h2 class="fw-bold">Crear una cuenta</h2>
            <p class="text-muted">Únete a Alphamedic y ayuda a quienes más lo necesitan.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="p-4 shadow rounded bg-white">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nombre completo</label>
                <input id="name" type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    name="name" value="{{ old('name') }}" required autofocus>

                @error('name')
                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input id="email" type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    name="email" value="{{ old('email') }}" required>

                @error('email')
                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input id="password" type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    name="password" required>

                @error('password')
                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password-confirm" class="form-label">Confirmar contraseña</label>
                <input id="password-confirm" type="password" class="form-control"
                    name="password_confirmation" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary"
                    style="background-color: #009688; border-color: #009688;">
                    Crear cuenta
                </button>
            </div>

            <div class="text-center mt-3">
                <small>¿Ya tienes una cuenta?
                    <a href="{{ route('login') }}">Inicia sesión</a>
                </small>
            </div>
        </form>
    </div>
</div>
@endsection