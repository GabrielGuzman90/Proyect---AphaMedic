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
    // CAMBIAR ESTADO + NOTIFICACIÓN 🔔
    // ==========================

    public function cambiarEstado($id, $estado)
    {
        $url = $this->baseUrl . "/" . $id . "?updateMask.fieldPaths=estado";

        // 🔥 Obtener pedido para sacar correo
        $pedidoResponse = Http::get($this->baseUrl . "/" . $id);

        $correoUsuario = null;

        if ($pedidoResponse->successful()) {
            $fields = $pedidoResponse->json()['fields'] ?? [];
            $correoUsuario = $fields['correo']['stringValue'] ?? null;
        }

        // 🔁 Si se rechaza → devolver stock
        if ($estado == "Rechazado") {
            $this->devolverStockFirebase($id);
        }

        // 🔥 Actualizar estado
        Http::patch($url, [
            'fields' => [
                'estado' => [
                    'stringValue' => $estado
                ]
            ]
        ]);

        // 🔔 CREAR NOTIFICACIÓN
        if ($correoUsuario) {

            Http::post(
                'https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents/notificaciones',
                [
                    'fields' => [
                        'correo' => ['stringValue' => $correoUsuario],
                        'mensaje' => ['stringValue' => "Tu petición fue $estado"],
                        'fecha' => ['timestampValue' => now()->toIso8601String()],
                        'leido' => ['booleanValue' => false],
                    ]
                ]
            );
        }

        return redirect()->route('admin.peticiones')
            ->with('success', 'Estado actualizado correctamente');
    }

    // ==========================
    // DEVOLVER STOCK
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

            $existenciaResponse = Http::get($existenciaUrl);
            $disponibilidad = (int)($existenciaResponse['fields']['disponibilidad']['integerValue'] ?? 0);

            $nuevaDisponibilidad = $disponibilidad + $cantidad;

            Http::patch($existenciaUrl, [
                'fields' => [
                    'disponibilidad' => ['integerValue' => (string)$nuevaDisponibilidad]
                ]
            ]);
        }
    }
}