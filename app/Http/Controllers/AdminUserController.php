<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Incidencia;
use App\Mail\CuentaAprobada;
use Illuminate\Support\Facades\Mail;

class AdminUserController extends Controller
{
    // Muestra la lista de ciudadanos
    public function index()
    {
        $users = User::where('role', 'ciudadano')->orderBy('created_at', 'desc')->get();
        return view('Admin.usuarios', compact('users'));
    }

    // Acción de Aprobar
    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'approved';
        $user->save();

        // Enviamos el correo
        Mail::to($user->email)->send(new CuentaAprobada($user));

        return back()->with('success', 'Usuario aprobado y notificado por correo.');
    }

    // Acción de Rechazar
    public function reject($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'rejected';
        $user->save();
        
        // Revocar tokens para que no pueda entrar a la App
        $user->tokens()->delete(); 

        return back()->with('success', 'Usuario rechazado.');
    }

    // Acción de Eliminar
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->tokens()->delete(); 
        $user->delete();

        return back()->with('success', 'Usuario eliminado permanentemente.');
    }
}