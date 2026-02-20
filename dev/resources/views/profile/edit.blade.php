<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Mostrar formulario de edición de perfil
     */
    public function edit()
    {
        // obtener UID desde la sesión
        $uid = session('firebase_user_uid');

        if (!$uid) {
            return redirect()->route('login')->with('error', 'Usuario no autenticado');
        }

        // conectar con Firestore
        $firestore = app('firebase.firestore')->database();

        // obtener usuario desde Firebase
        $snapshot = $firestore
            ->collection('users')
            ->document($uid)
            ->snapshot();

        if (!$snapshot->exists()) {
            return redirect()->back()->with('error', 'Usuario no encontrado en Firebase');
        }

        // convertir a objeto para usar -> en Blade
        $user = (object) $snapshot->data();

        // agregar uid también
        $user->uid = $uid;

        return view('profile.edit', compact('user'));
    }

    /**
     * Actualizar perfil
     */
    public function update(Request $request)
    {
        // validar datos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $uid = session('firebase_user_uid');

        if (!$uid) {
            return redirect()->route('login')->with('error', 'Usuario no autenticado');
        }

        // conectar con Firestore
        $firestore = app('firebase.firestore')->database();

        // actualizar datos en Firebase
        $firestore->collection('users')
            ->document($uid)
            ->set([
                'name' => $request->name,
                'email' => $request->email,
            ], ['merge' => true]);

        // actualizar sesión
        session([
            'firebase_user' => [
                'uid' => $uid,
                'name' => $request->name,
                'email' => $request->email
            ]
        ]);

        return back()->with('success', 'Perfil actualizado correctamente');
    }
}