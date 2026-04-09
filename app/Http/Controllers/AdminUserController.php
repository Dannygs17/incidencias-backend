<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Incidencia;
use App\Mail\CuentaAprobada;
use App\Mail\CorregirDocumentacion;
use App\Mail\CuentaRechazada; // <--- IMPORTAMOS EL NUEVO CORREO
use Illuminate\Support\Facades\Mail;

class AdminUserController extends Controller
{
    // --- LISTA DE CIUDADANOS ---
    public function index()
    {
        $users = User::where('role', 'ciudadano')->orderBy('created_at', 'desc')->get();
        return view('admin.usuarios', compact('users'));
    }

    // --- APROBAR CIUDADANO ---
    public function approve($id)
    {
        $user = User::findOrFail($id);
        
        $user->update([
            'status' => 'approved',
            'rejection_reason' => null, 
            'correction_fields' => null
        ]);

        Mail::to($user->email)->send(new CuentaAprobada($user));

        return back()->with('success', 'Usuario aprobado y notificado por correo.');
    }

    // --- SOLICITAR CORRECCIÓN ---
    public function solicitarCorreccion(Request $request, $id)
    {
        $request->validate([
            'motivo' => 'required|string|max:255',
            'correction_fields' => 'required|array|min:1',
            'correction_fields.*' => 'string|in:curp,ine_frente,ine_reverso' 
        ]);

        $user = User::findOrFail($id);
        
        $user->update([
            'status' => 'action_required',
            'rejection_reason' => $request->motivo,
            'correction_fields' => $request->correction_fields 
        ]);

        Mail::to($user->email)->send(new CorregirDocumentacion($user, $request->motivo, $request->correction_fields));

        return back()->with('warning', 'Se ha solicitado al ciudadano que corrija su documentación y se le notificó por correo.');
    }

    // --- RECHAZO DEFINITIVO (Bloqueo total) ---
    public function reject(Request $request, $id) // <--- Agregado Request
    {
        // Validamos que el admin escriba un motivo de rechazo
        $request->validate([
            'motivo' => 'required|string|max:255'
        ]);

        $user = User::findOrFail($id);
        
        $user->update([
            'status' => 'rejected',
            'rejection_reason' => $request->motivo, // <--- Guardamos el motivo real
            'correction_fields' => null
        ]);
        
        // Enviamos el correo al ciudadano explicando por qué fue rechazado
        Mail::to($user->email)->send(new CuentaRechazada($user, $request->motivo));

        // Revocamos tokens para expulsarlo inmediatamente de la App (Error 403)
        $user->tokens()->delete(); 

        return back()->with('danger', 'Usuario rechazado permanentemente y notificado por correo.');
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