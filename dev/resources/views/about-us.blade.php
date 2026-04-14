@extends("layouts.master")

@section("content")

{{-- HERO IMAGE --}}
<div class="position-relative">

    <img
        src="{{ asset('img/test2.jpg') }}"
        class="w-100"
        style="height:420px; object-fit:cover;"
    >

</div>

{{-- SECCIÓN ABOUT US --}}
<div style="background:#f4f4f4; padding-bottom:60px;">

    <div class="container">

        <div
            class="card shadow border-0"
            style="
                margin-top:-70px;
                background:#018d97;
                color:white;
                border-radius:12px;
                padding:45px;
            "
        >

            <h5 class="fw-bold mb-4">
                About us
            </h5>

            <div class="row align-items-center">

                {{-- TEXTO --}}
                <div class="col-md-8">

                    <p style="line-height:1.7;">
                        En Durango, muchas personas no pueden pagar sus medicamentos.
                    </p>

                    <p style="line-height:1.7;">
                        Con tu ayuda, podemos llevar tratamientos a quienes más lo necesitan.
                    </p>

                    <p style="line-height:1.7;">
                        Recibimos medicinas en buen estado y las entregamos a clínicas
                        y familias vulnerables en todo el estado.
                    </p>

                    <p style="line-height:1.7;">
                        Dona hoy y mejora una vida.
                    </p>

                </div>


                {{-- ICONO --}}
                <div class="col-md-4 text-center">

                    <img
                        src="{{ asset('img/Logo.png') }}"
                        style="max-width:250px;"
                    >

                </div>

            </div>

        </div>

    </div>

</div>



{{-- SECCIÓN INFORMACIÓN --}}
<div style="background:#333; color:white;">

    <div class="container">

        <div class="row">

            {{-- TEXTO --}}
            <div class="col-md-6 p-5">

                <h5 class="fw-bold mb-4">
                    Un mundo con acceso a medicamentos
                </h5>

                <p style="line-height:1.7; font-size:14px;">
                    Creemos en un mundo donde todas las personas puedan acceder a
                    los medicamentos que necesitan para cuidar su salud.
                </p>

                <p style="line-height:1.7; font-size:14px;">
                    Apoyamos a centros de salud y comunidades vulnerables,
                    entregando medicinas donadas y fortaleciendo su capacidad
                    para atender a quienes más lo necesitan.
                </p>

                <p style="line-height:1.7; font-size:14px;">
                    Trabajamos junto a aliados locales e internacionales para
                    crear soluciones efectivas y sostenibles.
                </p>

                <p style="line-height:1.7; font-size:14px;">
                    Gracias a la colaboración con nuestros donantes y socios,
                    multiplicamos el impacto de cada aportación.
                </p>

            </div>


            {{-- IMAGEN --}}
            <div class="col-md-6 p-0">

                <img
                    src="{{ asset('img/medicamentos-pendientes-de-entrega.jpg') }}"
                    class="w-100 h-100"
                    style="object-fit:cover;"
                >

            </div>

        </div>

    </div>

</div>

{{-- SECCIÓN DONACIÓN --}}
<div style="background:#e9e9e9; padding:60px 0;">

    <div class="container text-center">

        <p style="max-width:600px; margin:auto; line-height:1.7;">

            Cada medicamento que donas puede marcar la diferencia.

            Gracias a nuestra red de centros de salud, tus donaciones
            llegan a personas que realmente los necesitan,
            multiplicando su valor e impacto.

        </p>

        <a
            href="#"
            class="btn mt-4 text-white"
            style="
                background:#018d97;
                padding:12px 35px;
                border-radius:6px;
            "
        >
            Donar ahora
        </a>

    </div>

</div>

@endsection