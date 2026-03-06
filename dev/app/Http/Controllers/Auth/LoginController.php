<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    public function showLoginForm()
    {
        return view('auth.login');
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);


        // Obtener usuarios desde Firebase
        $response = Http::get(
            'https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents/users'
        );


        if (!$response->successful()) {
            return back()->withErrors([
                'error' => 'Error al conectar con Firebase'
            ]);
        }


        $users = $response->json()['documents'] ?? [];


        foreach ($users as $doc) {

            $fields = $doc['fields'];

            $email = $fields['email']['stringValue'] ?? '';
            $passwordHash = $fields['password']['stringValue'] ?? '';
            $role = $fields['role']['stringValue'] ?? 'estandar';


            if ($email === $request->email) {

                if (Hash::check($request->password, $passwordHash)) {

                    $uid = basename($doc['name']);

                    // Guardar sesión con el ROLE incluido
                    session([
                        'firebase_user' => [
                            'uid' => $uid,
                            'name' => $fields['name']['stringValue'] ?? '',
                            'email' => $email,
                            'role' => $role
                        ]
                    ]);

                    // IMPORTANTE: siempre redirige al inicio normal
                    return redirect('/');

                } else {

                    return back()->withErrors([
                        'email' => 'Contraseña incorrecta'
                    ]);

                }

            }

        }


        return back()->withErrors([
            'email' => 'Usuario no encontrado'
        ]);
    }


    public function logout(Request $request)
    {
        session()->forget('firebase_user');

        return redirect('/login');
    }

}