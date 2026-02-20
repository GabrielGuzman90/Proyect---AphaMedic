<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{

    private $firestoreUrl = "https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents/users";


    // Mostrar Dashboard
    public function index()
    {

        $response = Http::get($this->firestoreUrl);

        $documents = $response->json()['documents'] ?? [];

        $users = [];

        foreach ($documents as $doc)
        {
            $fields = $doc['fields'];

            $users[] = (object)[
                'id' => basename($doc['name']),
                'name' => $fields['name']['stringValue'] ?? '',
                'email' => $fields['email']['stringValue'] ?? ''
            ];
        }

        return view('dashboard.index', compact('users'));

    }


    // Mostrar usuario en modal (opcional)
    public function editUser($id)
    {

        $response = Http::get($this->firestoreUrl.'/'.$id);

        $fields = $response->json()['fields'];

        $user = (object)[
            'id' => $id,
            'name' => $fields['name']['stringValue'] ?? '',
            'email' => $fields['email']['stringValue'] ?? ''
        ];

        return view('dashboard.edit', compact('user'));

    }


    // Actualizar usuario
    public function updateUser(Request $request, $id)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email'
        ]);


        $body = [
            "fields" => [
                "name" => ["stringValue" => $request->name],
                "email" => ["stringValue" => $request->email]
            ]
        ];


        Http::patch($this->firestoreUrl.'/'.$id, $body);


        return redirect()->route('dashboard')
        ->with('success', 'Usuario actualizado correctamente.');

    }


    // Eliminar usuario
    public function deleteUser($id)
    {

        Http::delete($this->firestoreUrl.'/'.$id);

        return redirect()->route('dashboard')
        ->with('success', 'Usuario eliminado.');

    }

}
