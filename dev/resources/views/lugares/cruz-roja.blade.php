@extends("layouts.master")

@section("content")

{{-- HERO IMAGE --}}
<div class="position-relative">

    <img src="{{ asset('img/fondoCruz.jpg') }}"
         class="w-100"
         style="height:420px; object-fit:cover;">

    <div class="position-absolute top-50 start-50 translate-middle text-center text-white"
         style="text-shadow:2px 2px 6px rgba(0,0,0,0.7);">

        <h1 class="fw-bold" style="font-size:3rem;">
            Cruz Roja Mexicana
        </h1>

    </div>

</div>


{{-- SECCIÓN BLANCA --}}
<div style="background:white; padding-bottom:60px;">

<div class="container">

    <div class="card shadow border-0"
         style="margin-top:-70px; background:#0A9A9E; color:white; border-radius:14px; padding:50px;">

        <div class="row align-items-center">

            {{-- TEXTO --}}
            <div class="col-md-8 pe-5">

                <h4 class="fw-bold mb-3">Cruz Roja Durango</h4>

                <p style="line-height:1.6; font-size:16px;">
                    Brindamos atención médica, primeros auxilios y apoyo humanitario a comunidades vulnerables en todo el estado.
                    Tu colaboración nos permite responder a emergencias, trasladar pacientes y ofrecer servicios esenciales
                    a quienes más lo necesitan. Infórmate, participa y forma parte de una red que salva vidas todos los días.
                </p>

            </div>

            {{-- LOGO CIRCULAR --}}
            <div class="col-md-4 d-flex justify-content-center">

                <div style="
                        width:150px;
                        height:150px;
                        background:#e5e5e5;
                        border-radius:50%;
                        overflow:hidden;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                    ">

                    <img src="{{ asset('img/Cruz_Roja.png') }}"
                         style="width:100%; height:100%; object-fit:cover;">

                </div>

            </div>

        </div>

    </div>

</div>

</div>


{{-- SECCIÓN GRIS (UBICACIÓN) --}}
<div style="background:#f4f6f8; padding:60px 0;">

{{-- TITULO UBICACIÓN --}}
<div class="container">

    <h4 class="text-center mb-5 fw-semibold">
        <i class="bi bi-geo-alt-fill"></i> Ubicación
    </h4>

</div>


{{-- CARD UBICACIÓN --}}
<div class="container">

    <div class="card shadow border-0">

        <div class="row g-0">

            {{-- INFO --}}
            <div class="col-md-6 p-5 d-flex flex-column justify-content-center"
                 style="background:#0A9A9E; color:white; border-radius:10px 0 0 10px;">

                <h5 class="fw-bold mb-3">Cruz Roja, Durango</h5>

                <p class="mb-2">
                    Avenida Constitución #1234<br>
                    Colonia Centro<br>
                    Durango, Durango, México
                </p>

                <p class="mb-2">
                    Teléfono: (618) XXX-XXXX
                </p>

                <p class="mb-3">
                    Horario de atención: 24 horas
                </p>

                <a href="https://www.openstreetmap.org/export/embed.html?bbox=-104.6600%2C24.0240%2C-104.6460%2C24.0310&layer=mapnik&marker=24.0276%2C-104.6532"
                   target="_blank"
                   class="btn btn-light fw-semibold">

                    Abrir en mapa

                </a>

            </div>


            {{-- MAPA --}}
            <div class="col-md-6">

                <iframe
                    width="100%"
                    height="100%"
                    style="border:0; min-height:360px; border-radius:0 10px 10px 0;"
                    loading="lazy"
                    src="https://www.openstreetmap.org/export/embed.html?bbox=-104.6600%2C24.0240%2C-104.6460%2C24.0310&layer=mapnik&marker=24.0276%2C-104.6532">
                </iframe>

            </div>

        </div>

    </div>

</div>


{{-- BOTON DONAR --}}
<div class="container text-center mt-5">

    <a href="#"
       class="btn btn-lg text-white fw-semibold"
       style="background:#0A9A9E; padding:14px 40px; border-radius:8px;">

        Donar

    </a>

</div>

</div>

@endsection