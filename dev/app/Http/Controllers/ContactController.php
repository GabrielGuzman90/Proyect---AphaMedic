<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ContactController extends Controller
{
    private $firestoreUrl = "https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents/contacts";

    public function index()
    {
        $response = Http::get($this->firestoreUrl);

        if (!$response->ok()) {
            return view('indexcontact', ['mensajes' => []]);
        }

        $docs = $response->json()['documents'] ?? [];
        $mensajes = [];

        foreach ($docs as $doc) {
            $f = $doc['fields'] ?? [];

            $mensajes[] = (object)[
                'nombre'    => $f['nombre']['stringValue']    ?? '',
                'correo'    => $f['correo']['stringValue']    ?? '',
                'prioridad' => $f['prioridad']['stringValue'] ?? '',
                'asunto'    => $f['asunto']['stringValue']    ?? '',
                'mensaje'   => $f['mensaje']['stringValue']   ?? '',
            ];
        }

        return view('indexcontact', compact('mensajes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'    => 'required|min:10',
            'correo'    => 'required|email|min:10',
            'prioridad' => 'required',
            'asunto'    => 'required|min:10|max:80',
            'mensaje'   => 'required|min:10|max:3000'
        ]);

        // Formato requerido por Firestore REST
        $body = [
            "fields" => [
                "nombre"    => ["stringValue" => $validated['nombre']],
                "correo"    => ["stringValue" => $validated['correo']],
                "prioridad" => ["stringValue" => $validated['prioridad']],
                "asunto"    => ["stringValue" => $validated['asunto']],
                "mensaje"   => ["stringValue" => $validated['mensaje']],
                "fecha"     => ["timestampValue" => now()->toISOString()],
            ]
        ];

        $response = Http::post($this->firestoreUrl, $body);

        if (!$response->ok()) {
            return back()->withErrors(['firebase' => 'Error al guardar en Firebase']);
        }

        return redirect()->back()->with('ok', 'Mensaje enviado correctamente');
    }
}

