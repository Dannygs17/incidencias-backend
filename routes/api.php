<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\IncidenciaController; 
use App\Http\Controllers\ProfileController; // <--- ¡No olvides importar esto!

// --- RUTAS PÚBLICAS ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- RUTAS PROTEGIDAS (Middleware Sanctum) ---
Route::middleware('auth:sanctum')->group(function () {
    
    // Ruta actual para obtener datos rápidos
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // --- RUTAS DE PERFIL ---
    // Estas conectan con el ProfileController.php que acabamos de llenar
    Route::get('/user/profile', [ProfileController::class, 'show']);
    Route::post('/user/profile/update', [ProfileController::class, 'update']);
    Route::post('/user/profile/password', [ProfileController::class, 'changePassword']);

    // Rutas para Incidencias
    Route::post('/incidencias', [IncidenciaController::class, 'store']); 
    Route::get('/mis-reportes', [IncidenciaController::class, 'misReportes']);

    // Ruta para cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout']);
});