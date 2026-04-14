<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    protected $firebaseBaseUrl;

    public function __construct()
    {
        $this->firebaseBaseUrl = "https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents";
    }

    /**
     * 🔥 Buscar usuario en ambas colecciones (users / administradores)
     */
    private function getUserFromFirebase($uid)
    {
        $collections = ['users', 'administradores'];

        foreach ($collections as $collection) {
            $response = Http::get("{$this->firebaseBaseUrl}/{$collection}/{$uid}");

            if ($response->successful()) {
                return [
                    'data' => $response->json(),
                    'collection' => $collection
                ];
            }
        }

        return null;
    }

    /**
     * Mostrar formulario de edición de perfil
     */
    public function edit()
    {
        $firebaseUser = session('firebase_user');

        if (!$firebaseUser || !isset($firebaseUser['uid'])) {
            return redirect()->route('login')->with('error', 'Usuario no autenticado');
        }

        $uid = $firebaseUser['uid'];

        $result = $this->getUserFromFirebase($uid);

        if (!$result) {
            return back()->with('error', 'Usuario no encontrado en Firebase');
        }

        $data = $result['data'];

        // 🔥 Guardamos en sesión de dónde viene (users o administradores)
        session(['firebase_collection' => $result['collection']]);

        $user = [
            'uid'        => $uid,
            'name'       => $data['fields']['name']['stringValue'] ?? '',
            'email'      => $data['fields']['email']['stringValue'] ?? '',
            'password'   => $data['fields']['password']['stringValue'] ?? '',
            'created_at' => $data['fields']['created_at']['timestampValue'] ?? '',
        ];

        return view('profile.edit', compact('user'));
    }

    /**
     * Actualizar perfil
     */
    public function update(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $firebaseUser = session('firebase_user');

        if (!$firebaseUser || !isset($firebaseUser['uid'])) {
            return redirect()->route('login')->with('error', 'Usuario no autenticado');
        }

        $uid = $firebaseUser['uid'];

        // 🔥 Obtenemos la colección detectada
        $collection = session('firebase_collection');

        if (!$collection) {
            return back()->with('error', 'No se pudo determinar la colección del usuario');
        }

        // Obtener datos actuales
        $response = Http::get("{$this->firebaseBaseUrl}/{$collection}/{$uid}");

        if (!$response->successful()) {
            return back()->with('error', 'No se pudo obtener la información actual');
        }

        $data = $response->json();

        // Datos a actualizar
        $json = [
            'fields' => [
                'name'  => ['stringValue' => $request->name],
                'email' => ['stringValue' => $request->email],
            ]
        ];

        // Mantener otros campos
        foreach ($data['fields'] as $field => $value) {
            if (!isset($json['fields'][$field])) {
                $json['fields'][$field] = $value;
            }
        }

        // PATCH
        $patchResponse = Http::patch("{$this->firebaseBaseUrl}/{$collection}/{$uid}", $json);

        if (!$patchResponse->successful()) {
            return back()->with('error', 'No se pudo actualizar el perfil');
        }

        // Actualizar sesión
        session(['firebase_user' => array_merge($firebaseUser, [
            'name'  => $request->name,
            'email' => $request->email,
        ])]);

        return back()->with('success', 'Perfil actualizado correctamente');
    }

    /**
     * Eliminar perfil
     */
    public function destroy()
    {
        $firebaseUser = session('firebase_user');

        if (!$firebaseUser || !isset($firebaseUser['uid'])) {
            return redirect()->route('login')->with('error', 'Usuario no autenticado');
        }

        $uid = $firebaseUser['uid'];
        $collection = session('firebase_collection');

        if (!$collection) {
            return back()->with('error', 'No se pudo determinar la colección');
        }

        $response = Http::delete("{$this->firebaseBaseUrl}/{$collection}/{$uid}");

        if (!$response->successful()) {
            return back()->with('error', 'No se pudo eliminar el perfil');
        }

        session()->forget('firebase_user');
        session()->forget('firebase_collection');

        return redirect()->route('login')->with('success', 'Perfil eliminado correctamente');
    }
}