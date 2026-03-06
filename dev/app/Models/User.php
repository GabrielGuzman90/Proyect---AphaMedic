<?php

namespace App\Models;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

class User
{
    private static $baseUrl = "https://firestore.googleapis.com/v1/projects/soa-2026-e277f/databases/(default)/documents/users";

    public static function create($data){
        $response = Http::post(self::$baseUrl, [
            "fields" => [
                "name" => ["stringValue" => $data['name']],
                "email" => ["stringValue" => $data['email']],
                "password" => ["stringValue" => $data['password']],
                "role" => ["stringValue" => $data['role'] ?? 'estandar'], // 👈 NUEVO
                "created_at" => ["timestampValue" => now()->toIso8601String()],
                "updated_at" => ["timestampValue" => now()->toIso8601String()]
            ]
        ]);

        return $response->json();
    }

    public static function findByEmail($email)
    {
        $response = Http::get(self::$baseUrl);

        if (!$response->successful()) {
            return null;
        }

        $documents = $response->json()['documents'] ?? [];

        foreach ($documents as $doc) {

            $docEmail = $doc['fields']['email']['stringValue'] ?? null;

            if ($docEmail === $email) {

                return [
                    'id' => basename($doc['name']),
                    'name' => $doc['fields']['name']['stringValue'],
                    'email' => $docEmail,
                    'password' => $doc['fields']['password']['stringValue'],
                    'role' => $doc['fields']['role']['stringValue'] ?? 'estandar' // 👈 NUEVO
                ];
            }
        }

        return null;
    }

    public static function updateUser($id, $data)
    {
        $url = self::$baseUrl . "/" . $id;

        return Http::patch($url, [
            "fields" => [
                "name" => ["stringValue" => $data['name']],
                "email" => ["stringValue" => $data['email']],
                "updated_at" => ["timestampValue" => now()->toIso8601String()]
            ]
        ]);
    }
}