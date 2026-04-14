<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Mostrar el formulario de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesar login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // URLs de Firebase para usuarios y administradores
        $urls = [
            'https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents/users',
            'https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents/administradores'
        ];

        foreach ($urls as $url) {
            $response = Http::get($url);
            if (!$response->successful()) continue;

            $docs = $response->json()['documents'] ?? [];

            foreach ($docs as $doc) {
                $fields = $doc['fields'];
                $email = $fields['email']['stringValue'] ?? '';
                $passwordHash = $fields['password']['stringValue'] ?? '';

                if ($email !== $request->email) continue;

                // Verifica contraseña (Hash o plain)
                $passOk = Hash::check($request->password, $passwordHash) || $request->password === $passwordHash;
                if (!$passOk) {
                    return back()->withErrors(['email' => 'Contraseña incorrecta']);
                }

                $uid = basename($doc['name']);

                // Asignamos admin si viene de la colección administradores
                $role = strpos($url, 'administradores') !== false ? 'admin' : 'estandar';

                // Guardamos sesión
                session([
                    'firebase_user' => [
                        'uid' => $uid,
                        'name' => $fields['name']['stringValue'] ?? '',
                        'email' => $email,
                        'role' => $role
                    ]
                ]);

                $request->session()->regenerate();

                return redirect('/home');
            }
        }

        return back()->withErrors(['email' => 'Usuario no encontrado']);
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        session()->forget('firebase_user');
        return redirect('/login');
    }
}