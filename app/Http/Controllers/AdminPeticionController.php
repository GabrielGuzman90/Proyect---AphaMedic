<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class AdminPeticionController extends Controller
{
    private $baseUrl = "https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents/peticiones";
    private $medicamentosUrl = "https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents/medicamentos";

    // ==========================
    // LISTAR POR ESTADO
    // ==========================

    public function index()
    {
        return $this->listarPorEstado('Pendiente', 'admin.peticiones.index');
    }

    public function aceptados()
    {
        return $this->listarPorEstado('Aprobado', 'admin.peticiones.aceptados');
    }

    public function rechazados()
    {
        return $this->listarPorEstado('Rechazado', 'admin.peticiones.rechazados');
    }

    private function listarPorEstado($estadoFiltro, $vista)
    {
        $response = Http::get($this->baseUrl);
        $peticiones = [];

        if ($response->successful()) {
            $documents = $response->json()['documents'] ?? [];

            foreach ($documents as $doc) {
                $fields = $doc['fields'];
                $estado = $fields['estado']['stringValue'] ?? 'Pendiente';

                if ($estado == $estadoFiltro) {
                    $peticiones[] = [
                        'id' => basename($doc['name']),
                        'numero_pedido' => $fields['numero_pedido']['stringValue'] ?? '',
                        'nombre' => $fields['nombre']['stringValue'] ?? '',
                        'correo' => $fields['correo']['stringValue'] ?? '',
                        'telefono' => $fields['telefono']['stringValue'] ?? '',
                        'estado' => $estado,
                        'medicamentos' => $fields['medicamentos']['arrayValue']['values'] ?? []
                    ];
                }
            }
        }

        return view($vista, compact('peticiones'));
    }

    // ==========================
    // CAMBIAR ESTADO
    // ==========================

    public function cambiarEstado($id, $estado)
    {
        // 🔥 IMPORTANTE: updateMask evita borrar los demás campos
        $url = $this->baseUrl . "/" . $id . "?updateMask.fieldPaths=estado";

        // Si se rechaza → devolver stock en Firebase
        if ($estado == "Rechazado") {
            $this->devolverStockFirebase($id);
        }

        $data = [
            'fields' => [
                'estado' => [
                    'stringValue' => $estado
                ]
            ]
        ];

        Http::patch($url, $data);

        return redirect()->route('admin.peticiones')
            ->with('success', 'Estado actualizado correctamente');
    }

    // ==========================
    // DEVOLVER STOCK EN FIREBASE
    // ==========================

    private function devolverStockFirebase($pedidoId)
    {
        $pedidoUrl = $this->baseUrl . "/" . $pedidoId;
        $pedidoResponse = Http::get($pedidoUrl);

        if (!$pedidoResponse->successful()) return;

        $fields = $pedidoResponse->json()['fields'];
        $medicamentos = $fields['medicamentos']['arrayValue']['values'] ?? [];

        foreach ($medicamentos as $med) {

            $nombre = $med['mapValue']['fields']['nombre']['stringValue'];
            $mg = $med['mapValue']['fields']['mg']['stringValue'] ?? '';
            $presentacion = $med['mapValue']['fields']['presentacion']['stringValue'] ?? '';
            $cantidad = (int)($med['mapValue']['fields']['cantidad']['integerValue'] ?? 0);
            $institucion = $med['mapValue']['fields']['institucion']['stringValue'] ?? '';

            // 🔹 Buscar medicamento en Firebase
            $medResponse = Http::get($this->medicamentosUrl);
            if (!$medResponse->successful()) continue;

            $documents = $medResponse->json()['documents'] ?? [];
            $medDoc = null;

            foreach ($documents as $doc) {
                $fieldsDoc = $doc['fields'] ?? [];
                if (
                    ($fieldsDoc['nombre']['stringValue'] ?? '') === $nombre &&
                    ($fieldsDoc['mg']['integerValue'] ?? '') == $mg &&
                    ($fieldsDoc['presentacion']['stringValue'] ?? '') === $presentacion
                ) {
                    $medDoc = $doc;
                    break;
                }
            }

            if (!$medDoc) continue;

            $docName = $medDoc['name'];
            $existenciaUrl = $this->medicamentosUrl . "/" . basename($docName) . "/existencias/" . $institucion;

            // 🔹 Obtener disponibilidad actual
            $existenciaResponse = Http::get($existenciaUrl);
            $disponibilidad = (int)($existenciaResponse['fields']['disponibilidad']['integerValue'] ?? 0);

            // 🔹 Sumar la cantidad devuelta
            $nuevaDisponibilidad = $disponibilidad + $cantidad;

            Http::patch($existenciaUrl, [
                'fields' => [
                    'disponibilidad' => ['integerValue' => (string)$nuevaDisponibilidad]
                ]
            ]);
        }
    }
}