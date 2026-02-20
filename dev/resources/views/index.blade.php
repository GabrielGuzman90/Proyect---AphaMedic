@extends("layouts.master")
@section("content")

{{-- HERO IMAGE CON TEXTO ENCIMA --}}
<div class="position-relative">
    <img src="{{ asset('img/Medicines_share.jpg') }}" 
         class="w-100" 
         style="height: 420px; object-fit: cover;">

    <div class="position-absolute top-50 start-50 translate-middle text-center text-white"
         style="text-shadow: 2px 2px 6px rgba(0,0,0,0.7);">
        <h1 class="fw-bold" style="font-size: 3rem;">
            Donación de medicinas, ayuda a quien lo necesita
        </h1>
    </div>
</div>

{{-- SECCIÓN DE SERVICIOS --}}
<div class="container mt-5">

    <h2 class="text-center mb-4">Conoce nuestros servicios</h2>

    <div class="row g-4">

        {{-- Card 1 --}}
        <div class="col-lg-6 col-md-12">
            <div class="card shadow-sm h-100">
                <img src="{{ asset('img/Donacion-de-Medicamentos.jpg') }}"
                    class="card-img-top"
                    style="height: 250px; object-fit: cover;">

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold">Donar medicamentos</h5>
                    <p class="card-text">
                        Comparte los medicamentos que ya no necesitas y ayuda a quien más lo requiere.
                        Asegura que los medicamentos disponibles para otras personas puedan ser aprovechados 
                        de forma segura y responsable.
                    </p>
                </div>
            </div>
        </div>

        {{-- Card 2 --}}
        <div class="col-lg-6 col-md-12">
            <div class="card shadow-sm h-100">
                <img src="{{ asset('img/medicamentos-pendientes-de-entrega.jpg') }}"
                    class="card-img-top"
                    style="height: 250px; object-fit: cover;">

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold">Recibir medicamentos</h5>
                    <p class="card-text">
                        Si necesitas un tratamiento y no cuentas con los recursos, 
                        aquí puedes encontrar medicamentos donados por otras personas 
                        y recibir el apoyo de manera gratuita.
                    </p>
                </div>
            </div>
        </div>


    </div>
</div>

{{-- CENTROS DE DONACIÓN --}}
<div class="container mt-5 mb-5">
    <h2 class="text-center mb-4">Lugares para donar</h2>

    <div class="row g-4">

        <div class="col-lg-4 col-md-6 col-sm">
            <div class="card shadow-sm">
                <img src="{{ asset('img/images.jpg') }}"
                    class="d-block w-100"
                    style="height:250px; object-fit:cover;">

                <div class="card-body text-center">
                    <h5 class="card-title">Cruz Roja Mexicana</h5>
                    <p class="card-text">Durango, Dgo. México</p>
                    <a href="#" class="btn btn-primary">Saber más</a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm">
            <div class="card shadow-sm">
                <img src="{{ asset('img/hospitalNene.jpeg') }}"
                    class="d-block w-100"
                    style="height:250px; object-fit:cover;">

                <div class="card-body text-center">
                    <h5 class="card-title">Hospital municipal del niño</h5>
                    <p class="card-text">Durango, Dgo. México</p>
                    <a href="#" class="btn btn-primary">Saber más</a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-sm">
            <div class="card shadow-sm">
                <img src="{{ asset('img/ImagePrincipal.jpeg') }}"
                    class="d-block w-100"
                    style="height:250px; object-fit:cover;">

                <div class="card-body text-center">
                    <h5 class="card-title">Caritas del Guadiana A.C</h5>
                    <p class="card-text">Durango, Dgo. México</p>
                    <a href="#" class="btn btn-primary">Saber más</a>
                </div>
            </div>
        </div>

         {{-- Card Leer Contactos --}}
        <div class="col-lg-6 col-md-6 col-sm">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Leer contactos</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Acceso a registros</h6>
                    <p class="card-text">
                        Consulta la información registrada por los usuarios
                        en la sección de contactos.
                    </p>
                    <a href="{{ url('leer-contactos') }}" class="btn btn-primary">
                        Ver contactos
                    </a>
                </div>
            </div>
        </div>

        {{-- Card Usuarios --}}
        <div class="col-lg-6 col-md-6 col-sm">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Usuarios registrados</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Administración del sistema</h6>
                    <p class="card-text">
                        Visualiza la lista completa de usuarios registrados
                        dentro de la plataforma.
                    </p>
                    <a href="{{ url('usuarios') }}" class="btn btn-primary">
                        Ver usuarios
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
