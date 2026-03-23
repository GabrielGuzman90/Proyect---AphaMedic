<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CartController extends Controller
{
    private function getCartKey()
    {
        $user = session('firebase_user');
        return 'cart_' . $user['uid'];
    }

    // 🔥 OBTENER MEDICAMENTO DESDE FIREBASE
    private function obtenerMedicamentoFirebase($id)
    {
        $url = "https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents/medicamentos/$id";

        $response = Http::get($url);

        if (!$response->successful()) {
            return null;
        }

        $doc = $response->json();
        $fields = $doc['fields'] ?? [];

        // 🔥 EXISTENCIAS (lugares)
        $existenciasUrl = $url . "/existencias";
        $existenciasResponse = Http::get($existenciasUrl);

        $existencias = $existenciasResponse['documents'] ?? [];

        $totalDisponibilidad = 0;

        foreach ($existencias as $ex) {
            $totalDisponibilidad += (int)($ex['fields']['disponibilidad']['integerValue'] ?? 0);
        }

        return (object)[
            'id' => $id,
            'nombre' => $fields['nombre']['stringValue'] ?? '',
            'presentacion' => $fields['presentacion']['stringValue'] ?? '',
            'mg' => (int)($fields['mg']['integerValue'] ?? 0),
            'image_path' => $fields['image_path']['stringValue'] ?? '',
            'disponibilidad' => $totalDisponibilidad,
            'lugar' => $fields['lugar']['stringValue'] ?? 'No definido'
        ];
    }

    // 🔹 INDEX
    public function index()
    {
        $cartKey = $this->getCartKey();
        $cart = session()->get($cartKey, []);

        return view('carrito.index', compact('cart'));
    }

    // 🔹 AGREGAR
    public function add(Request $request, $id)
    {
        session()->forget('pedido_enviado');

        $med = $this->obtenerMedicamentoFirebase($id);

        if (!$med) {
            return response()->json([
                'ok' => false,
                'message' => 'Medicamento no encontrado'
            ]);
        }

        $cartKey = $this->getCartKey();
        $cart = session()->get($cartKey, []);

        $maxPermitido = min(4, $med->disponibilidad);

        if ($maxPermitido <= 0) {
            return response()->json([
                'ok' => false,
                'message' => 'Sin stock'
            ]);
        }

        // 🔥 AQUÍ RECIBES EL LUGAR DESDE LA VISTA
        $lugar = $request->get('lugar', 'No definido');

        // 🔒 VALIDAR MISMO INSTITUTO
        if (!empty($cart)) {

            $primerItem = reset($cart);
            $lugarCarrito = $primerItem['lugar'] ?? null;

            if ($lugarCarrito && $lugarCarrito !== $lugar) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Este carrito ya pertenece a: ' . $lugarCarrito . 
                    '. Solo puedes agregar medicamentos de ese mismo instituto. 
                    Si deseas otro, vacía el carrito primero.'
                ]);
            }
        }

        if (isset($cart[$id])) {

            if ($cart[$id]['cantidad'] < $maxPermitido) {
                $cart[$id]['cantidad']++;
            }

        } else {

            $cart[$id] = [
                "id" => $med->id,
                "nombre" => $med->nombre,
                "mg" => $med->mg,
                "presentacion" => $med->presentacion,
                "image" => $med->image_path,
                "cantidad" => 1,
                "max" => $maxPermitido,

                // 🔥 GUARDAS EL LUGAR
                "lugar" => $lugar
            ];
        }

        session()->put($cartKey, $cart);

        return response()->json(['ok' => true]);
}

    // 🔹 ACTUALIZAR
    public function update(Request $request, $id)
    {
        $med = $this->obtenerMedicamentoFirebase($id);

        $cartKey = $this->getCartKey();
        $cart = session()->get($cartKey, []);

        if (isset($cart[$id]) && $med) {

            $maxPermitido = min(4, $med->disponibilidad);

            $cantidad = (int) $request->cantidad;

            if ($cantidad < 1) {
                $cantidad = 1;
            }

            if ($cantidad > $maxPermitido) {
                $cantidad = $maxPermitido;
            }

            $cart[$id]['cantidad'] = $cantidad;
            $cart[$id]['max'] = $maxPermitido;

            session()->put($cartKey, $cart);
        }

        return redirect()->route('cart.index');
    }

    // 🔹 ELIMINAR
    public function remove($id)
    {
        $cartKey = $this->getCartKey();
        $cart = session()->get($cartKey, []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put($cartKey, $cart);
        }

        return redirect()->route('cart.index');
    }
}