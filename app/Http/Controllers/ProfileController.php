<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Obtener los datos del usuario logueado.
     */
    public function show(Request $request)
    {
        // Esto devuelve ID, nombre, email, curp, etc.
        return response()->json($request->user());
    }

    /**
     * Actualizar datos básicos (Nombre).
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Perfil actualizado',
            'user' => $user
        ]);
    }

    /**
     * Cambiar la contraseña desde la App.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Contraseña actualizada correctamente'
        ]);
    }
}