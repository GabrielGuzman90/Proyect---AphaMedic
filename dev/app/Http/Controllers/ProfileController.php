<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = session('firebase_user');

        if (!$user) {
            return redirect()->route('login');
        }

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email'
        ]);

        $user = session('firebase_user');

        if (!$user) {
            return redirect()->route('login');
        }

        User::updateUser($user['id'], [
            'name' => $request->name,
            'email' => $request->email
        ]);

        session([
            'firebase_user' => [
                'id' => $user['id'],
                'name' => $request->name,
                'email' => $request->email
            ]
        ]);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}