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
                'id'        => basename($doc['name']), // 🔥 IMPORTANTE (ID FIREBASE)
                'nombre'    => $f['nombre']['stringValue']    ?? '',
                'correo'    => $f['correo']['stringValue']    ?? '',
                'prioridad' => $f['prioridad']['stringValue'] ?? '',
                'asunto'    => $f['asunto']['stringValue']    ?? '',
                'mensaje'   => $f['mensaje']['stringValue']   ?? '',
                'status'    => $f['status']['stringValue']    ?? 'pendiente', // 🔥 NUEVO
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

        $body = [
            "fields" => [
                "nombre"    => ["stringValue" => $validated['nombre']],
                "correo"    => ["stringValue" => $validated['correo']],
                "prioridad" => ["stringValue" => $validated['prioridad']],
                "asunto"    => ["stringValue" => $validated['asunto']],
                "mensaje"   => ["stringValue" => $validated['mensaje']],
                "fecha"     => ["timestampValue" => now()->toISOString()],
                "status"    => ["stringValue" => "pendiente"], // 🔥 NUEVO
            ]
        ];

        $response = Http::post($this->firestoreUrl, $body);

        if (!$response->ok()) {
            return back()->withErrors(['firebase' => 'Error al guardar en Firebase']);
        }

        return redirect()->back()->with('ok', 'Mensaje enviado correctamente');
    }

    // 🔥 NUEVO: ELIMINAR
    public function eliminar($id)
    {
        Http::delete($this->firestoreUrl . '/' . $id);

        return back()->with('success', 'Mensaje eliminado');
    }

    // 🔥 NUEVO: ACTUALIZAR STATUS
    public function cambiarStatus($id, $status)
    {
        $url = $this->firestoreUrl . "/$id?updateMask.fieldPaths=status";

        Http::patch($url, [
            "fields" => [
                "status" => [
                    "stringValue" => $status
                ]
            ]
        ]);

        return back()->with('success', 'Estado actualizado');
    }
}