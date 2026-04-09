<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\AdminUserController; 
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Incidencia;

Route::get('/', function () {
    return redirect()->route('register');
});

// GRUPO PROTEGIDO PARA ADMIN (Requiere sesión y verificación)
Route::middleware(['auth', 'verified'])->group(function () {

    // 1. BIENVENIDA (Dashboard General)
    Route::get('/dashboard', function () {
        if (auth()->user()->role !== 'admin') {
            return "Acceso denegado. Tu cuenta es de tipo: " . auth()->user()->role;
        }

        $pendientesAprobar = User::where('status', 'pending')->count();
        $reportesNuevos = Incidencia::where('estado', 'pendiente')->count();

        return view('dashboard', compact('pendientesAprobar', 'reportesNuevos'));
    })->name('dashboard');

    // 2. PANEL DE USUARIOS 
    Route::get('/admin/usuarios', [AdminUserController::class, 'index'])->name('admin.usuarios');
    
    // Acciones individuales (en singular)
    Route::post('/admin/user/{id}/approve', [AdminUserController::class, 'approve'])->name('admin.aprobar');
    Route::post('/admin/user/{id}/reject', [AdminUserController::class, 'reject'])->name('admin.rechazar');
    Route::post('/admin/user/{id}/corregir', [AdminUserController::class, 'solicitarCorreccion'])->name('admin.usuarios.corregir');
    
    Route::delete('/admin/user/{id}', [AdminUserController::class, 'destroy'])->name('admin.eliminar');

   

    // 3. PANEL DE INCIDENCIAS (Gestión de Reportes)
    Route::get('/admin/incidencias', [IncidenciaController::class, 'dashboardAdmin'])
        ->name('admin.incidencias');

    Route::get('tabla-incidencias/{categoria}', [IncidenciaController::class, 'tablaIncidencias'])
        ->name('admin.tabla_incidencias');

    Route::post('admin/incidencia/{id}/estado', [IncidenciaController::class, 'actualizarEstado'])
        ->name('admin.actualizar_estado_incidencia');

    // 4. PANEL DE ESTADÍSTICAS
    Route::get('/admin/estadisticas', [IncidenciaController::class, 'mostrarEstadisticas'])
        ->name('admin.estadisticas');

    // 5. PANEL DE CATEGORÍAS (CRUD)
    Route::prefix('admin')->group(function () {
        Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
        Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
        Route::put('/categorias/{id}', [CategoriaController::class, 'update'])->name('categorias.update');
        Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');
    });
});

// RUTAS DE PERFIL (Laravel Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
   
});

require __DIR__.'/auth.php';