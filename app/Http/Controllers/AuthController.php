<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Str;

// --- NUEVAS IMPORTACIONES PARA EL CORREO DEL PIN ---
use App\Mail\RecuperarPasswordPin;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // --- REGISTRO MANUAL ---
    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'curp' => 'required|string|unique:users',
            'ine_frente' => 'required|image|mimes:jpg,jpeg,png|max:2048', 
            'ine_reverso' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'name.required' => 'El nombre completo es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido (ej. usuario@correo.com).',
            'email.unique' => 'Este correo ya está registrado en el sistema.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'curp.required' => 'La CURP es obligatoria.',
            'curp.unique' => 'Esta CURP ya se encuentra registrada en otra cuenta.',
            'ine_frente.required' => 'La foto frontal del INE es obligatoria.',
            'ine_reverso.required' => 'La foto del reverso del INE es obligatoria.',
            'ine_frente.max' => 'La imagen frontal es muy pesada (Máximo 2MB).',
            'ine_reverso.max' => 'La imagen del reverso es muy pesada (Máximo 2MB).',
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

        if ($user->status === 'rejected') {
            return response()->json([
                'message' => 'Tu acceso ha sido revocado permanentemente.',
                'motivo' => $user->rejection_reason
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

        if ($user && $user->status === 'rejected') {
            return response()->json([
                'message' => 'Esta cuenta de Google ha sido bloqueada para el sistema.',
                'motivo' => $user->rejection_reason
            ], 403);
        }

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

    // --- VERIFICACIÓN DE DOCUMENTOS ---
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
    // SOLUCIÓN: Usamos trim() para asegurar que no haya espacios invisibles que rompan Firebase
    public function saveFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = $request->user();
        $user->fcm_token = trim($request->fcm_token); // Limpia la cadena
        $user->save();

        return response()->json([
            'message' => 'Token de dispositivo guardado correctamente.'
        ]);
    }

    // --- SINCRONIZACIÓN ---
    public function me(Request $request)
    {
        $user = $request->user();

        if ($user->status === 'rejected') {
            $user->tokens()->delete(); 
            return response()->json(['message' => 'Sesión revocada'], 403);
        }

        return response()->json($user);
    }

    public function logout(Request $request) {
        $user = $request->user();
        $user->fcm_token = null;
        $user->save();

        $user->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada']);
    }

    // ====================================================
    // --- RECUPERAR CONTRASEÑA ---
    // ====================================================

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Si el correo existe, enviaremos un PIN.'], 200);
        }

        $pin = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        $user->reset_pin = $pin;
        $user->save();

        Mail::to($user->email)->send(new RecuperarPasswordPin($pin));

        return response()->json(['message' => 'Si el correo existe, enviaremos un PIN.'], 200);
    }

    public function resetPasswordPin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'pin' => 'required|string|size:6',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::where('email', $request->email)->where('reset_pin', $request->pin)->first();

        if (!$user) {
            return response()->json(['message' => 'El código PIN es incorrecto o ha expirado.'], 400);
        }

        $user->password = Hash::make($request->password);
        $user->reset_pin = null; 
        $user->save();

        return response()->json(['message' => 'Contraseña actualizada correctamente.'], 200);
    }
}