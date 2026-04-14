<?php

namespace App\Models;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

class User
{
    // URLs separadas según tipo de usuario
    private static $baseUrlUsers = "https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents/users";
    private static $baseUrlAdmins = "https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents/administradores";

    /**
     * Crear un usuario (admin o estandar)
     */
    public static function create($data){
        // Determinar colección según rol
        $roleKey = ($data['role'] ?? 'estandar') === 'admin' ? 'administradores' : 'users';

        $response = Http::post(self::$urls[$roleKey], [
            "fields" => [
                "name" => ["stringValue" => $data['name']],
                "email" => ["stringValue" => $data['email']],
                "password" => ["stringValue" => $data['password']],
                "role" => ["stringValue" => $data['role'] ?? 'estandar'],
                "created_at" => ["timestampValue" => now()->toIso8601String()],
                "updated_at" => ["timestampValue" => now()->toIso8601String()]
            ]
        ]);

        return $response->json();
    }

    /**
     * Buscar usuario por email (revisa ambas colecciones)
     */
    public static function findByEmail($email)
    {
        foreach (self::$urls as $roleKey => $url) {
            $response = Http::get($url);

            if (!$response->successful()) {
                continue; // intenta la otra colección
            }

            $documents = $response->json()['documents'] ?? [];

            foreach ($documents as $doc) {
                $docEmail = $doc['fields']['email']['stringValue'] ?? null;

                if ($docEmail === $email) {
                    return [
                        'id' => basename($doc['name']),
                        'name' => $doc['fields']['name']['stringValue'] ?? '',
                        'email' => $docEmail,
                        'password' => $doc['fields']['password']['stringValue'] ?? '',
                        'role' => $roleKey === 'administradores' ? 'admin' : 'estandar'
                    ];
                }
            }
        }

        return null;
    }

    /**
     * Actualizar usuario (solo users, si quieres admin también agregar lógica)
     */
    public static function updateUser($id, $data, $role = 'estandar')
    {
        $roleKey = $role === 'admin' ? 'administradores' : 'users';
        $url = self::$urls[$roleKey] . "/" . $id;

        return Http::patch($url, [
            "fields" => [
                "name" => ["stringValue" => $data['name']],
                "email" => ["stringValue" => $data['email']],
                "updated_at" => ["timestampValue" => now()->toIso8601String()]
            ]
        ]);
    }
}