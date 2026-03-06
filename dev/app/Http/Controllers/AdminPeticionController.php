<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class AdminPeticionController extends Controller
{
    private $baseUrl = "https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents/peticiones";

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
        $url = $this->baseUrl . "/" . $id;

        // Si se rechaza → devolver stock
        if ($estado == "Rechazado") {
            $this->devolverStock($id);
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
    // DEVOLVER STOCK MYSQL
    // ==========================

    private function devolverStock($id)
    {
        $url = $this->baseUrl . "/" . $id;

        $response = Http::get($url);

        if (!$response->successful()) return;

        $fields = $response->json()['fields'];
        $medicamentos = $fields['medicamentos']['arrayValue']['values'] ?? [];

        foreach ($medicamentos as $med) {

            $nombre = $med['mapValue']['fields']['nombre']['stringValue'];
            $cantidad = $med['mapValue']['fields']['cantidad']['integerValue'];

            DB::table('medicamentos')
                ->where('nombre', $nombre)
                ->increment('disponibilidad', $cantidad);
        }
    }
}