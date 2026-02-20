@extends('layouts.master')

@section('content')
<div class="container py-5">

    <h6 class="text-secondary fw-semibold mb-1">Contacto</h6>
    <h2 class="fw-bold mb-3">Ponte en contacto con nosotros</h2>

    <p class="mb-4" style="max-width: 600px;">
        Queremos ayudarte a encontrar los medicamentos que necesitas. Desde esta plataforma, puedes visualizar los medicamentos disponibles donados por otros usuarios en Durango, Dgo., y realizar una solicitud si están en existencia.
    </p>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Ups!! Hay algunos problemas bro</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <!-- FORMULARIO -->
        <div class="col-md-7">

            <form method="POST" action="{{ url('guardar-contacto') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold">Nombre</label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror">
                        @error('nombre')
                        <small class="text-danger">Este campo es obligatorio</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold">Correo Electrónico</label>
                        <input type="email" name="correo" class="form-control @error('correo') is-invalid @enderror">
                        @error('correo')
                        <small class="text-danger">Correo no válido</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold">Prioridad</label>
                        <select name="prioridad" class="form-control @error('prioridad') is-invalid @enderror">
                            <option value="" disabled selected>Elige una opción</option>
                            <option value="alta">Alta</option>
                            <option value="media">Media</option>
                            <option value="baja">Baja</option>
                        </select>
                        @error('prioridad')
                        <small class="text-danger">Este campo es obligatorio</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold">Asunto</label>
                        <input type="text" name="asunto" class="form-control @error('asunto') is-invalid @enderror">
                        @error('asunto')
                        <small class="text-danger">Este campo es obligatorio</small>
                        @enderror
                    </div>

                    <div class="col-12 mb-4">
                        <label class="fw-semibold">Mensaje</label>
                        <textarea name="mensaje" rows="4" class="form-control @error('mensaje') is-invalid @enderror"></textarea>
                        @error('mensaje')
                        <small class="text-danger">Este campo es obligatorio</small>
                        @enderror
                    </div>
                </div>

                <p class="small text-secondary mb-3">
                    Al enviar mis datos personales, doy mi consentimiento para que Alphamedic procese mi información
                    de acuerdo con el
                    <a href="#" class="text-decoration-underline">Aviso de privacidad</a>.
                </p>

                <button type="submit" class="btn w-100 text-white fw-semibold"
                    style="background-color:#008C8C; border:none; padding:10px; border-radius:4px;">
                    Enviar mensaje
                </button>

            </form>
        </div>

        <!-- INFO DERECHA -->
        <div class="col-md-5 ps-md-5 mt-5 mt-md-0">
            <h5 class="fw-bold mb-2">Llamar</h5>
            <p class="mb-4">
                Llámanos al 618-127-0954<br>
                Lunes a Viernes — 8:00 a.m. a 6:00 p.m.<br>
                Durango, México
            </p>

            <h5 class="fw-bold mb-2">Soporte de productos y cuentas</h5>
            <p>
                Estamos a tu disposición en todo momento.
                <br>
                <a href="#" class="text-decoration-underline">Visita el centro de ayuda</a>.
            </p>
        </div>
    </div>

</div>
@endsection