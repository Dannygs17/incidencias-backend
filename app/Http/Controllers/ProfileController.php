<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    // ==========================================
    // 1. RUTAS COMPARTIDAS / API (MÓVIL - IONIC)
    // ==========================================

    public function show(Request $request)
    {
        return response()->json($request->user());
    }

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

    // ==========================================
    // 2. RUTAS WEB (PANEL DE ADMINISTRACIÓN BREEZE)
    // ==========================================

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    // ==========================================
    // 3. ACTUALIZACIÓN (Maneja tanto Ionic como Web)
    // ==========================================
    public function update(Request $request)
    {
        // A) Si la petición viene de Ionic (API / JSON)
        if ($request->expectsJson() || $request->is('api/*')) {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $request->user()->update([
                'name' => $request->name,
            ]);

            return response()->json([
                'message' => 'Perfil actualizado',
                'user' => $request->user()
            ]);
        }

        // B) Si la petición viene del Panel Web (Breeze)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$request->user()->id],
        ]);

        $request->user()->fill($request->only('name', 'email'));

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
}