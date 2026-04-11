<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // --- REGISTRO MANUAL ---
    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'curp' => 'required|string|unique:users',
            'ine_frente' => 'required|image|mimes:jpg,jpeg,png|max:2048', 
            'ine_reverso' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

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

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registro exitoso.', 
            'access_token' => $token,
            'user' => $user,
            'status' => 'pending'
        ], 201);
    }

    // --- LOGIN NORMAL (Email y Password) ---
    public function login(Request $request) {
        $request->validate(['email' => 'required|email', 'password' => 'required']);
        
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        // BLOQUEO: Si la cuenta está rechazada
        if ($user->status === 'rejected') {
            return response()->json([
                'message' => 'Tu acceso ha sido revocado permanentemente.',
                'motivo' => $user->rejection_reason // <--- Retornamos el motivo al Frontend
            ], 403); 
        }

        return response()->json([
            'access_token' => $user->createToken('auth_token')->plainTextToken,
            'user' => $user
        ]);
    }

    // --- LOGIN CON GOOGLE (Firebase) ---
    public function loginGoogle(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
            'photoURL' => 'nullable|string',
        ]);

        $user = User::where('email', $request->email)->first();

        // BLOQUEO: Si el usuario de Google ya existía y fue rechazado
        if ($user && $user->status === 'rejected') {
            return response()->json([
                'message' => 'Esta cuenta de Google ha sido bloqueada para el sistema.',
                'motivo' => $user->rejection_reason // <--- Retornamos el motivo al Frontend
            ], 403);
        }

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

    // --- VERIFICACIÓN DE DOCUMENTOS (Acepta actualizaciones parciales) ---
    public function verificarCuenta(Request $request) 
    {
        $request->validate([
            'curp' => 'nullable|string|size:18',
            'ine_frente' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
            'ine_reverso' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = $request->user();

        if ($request->has('curp') && $request->curp != null) {
            $user->curp = strtoupper($request->curp);
        }

        if ($request->hasFile('ine_frente')) {
            $user->ine_frente = $request->file('ine_frente')->store('ine_images', 'public');
        }

        if ($request->hasFile('ine_reverso')) {
            $user->ine_reverso = $request->file('ine_reverso')->store('ine_images', 'public');
        }

        $user->status = 'pending';
        $user->rejection_reason = null;
        $user->correction_fields = null; 
        $user->save();

        return response()->json([
            'message' => 'Documentación actualizada. En revisión.',
            'user' => $user
        ]);
    }

    // --- GUARDAR TOKEN DE NOTIFICACIONES (FCM) ---
    public function saveFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = $request->user();
        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response()->json([
            'message' => 'Token de dispositivo guardado correctamente.'
        ]);
    }

    // --- SINCRONIZACIÓN (Estatus en tiempo real) ---
    public function me(Request $request)
    {
        $user = $request->user();

        // BLOQUEO EN CALIENTE: Si el admin rechaza mientras el usuario está logueado
        if ($user->status === 'rejected') {
            $user->tokens()->delete(); 
            return response()->json(['message' => 'Sesión revocada'], 403);
        }

        return response()->json($user);
    }

    public function logout(Request $request) {
        $user = $request->user();
        
        // Limpiamos el token de notificaciones para que no reciba alertas si cerró sesión
        $user->fcm_token = null;
        $user->save();

        $user->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada']);
    }
}