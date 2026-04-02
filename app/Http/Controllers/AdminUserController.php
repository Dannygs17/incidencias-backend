<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Incidencia;
use App\Mail\CuentaAprobada;
use App\Mail\CorregirDocumentacion; // <--- IMPORTAMOS EL NUEVO CORREO
use Illuminate\Support\Facades\Mail;

class AdminUserController extends Controller
{
    // --- LISTA DE CIUDADANOS ---
    public function index()
    {
        $users = User::where('role', 'ciudadano')->orderBy('created_at', 'desc')->get();
        return view('Admin.usuarios', compact('users'));
    }

    // --- APROBAR CIUDADANO ---
    public function approve($id)
    {
        $user = User::findOrFail($id);
        
        $user->update([
            'status' => 'approved',
            'rejection_reason' => null, 
            'correction_fields' => null // Limpiamos el historial por completo
        ]);

        // Enviamos el correo
        Mail::to($user->email)->send(new CuentaAprobada($user));

        return back()->with('success', 'Usuario aprobado y notificado por correo.');
    }

    // --- NUEVO: SOLICITAR CORRECCIÓN (Foto borrosa, etc.) ---
    public function solicitarCorreccion(Request $request, $id)
    {
        // Validamos que el admin escriba un motivo y seleccione al menos un checkbox
        $request->validate([
            'motivo' => 'required|string|max:255',
            'correction_fields' => 'required|array|min:1',
            'correction_fields.*' => 'string|in:curp,ine_frente,ine_reverso' // Evita que inyecten datos falsos
        ]);

        $user = User::findOrFail($id);
        
        $user->update([
            'status' => 'action_required',
            'rejection_reason' => $request->motivo,
            'correction_fields' => $request->correction_fields // Guardamos el arreglo JSON
        ]);

        // === ENVIAMOS EL CORREO AL CIUDADANO ===
        Mail::to($user->email)->send(new CorregirDocumentacion($user, $request->motivo, $request->correction_fields));

        return back()->with('warning', 'Se ha solicitado al ciudadano que corrija su documentación y se le notificó por correo.');
    }

    // --- RECHAZO DEFINITIVO (Bloqueo total) ---
    public function reject($id)
    {
        $user = User::findOrFail($id);
        
        $user->update([
            'status' => 'rejected',
            'rejection_reason' => 'Cuenta declinada por incumplimiento de términos.',
            'correction_fields' => null
        ]);
        
        // Revocamos tokens para expulsarlo inmediatamente de la App (Error 403)
        $user->tokens()->delete(); 

        return back()->with('danger', 'Usuario rechazado permanentemente.');
    }

    // --- ELIMINAR REGISTRO ---
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->tokens()->delete(); 
        $user->delete();

        return back()->with('success', 'Usuario eliminado permanentemente.');
    }
}