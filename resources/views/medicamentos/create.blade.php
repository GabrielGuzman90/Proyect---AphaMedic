@extends('layouts.master')

@section('content')
<div class="container py-5">

    <h6 class="text-secondary fw-semibold mb-1">Medicamentos</h6>
    <h2 class="fw-bold mb-3">Registrar medicamento</h2>

    <p class="mb-4" style="max-width: 600px;">
        Completa el siguiente formulario para registrar un medicamento disponible en la plataforma.
    </p>

    <div class="row">

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
                        <select name="presentacion" class="form-control" required>
                            <option disabled selected>Selecciona</option>
                            <option value="Tabletas">Tabletas</option>
                            <option value="Liquido">Liquido</option>
                            <option value="Inhalador">Inhalador</option>
                            <option value="Capsulas">Capsulasr</option>
                        </select>
                    </div>

                    <!-- CANTIDAD (dato del medicamento) -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold">Cantidad por presentación</label>
                        <input type="number" name="cantidad" class="form-control" required>
                    </div>

                    <!-- DISPONIBILIDAD (lo que agregas) -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold">Cantidad a agregar</label>
                        <input type="number" name="disponibilidad" class="form-control" required>
                    </div>

                    <!-- MG -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold">MG</label>
                        <input type="number" name="mg" class="form-control" required>
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

                    <!-- LUGAR -->
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold">Lugar</label>
                        <select name="lugar" class="form-control" required>
                            <option disabled selected>Selecciona</option>
                            <option value="cruz_roja">Cruz Roja</option>
                            <option value="hospital_nino">Hospital del Niño</option>
                            <option value="caritas">Cáritas</option>
                        </select>
                    </div>

                    <!-- Imagen -->
                    <div class="col-12 mb-4">
                        <label class="fw-semibold">Imagen</label>
                        <input type="file" name="image_path" class="form-control">
                    </div>

                </div>

                <button type="submit"
                    class="btn w-100 text-white fw-semibold"
                    style="background-color:#008C8C;">
                    Guardar medicamento
                </button>

            </form>

        </div>

    </div>

</div>
@endsection