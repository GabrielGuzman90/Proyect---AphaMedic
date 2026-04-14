<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class PedidoController extends Controller
{
    private function getUserEmail()
    {
        $user = session('firebase_user');

        return $user['email'] ?? null;
    }

    public function index()
    {
        $email = $this->getUserEmail();

        if (!$email) {
            return redirect()->route('login');
        }

        $url = "https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents/peticiones";

        $response = Http::get($url);

        $pedidos = [];

        if ($response->successful()) {

            $documents = $response['documents'] ?? [];

            foreach ($documents as $doc) {

                $fields = $doc['fields'];

                // 🔥 FILTRAR POR USUARIO
                if (($fields['correo']['stringValue'] ?? '') !== $email) {
                    continue;
                }

                $meds = [];

                $items = $fields['medicamentos']['arrayValue']['values'] ?? [];

                foreach ($items as $med) {
                    $f = $med['mapValue']['fields'];

                    $meds[] = [
                        'nombre' => $f['nombre']['stringValue'] ?? '',
                        'mg' => $f['mg']['stringValue'] ?? '',
                        'presentacion' => $f['presentacion']['stringValue'] ?? '',
                        'cantidad' => $f['cantidad']['integerValue'] ?? 0,
                        'institucion' => $f['institucion']['stringValue'] ?? '',
                    ];
                }

                $pedidos[] = [
                    'numero' => $fields['numero_pedido']['stringValue'] ?? '',
                    'estado' => $fields['estado']['stringValue'] ?? '',
                    'fecha' => $fields['fecha']['timestampValue'] ?? '',
                    'medicamentos' => $meds
                ];
            }
        }

        return view('pedidos.index', compact('pedidos'));
    }
}