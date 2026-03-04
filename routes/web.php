<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IncidenciaController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Incidencia;

Route::get('/', function () {
    return redirect()->route('register');
});

// --- GRUPO PROTEGIDO PARA ADMIN ---
Route::middleware(['auth', 'verified'])->group(function () {

    // 1. BIENVENIDA (Dashboard con Resumen de Actividad)
    Route::get('/dashboard', function () {
        if (auth()->user()->role !== 'admin') {
            return "Acceso denegado. Tu cuenta es de tipo: " . auth()->user()->role;
        }

        // Realizamos los conteos para que el mensaje de bienvenida sea dinámico
        $pendientesAprobar = User::where('status', 'pending')->count();
        $reportesNuevos = Incidencia::where('estado', 'pendiente')->count();

        return view('dashboard', compact('pendientesAprobar', 'reportesNuevos'));
    })->name('dashboard');

    // 2. PANEL DE USUARIOS
    Route::get('/admin/usuarios', function () {
        $users = User::where('role', 'ciudadano')->orderBy('created_at', 'desc')->get();
        return view('Admin.usuarios', compact('users'));
    })->name('admin.usuarios');

    // 3. PANEL DE INCIDENCIAS
    Route::get('/admin/incidencias', [IncidenciaController::class, 'dashboardAdmin'])
        ->name('admin.incidencias');

    // --- ACCIONES DE GESTIÓN DE USUARIOS ---
    Route::post('/admin/user/{id}/approve', function ($id) {
        $user = User::findOrFail($id);
        $user->status = 'approved';
        $user->save();
        return back()->with('success', 'Usuario aprobado.');
    })->name('admin.aprobar');

    Route::post('/admin/user/{id}/reject', function ($id) {
        $user = User::findOrFail($id);
        $user->status = 'rejected';
        $user->save();
        $user->tokens()->delete(); 
        return back()->with('success', 'Usuario rechazado.');
    })->name('admin.rechazar');

    Route::delete('/admin/user/{id}', function ($id) {
        $user = User::findOrFail($id);
        $user->tokens()->delete(); 
        $user->delete();
        return back()->with('success', 'Usuario eliminado.');
    })->name('admin.eliminar');

  

        Route::get('tabla-incidencias/{categoria}', [IncidenciaController::class, 'tablaIncidencias'])
    ->name('admin.tabla_incidencias');



});




// Rutas de Perfil (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';