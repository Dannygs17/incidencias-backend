<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // Importante para manejar archivos

class AuthController extends Controller
{
    // --- REGISTRO (Ionic envía datos y fotos) ---
    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
            'curp' => 'required|string|unique:users',
            'ine_frente' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Validación de imagen
            'ine_reverso' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Guardamos fotos en la carpeta 'ine_images' dentro de public
        $pathFrente = $request->file('ine_frente')->store('ine_images', 'public');
        $pathReverso = $request->file('ine_reverso')->store('ine_images', 'public');

        // Creamos usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'curp' => $request->curp,
            'ine_frente' => $pathFrente,
            'ine_reverso' => $pathReverso,
            'role' => 'ciudadano', 
            'status' => 'pending' 
        ]);

        return response()->json([
            'message' => 'Registro exitoso. Tu cuenta está en revisión.',
            'status' => 'pending'
        ], 201);
    }

    // --- LOGIN (El filtro de seguridad) ---
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        // 1. Validar existencia y contraseña
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        // 2. Filtro de Estatus: Pendiente (Error 403)
        if ($user->status === 'pending') {
            return response()->json([
                'message' => 'Tu cuenta aún está en revisión por un administrador.'
            ], 403); 
        }

        // 3. Filtro de Estatus: Rechazado (Error 403)
        if ($user->status === 'rejected') {
            return response()->json([
                'message' => 'Tu solicitud de registro fue rechazada. Contacta a soporte.'
            ], 403);
        }

        // 4. Éxito: Generar Token (Sanctum)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Bienvenido',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status
            ]
        ]);
    }

   // --- LOGOUT ---
public function logout(Request $request) {
    // Al llamar a este método, Sanctum identifica el token usado
    // y lo elimina de la base de datos permanentemente.
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'message' => 'Sesión cerrada y token destruido'
    ]);
}

}
