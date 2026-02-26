<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\IncidenciaController; 

// --- RUTAS PÚBLICAS ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- RUTAS PROTEGIDAS (Middleware Sanctum) ---
Route::middleware('auth:sanctum')->group(function () {
    
    // Ruta para obtener los datos del usuario logueado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rutas para Incidencias
    Route::post('/incidencias', [IncidenciaController::class, 'store']); // Guardar reporte nuevo
    
    // --- NUEVA RUTA PARA TUS REPORTES EN LA APP MÓVIL ---
    Route::get('/mis-reportes', [IncidenciaController::class, 'misReportes']);

    // Ruta para cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout']);
});