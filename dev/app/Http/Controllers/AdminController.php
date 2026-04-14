<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{
    private $usersUrl = "https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents/users";
    private $adminsUrl = "https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents/administradores";

    /**
     * 🔥 Dashboard con buscador
     */
    public function index(Request $request)
    {
        $search = strtolower($request->get('search'));

        // 🔥 Obtener USERS
        $usersResponse = Http::get($this->usersUrl);
        $usersDocs = $usersResponse->json()['documents'] ?? [];

        $users = [];
        foreach ($usersDocs as $doc) {
            $fields = $doc['fields'];

            $user = (object)[
                'id' => basename($doc['name']),
                'name' => $fields['name']['stringValue'] ?? '',
                'email' => $fields['email']['stringValue'] ?? '',
                'type' => 'user'
            ];

            // 🔎 FILTRO
            if (!$search ||
                str_contains(strtolower($user->id), $search) ||
                str_contains(strtolower($user->name), $search) ||
                str_contains(strtolower($user->email), $search)
            ) {
                $users[] = $user;
            }
        }

        // 🔥 Obtener ADMINS
        $adminsResponse = Http::get($this->adminsUrl);
        $adminsDocs = $adminsResponse->json()['documents'] ?? [];

        $admins = [];
        foreach ($adminsDocs as $doc) {
            $fields = $doc['fields'];

            $admin = (object)[
                'id' => basename($doc['name']),
                'name' => $fields['name']['stringValue'] ?? '',
                'email' => $fields['email']['stringValue'] ?? '',
                'type' => 'admin'
            ];

            // 🔎 FILTRO
            if (!$search ||
                str_contains(strtolower($admin->id), $search) ||
                str_contains(strtolower($admin->name), $search) ||
                str_contains(strtolower($admin->email), $search)
            ) {
                $admins[] = $admin;
            }
        }

        return view('dashboard.index', compact('users', 'admins', 'search'));
    }

    /**
     * 🔥 Detectar colección automáticamente
     */
    private function findUserCollection($id)
    {
        $urls = [
            'users' => $this->usersUrl,
            'administradores' => $this->adminsUrl
        ];

        foreach ($urls as $key => $url) {
            $response = Http::get($url . '/' . $id);

            if ($response->successful()) {
                return $url;
            }
        }

        return null;
    }

    /**
     * ✏️ Actualizar
     */
    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email'
        ]);

        $url = $this->findUserCollection($id);

        if (!$url) {
            return back()->with('error', 'Usuario no encontrado');
        }

        Http::patch($url . '/' . $id, [
            "fields" => [
                "name" => ["stringValue" => $request->name],
                "email" => ["stringValue" => $request->email]
            ]
        ]);

        return back()->with('success', 'Usuario actualizado correctamente');
    }

    /**
     * 🗑️ Eliminar
     */
    public function deleteUser($id)
    {
        $url = $this->findUserCollection($id);

        if (!$url) {
            return back()->with('error', 'Usuario no encontrado');
        }

        Http::delete($url . '/' . $id);

        return back()->with('success', 'Usuario eliminado correctamente');
    }
}
