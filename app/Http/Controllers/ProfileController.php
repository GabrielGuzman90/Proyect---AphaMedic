<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    protected $firebaseBaseUrl;

    public function __construct()
    {
        // URL base hasta "projects/soa-2026-e277f/databases/(default)/documents"
        $this->firebaseBaseUrl = "https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents";
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

        // Llamada HTTP GET para obtener los datos del usuario
        $response = Http::get("{$this->firebaseBaseUrl}/users/{$uid}");

        if (!$response->successful()) {
            return redirect()->back()->with('error', 'No se pudo obtener la información del usuario');
        }

        $data = $response->json();

        // Convertimos el formato Firestore a array simple
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

        // Traer los datos actuales completos desde Firebase
        $response = Http::get("{$this->firebaseBaseUrl}/users/{$uid}");
        if (!$response->successful()) {
            return back()->with('error', 'No se pudo obtener la información actual del usuario');
        }

        $data = $response->json();

        // Solo modificamos name y email, los demás campos existentes se mantienen
        $json = [
            'fields' => [
                'name'  => ['stringValue' => $request->name],
                'email' => ['stringValue' => $request->email],
            ]
        ];

        // Conservar los demás campos existentes automáticamente
        foreach ($data['fields'] as $field => $value) {
            if (!isset($json['fields'][$field])) {
                $json['fields'][$field] = $value;
            }
        }

        // Llamada HTTP PATCH para actualizar
        $patchResponse = Http::patch("{$this->firebaseBaseUrl}/users/{$uid}", $json);

        if (!$patchResponse->successful()) {
            return back()->with('error', 'No se pudo actualizar el perfil');
        }

        // Actualizar sesión con los datos nuevos
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

        // Llamada HTTP DELETE para eliminar usuario
        $response = Http::delete("{$this->firebaseBaseUrl}/users/{$uid}");

        if (!$response->successful()) {
            return back()->with('error', 'No se pudo eliminar el perfil');
        }

        session()->forget('firebase_user');

        return redirect()->route('login')->with('success', 'Perfil eliminado correctamente');
    }
}