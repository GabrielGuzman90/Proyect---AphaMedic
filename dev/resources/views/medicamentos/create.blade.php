@extends('layouts.master')

@section('content')
<div class="container py-5">

    <!-- ENCABEZADO -->
    <h6 class="text-secondary fw-semibold mb-1">Medicamentos</h6>
    <h2 class="fw-bold mb-3">Registrar medicamento</h2>

    <p class="mb-4" style="max-width: 600px;">
        Completa el siguiente formulario para registrar un medicamento disponible en la plataforma.
        Asegúrate de ingresar correctamente la información.
    </p>

    <div class="row">

        <!-- FORMULARIO -->
        <div class="col-md-7">

            <form method="POST"
                  action="{{ route('medicamentos.store') }}"
                  enctype="multipart/form-data">

                @csrf

                <div class="row">

                    <!-- Nombre -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold">Nombre</label>
                        <select name="nombre" class="form-control" required>
                            <option value="" disabled selected>Selecciona un medicamento</option>
                            <option value="Paracetamol">Paracetamol</option>
                            <option value="Ibuprofeno">Ibuprofeno</option>
                            <option value="Loratadina">Loratadina</option>
                            <option value="Salbutamol">Salbutamol</option>
                            <option value="Metronidazol">Metronidazol</option>
                            <option value="Albendazol">Albendazol</option>
                            <option value="Amoxicilina">Amoxicilina</option>
                            <option value="Multivitaminico">Multivitamínico</option>
                            <option value="Fluoxetina">Fluoxetina</option>
                            <option value="Dextrometorfano">Dextrometorfano</option>
                        </select>
                    </div>

                    <!-- Presentacion -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold">Presentación</label>
                        <select name="presentacion"
                                class="form-control"
                                required>
                            <option value="" disabled selected>Elige una opción</option>
                            <option value="Tabletas">Tabletas</option>
                            <option value="Liquido">Liquido</option>
                            <option value="Capsulas">Capsulas</option>
                            <option value="Inhalador">Inhalador</option>
                        </select>
                    </div>

                    <!-- Cantidad -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold">Cantidad</label>
                        <input type="number"
                               name="cantidad"
                               class="form-control"
                               required>
                    </div>

                    <!-- MG -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold">MG</label>
                        <input type="number"
                               name="mg"
                               class="form-control"
                               required>
                    </div>

                    <!-- Categoria -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold">Categoría</label>
                        <select name="categoria" class="form-control" required>
                            <option value="" disabled selected>Selecciona categoría</option>
                            <option value="Analgesico">Analgésico</option>
                            <option value="Antihistaminico">Antihistamínico</option>
                            <option value="Broncodilatador">Broncodilatador</option>
                            <option value="Antibacterial">Antibacterial</option>
                            <option value="Antiparasitario">Antiparasitario</option>
                            <option value="Antibiotico">Antibiótico</option>
                            <option value="Suplemento">Suplemento</option>
                            <option value="Antitusivo">Antitusivo</option>
                        </select>
                    </div>

                    <!-- Disponibilidad -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold">Disponibilidad</label>
                        <input type="number"
                               name="disponibilidad"
                               class="form-control"
                               required>
                    </div>

                    <!-- Imagen -->
                    <div class="col-12 mb-4">
                        <label class="fw-semibold">Imagen del medicamento</label>
                        <input type="file"
                               name="image_path"
                               class="form-control">
                    </div>

                </div>

                <!-- TEXTO -->
                <p class="small text-secondary mb-3">
                    Al enviar este formulario, confirmas que la información del medicamento es correcta
                    y puede ser publicada en la plataforma.
                </p>

                <!-- BOTON -->
                <button type="submit"
                    class="btn w-100 text-white fw-semibold"
                    style="background-color:#008C8C; border:none; padding:10px; border-radius:4px;">
                    Guardar medicamento
                </button>

            </form>

        </div>

        <!-- INFO DERECHA -->
        <div class="col-md-5 ps-md-5 mt-5 mt-md-0">

            <h5 class="fw-bold mb-2">Registro seguro</h5>
            <p class="mb-4">
                La información registrada será utilizada para mostrar el medicamento
                a otros usuarios que lo necesiten.
            </p>

            <h5 class="fw-bold mb-2">Recomendación</h5>
            <p>
                Verifica que la imagen y los datos sean correctos antes de guardar.
                <br>
                Esto facilitará su identificación.
            </p>

        </div>

    </div>

</div>
@endsection