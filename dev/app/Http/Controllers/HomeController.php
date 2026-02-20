<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{

    private $firestoreUrl = "https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents/users";


    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        return view('home');
    }


    public function users()
    {

        $response = Http::get($this->firestoreUrl);

        $documents = $response->json()['documents'] ?? [];

        $usuarios = [];

        foreach ($documents as $doc)
        {

            $fields = $doc['fields'];

            $usuarios[] = (object)[
                'id' => basename($doc['name']),
                'name' => $fields['name']['stringValue'] ?? '',
                'email' => $fields['email']['stringValue'] ?? ''
            ];

        }


        return view('users', compact('usuarios'));

    }

}
