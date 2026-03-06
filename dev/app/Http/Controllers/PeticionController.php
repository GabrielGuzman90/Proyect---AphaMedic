<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Medicamento;

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
        // 🔐 Validación fuerte
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

        // 🔥 AHORA SÍ LEEMOS LA CLAVE CORRECTA
        $cart = session()->get($cartKey, []);

        if (empty($cart)) {
            return redirect()->back()
                ->with('error', 'El carrito está vacío.');
        }

        DB::beginTransaction();

        try {

            $medicamentosFirestore = [];

            foreach ($cart as $item) {

                $med = Medicamento::findOrFail($item['id']);

                // 🚨 Validar stock
                if ($item['cantidad'] > $med->disponibilidad) {
                    throw new \Exception("Stock insuficiente para {$med->nombre}");
                }

                // 🔻 Descontar stock
                $med->disponibilidad -= $item['cantidad'];
                $med->save();

                $medicamentosFirestore[] = [
                    'mapValue' => [
                        'fields' => [
                            'nombre' => ['stringValue' => $item['nombre']],
                            'mg' => ['stringValue' => (string)$item['mg']],
                            'presentacion' => ['stringValue' => $item['presentacion']],
                            'cantidad' => ['integerValue' => (int)$item['cantidad']],
                        ]
                    ]
                ];
            }

            $numeroPedido = 'PED-' . now()->format('YmdHis') . '-' . rand(100,999);

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

            $response = Http::post(
                'https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents/peticiones',
                $data
            );

            if (!$response->successful()) {
                throw new \Exception('Error enviando a Firebase');
            }

            DB::commit();

            // 🔒 Bloquear reenvío
            session()->put('pedido_enviado', true);

            // 🧹 Vaciar carrito correctamente
            session()->forget($cartKey);

            return redirect()->route('cart.index')
                ->with('success', "Pedido enviado correctamente. Nº $numeroPedido");

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}