<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RegisterController extends Controller
{

    public function showRegistrationForm()
    {
        return view('auth.register');
    }


    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed'
        ]);


        // Guardar en Firebase Firestore
        $response = Http::post(
            'https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents/users',
            [
                'fields' => [
                    'name' => [
                        'stringValue' => $request->name
                    ],
                    'email' => [
                        'stringValue' => $request->email
                    ],
                    'password' => [
                        'stringValue' => bcrypt($request->password)
                    ],
                    'created_at' => [
                        'timestampValue' => now()->toISOString()
                    ]
                ]
            ]
        );


        if ($response->successful()) {

            // obtener ID del usuario creado
            $data = $response->json();

            $id = basename($data['name']);

            session([
                'firebase_user' => [
                    'id' => $id,
                    'name' => $request->name,
                    'email' => $request->email
                ]
            ]);

            return redirect('/');

        } else {

            return back()->withErrors(['error' => 'Error al registrar usuario']);

        }

    }

}
