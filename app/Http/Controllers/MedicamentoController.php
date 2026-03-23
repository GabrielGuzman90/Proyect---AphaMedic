<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MedicamentoController extends Controller
{

    // 🔥 FUNCIÓN CENTRAL (Firebase)
    public function obtenerMedicamentosFirebase()
    {
        $baseUrl = "https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents/medicamentos";

        $response = Http::get($baseUrl);

        if (!$response->successful()) {
            dd("Error Firebase", $response->body());
        }

        $documentos = $response['documents'] ?? [];

        $medicamentos = collect();

        foreach ($documentos as $doc) {

            $fields = $doc['fields'] ?? [];
            $id = basename($doc['name']);

            // 🔥 EXISTENCIAS
            $existenciasUrl = "https://firestore.googleapis.com/v1/" . $doc['name'] . "/existencias";
            $existenciasResponse = Http::get($existenciasUrl);
            $existencias = $existenciasResponse['documents'] ?? [];

            foreach ($existencias as $ex) {

                $lugar = basename($ex['name']);
                $disponibilidad = (int)($ex['fields']['disponibilidad']['integerValue'] ?? 0);

                $medicamentos->push((object)[
                    'id' => $id,
                    'nombre' => $fields['nombre']['stringValue'] ?? '',
                    'presentacion' => $fields['presentacion']['stringValue'] ?? '',
                    'cantidad' => (int)($fields['cantidad']['integerValue'] ?? 0),
                    'mg' => (int)($fields['mg']['integerValue'] ?? 0),
                    'categoria' => $fields['categoria']['stringValue'] ?? '',
                    'image_path' => $fields['image_path']['stringValue'] ?? '',
                    'lugar' => $lugar,
                    'disponibilidad' => $disponibilidad,
                    'guardado' => false
                ]);
            }
        }

        return $medicamentos;
    }

    // 🔹 INDEX
    public function index()
    {
        $medicamentos = $this->obtenerMedicamentosFirebase()
            ->groupBy('categoria');

        return view('medicamentos.index', compact('medicamentos'));
    }

    // 🔹 HOME
    public function home()
    {
        $medicamentos = $this->obtenerMedicamentosFirebase()
            ->groupBy('categoria')
            ->take(2);

        return view('home', compact('medicamentos'));
    }

    // 🔹 CREATE
    public function create()
    {
        return view('medicamentos.create');
    }

    // 🔥 STORE (AGREGAR MEDICAMENTOS)
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'presentacion' => 'required|string',
            'cantidad' => 'required|integer|min:1',
            'disponibilidad' => 'required|integer|min:1',
            'mg' => 'required|integer|min:1',
            'categoria' => 'required|string',
            'lugar' => 'required|string',
            'image_path' => 'nullable|image'
        ]);

        $baseUrl = "https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents/medicamentos";

        // 📸 Imagen
        $image = null;
        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path')->store('medicamentos', 'public');
        }

        // 🔍 Buscar si ya existe el medicamento
        $response = Http::get($baseUrl);
        $documentos = $response['documents'] ?? [];

        $medExistente = null;

        foreach ($documentos as $doc) {

            $fields = $doc['fields'] ?? [];

            if (
                ($fields['nombre']['stringValue'] ?? '') == $request->nombre &&
                ($fields['presentacion']['stringValue'] ?? '') == $request->presentacion &&
                (int)($fields['mg']['integerValue'] ?? 0) == (int)$request->mg &&
                ($fields['categoria']['stringValue'] ?? '') == $request->categoria
            ) {
                $medExistente = $doc;
                break;
            }
        }

        // 🔁 SI EXISTE → solo suma disponibilidad
        if ($medExistente) {

            $docName = $medExistente['name'];

            $existenciaUrl = "https://firestore.googleapis.com/v1/" . $docName . "/existencias/" . $request->lugar;

            $existenciaResponse = Http::get($existenciaUrl);

            if ($existenciaResponse->successful()) {

                $actual = (int)($existenciaResponse['fields']['disponibilidad']['integerValue'] ?? 0);
                $nueva = $actual + (int)$request->disponibilidad;

                Http::patch($existenciaUrl, [
                    "fields" => [
                        "disponibilidad" => ["integerValue" => (string)$nueva]
                    ]
                ]);

            } else {

                Http::patch($existenciaUrl, [
                    "fields" => [
                        "disponibilidad" => ["integerValue" => (string)$request->disponibilidad]
                    ]
                ]);
            }

        } 
        // 🆕 SI NO EXISTE → crea todo
        else {

            $createResponse = Http::post($baseUrl, [
                "fields" => [
                    "nombre" => ["stringValue" => $request->nombre],
                    "presentacion" => ["stringValue" => $request->presentacion],
                    "cantidad" => ["integerValue" => (string)$request->cantidad],
                    "mg" => ["integerValue" => (string)$request->mg],
                    "categoria" => ["stringValue" => $request->categoria],
                    "image_path" => ["stringValue" => $image ?? ""]
                ]
            ]);

            $docName = $createResponse['name'];

            $existenciaUrl = "https://firestore.googleapis.com/v1/" . $docName . "/existencias?documentId=" . $request->lugar;

            Http::post($existenciaUrl, [
                "fields" => [
                    "disponibilidad" => ["integerValue" => (string)$request->disponibilidad]
                ]
            ]);
        }

        return redirect()->route('medicamentos.index');
    }

    // 🔹 VER CATEGORIA
    public function verCategoria(Request $request, $categoria)
    {
        $categoriaBuscada = strtolower(trim($categoria));

        $medicamentos = $this->obtenerMedicamentosFirebase()
            ->filter(function ($med) use ($categoriaBuscada) {
                return str_contains(strtolower($med->categoria), $categoriaBuscada)
                    && $med->disponibilidad > 0;
            });

        if ($request->filled('mg')) {
            $medicamentos = $medicamentos->where('mg', (int)$request->mg);
        }

        if ($request->filled('presentacion')) {
            $medicamentos = $medicamentos->where('presentacion', $request->presentacion);
        }

        $mgs = $medicamentos->pluck('mg')->unique();
        $presentaciones = $medicamentos->pluck('presentacion')->unique();

        return view('medicamentos.categoria', [
            'medicamentos' => $medicamentos,
            'categoria' => $categoria,
            'mgs' => $mgs,
            'presentaciones' => $presentaciones
        ]);
    }

}