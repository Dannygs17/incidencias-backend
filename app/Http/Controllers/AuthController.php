<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // --- REGISTRO MANUAL (Usuarios que no usan Google) ---
    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'curp' => 'required|string|unique:users',
            'ine_frente' => 'required|image|mimes:jpg,jpeg,png|max:2048', 
            'ine_reverso' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Guardado físico en Storage
        $pathFrente = $request->file('ine_frente')->store('ine_images', 'public');
        $pathReverso = $request->file('ine_reverso')->store('ine_images', 'public');

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

        return response()->json(['message' => 'Registro exitoso.', 'status' => 'pending'], 201);
    }

    // --- LOGIN NORMAL ---
    public function login(Request $request) {
        $request->validate(['email' => 'required|email', 'password' => 'required']);
        
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        // Bloqueo si la cuenta fue rechazada por el administrador
        if ($user->status === 'rejected') {
            return response()->json(['message' => 'Tu cuenta ha sido rechazada. Contacta al soporte.'], 403); 
        }

        return response()->json([
            'access_token' => $user->createToken('auth_token')->plainTextToken,
            'user' => $user
        ]);
    }

    // --- LOGIN CON GOOGLE ---
    public function loginGoogle(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
            'photoURL' => 'nullable|string',
        ]);

        $user = User::where('email', $request->email)->first();

        // Si el usuario no existe, lo creamos como "invitado"
        if (!$user) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make(Str::random(24)),
                'role' => 'ciudadano',
                'status' => 'invitado', 
                'photo_url' => $request->photoURL,
            ]);
        }

        return response()->json([
            'access_token' => $user->createToken('auth_token')->plainTextToken,
            'user' => $user
        ]);
    }

    // --- VERIFICACIÓN DE DOCUMENTOS (Para usuarios de Google) ---
    // Recibe FormData desde Ionic con archivos reales
    public function verificarCuenta(Request $request) 
    {
        $request->validate([
            'curp' => 'required|string|size:18',
            'ine_frente' => 'required|image|mimes:jpg,jpeg,png|max:2048', 
            'ine_reverso' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = $request->user();

        // 1. Guardamos archivos en Storage (public/ine_images)
        $pathFrente = $request->file('ine_frente')->store('ine_images', 'public');
        $pathReverso = $request->file('ine_reverso')->store('ine_images', 'public');

        // 2. Actualizamos el registro del usuario
        $user->update([
            'curp' => strtoupper($request->curp),
            'ine_frente' => $pathFrente,
            'ine_reverso' => $pathReverso,
            'status' => 'pending' // Cambia de 'invitado' a 'pending'
        ]);

        return response()->json([
            'message' => 'Documentación recibida. Estatus actualizado a pendiente.',
            'user' => $user
        ]);
    }

    // --- OBTENER DATOS ACTUALIZADOS (Sincronización en tiempo real) ---
    // Este es el que llama la app para ver si el admin ya aprobó al usuario
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    // --- CERRAR SESIÓN ---
    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada']);
    }
}