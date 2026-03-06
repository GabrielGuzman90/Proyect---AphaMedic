<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicamento;

class CartController extends Controller
{
    private function getCartKey()
    {
        $user = session('firebase_user');
        return 'cart_' . $user['uid'];
    }

    public function index()
    {
        $cartKey = $this->getCartKey();
        $cart = session()->get($cartKey, []);

        return view('carrito.index', compact('cart'));
    }

    public function add($id)
    {
        session()->forget('pedido_enviado');

        $med = Medicamento::findOrFail($id);

        $cartKey = $this->getCartKey();
        $cart = session()->get($cartKey, []);

        $maxPermitido = min(4, $med->disponibilidad);

        if ($maxPermitido <= 0) {
            return response()->json([
                'ok' => false,
                'message' => 'Sin stock'
            ]);
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
                "max" => $maxPermitido
            ];
        }

        session()->put($cartKey, $cart);

        return response()->json(['ok' => true]);
    }

    public function update(Request $request, $id)
    {
        $med = Medicamento::findOrFail($id);

        $cartKey = $this->getCartKey();
        $cart = session()->get($cartKey, []);

        if (isset($cart[$id])) {

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