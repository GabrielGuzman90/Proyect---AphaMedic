<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PeticionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Obtener clave dinámica del carrito (MISMA que CartController)
    |--------------------------------------------------------------------------
    */
    private function getCartKey()
    {
        $user = session('firebase_user');

        if (!$user) {
            return null;
        }

        return 'cart_' . $user['uid'];
    }

    public function store(Request $request)
    {
        // 🔐 Validación
        $request->validate([
            'nombre' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
            'correo' => 'required|email|max:100',
        ]);

        $cartKey = $this->getCartKey();

        if (!$cartKey) {
            return redirect()->route('login');
        }

        // 🚫 Bloquear doble envío
        if (session()->has('pedido_enviado')) {
            return redirect()->route('cart.index')
                ->with('error', 'Este pedido ya fue enviado.');
        }

        // 🔥 Obtener carrito
        $cart = session()->get($cartKey, []);

        if (empty($cart)) {
            return redirect()->back()
                ->with('error', 'El carrito está vacío.');
        }

        $baseUrl = "https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents/medicamentos";

        try {
            $medicamentosFirestore = [];

            foreach ($cart as $item) {

                // 🔹 Buscar medicamento exacto en Firebase
                $medResponse = Http::get($baseUrl);
                $documents = $medResponse['documents'] ?? [];
                $medDoc = null;

                foreach ($documents as $doc) {
                    $fields = $doc['fields'] ?? [];
                    if (
                        ($fields['nombre']['stringValue'] ?? '') === $item['nombre'] &&
                        ($fields['mg']['integerValue'] ?? '') == $item['mg'] &&
                        ($fields['presentacion']['stringValue'] ?? '') === $item['presentacion']
                    ) {
                        $medDoc = $doc;
                        break;
                    }
                }

                if (!$medDoc) {
                    throw new \Exception("Medicamento {$item['nombre']} no encontrado en Firebase");
                }

                $docName = $medDoc['name'];
                $lugar = $item['lugar'] ?? 'No definido';

                // 🔹 URL exacta de la existencia en ese lugar
                $existenciaUrl = "https://firestore.googleapis.com/v1/{$docName}/existencias/{$lugar}";

                // 🔹 Obtener disponibilidad actual en Firebase
                $existenciaResponse = Http::get($existenciaUrl);
                $disponibilidad = 0;

                if ($existenciaResponse->successful()) {
                    $disponibilidad = (int)($existenciaResponse['fields']['disponibilidad']['integerValue'] ?? 0);
                }

                // 🚨 Validar stock
                if ($item['cantidad'] > $disponibilidad) {
                    throw new \Exception("Stock insuficiente para {$item['nombre']} en {$lugar}");
                }

                // 🔻 Restar stock en Firebase
                $newDisponibilidad = $disponibilidad - $item['cantidad'];
                Http::patch($existenciaUrl, [
                    'fields' => [
                        'disponibilidad' => ['integerValue' => (string)$newDisponibilidad]
                    ]
                ]);

                // 🔹 Preparar datos para la petición
                $medicamentosFirestore[] = [
                    'mapValue' => [
                        'fields' => [
                            'nombre' => ['stringValue' => $item['nombre']],
                            'mg' => ['stringValue' => (string)$item['mg']],
                            'presentacion' => ['stringValue' => $item['presentacion']],
                            'cantidad' => ['integerValue' => (int)$item['cantidad']],
                            'institucion' => ['stringValue' => $lugar],
                        ]
                    ]
                ];
            }

            // 🔹 Crear número de pedido
            $numeroPedido = 'PED-' . now()->format('YmdHis') . '-' . rand(100,999);

            // 🔹 Preparar datos para Firebase Peticiones
            $data = [
                'fields' => [
                    'numero_pedido' => ['stringValue' => $numeroPedido],
                    'nombre' => ['stringValue' => $request->nombre],
                    'telefono' => ['stringValue' => $request->telefono],
                    'correo' => ['stringValue' => $request->correo],
                    'fecha' => ['timestampValue' => now()->toIso8601String()],
                    'estado' => ['stringValue' => 'Pendiente'],
                    'medicamentos' => [
                        'arrayValue' => [
                            'values' => $medicamentosFirestore
                        ]
                    ]
                ]
            ];

            // 🔹 Enviar pedido a Firebase
            $response = Http::post(
                'https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents/peticiones',
                $data
            );

            if (!$response->successful()) {
                throw new \Exception('Error enviando pedido a Firebase');
            }

            // 🔒 Bloquear reenvío
            session()->put('pedido_enviado', true);

            // 🧹 Vaciar carrito
            session()->forget($cartKey);

            return redirect()->route('cart.index')
                ->with('success', "Pedido enviado correctamente. Nº $numeroPedido");

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}