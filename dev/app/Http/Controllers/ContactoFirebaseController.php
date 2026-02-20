<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ContactoFirebaseController extends Controller
{
    private $firestoreUrl = "https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents/contacts";
   
    public function guardar(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'correo' => 'required|email',
            'prioridad' => 'required',
            'asunto' => 'required',
            'mensaje' => 'required'
        ]);

        $body = [
            "fields" => [
                "nombre" => ["stringValue" => $request->nombre],
                "correo" => ["stringValue" => $request->correo],
                "prioridad" => ["stringValue" => $request->prioridad],
                "asunto" => ["stringValue" => $request->asunto],
                "mensaje" => ["stringValue" => $request->mensaje],
                "fecha" => ["timestampValue" => now()->toISOString()]
            ]
        ];

        Http::post($this->firestoreUrl, $body);

        return redirect()->back()->with('ok', 'Mensaje enviado correctamente');
    }

    public function listar()
    {
        $response = Http::get($this->firestoreUrl);

        $docs = $response->json()['documents'] ?? [];

        $mensajes = [];

        foreach ($docs as $doc) {
            $f = $doc['fields'];

            $mensajes[] = (object)[
                'nombre' => $f['nombre']['stringValue'] ?? '',
                'correo' => $f['correo']['stringValue'] ?? '',
                'prioridad' => $f['prioridad']['stringValue'] ?? '',
                'asunto' => $f['asunto']['stringValue'] ?? '',
                'mensaje' => $f['mensaje']['stringValue'] ?? ''
            ];
        }

        return view('leer-contactos', compact('mensajes'));
    }
}
