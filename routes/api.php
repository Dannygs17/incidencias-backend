<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\IncidenciaController; 
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- RUTAS PÚBLICAS ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login-google', [AuthController::class, 'loginGoogle']);


// --- RUTAS PROTEGIDAS (Middleware Sanctum) ---
Route::middleware('auth:sanctum')->group(function () {
    
    // Obtener datos frescos del usuario (Sincronización de estatus)
    // Apuntamos al método 'me' o 'show' del controlador para mantener orden
    Route::get('/user', [AuthController::class, 'me']); 
    

    // --- VERIFICACIÓN DE IDENTIDAD (Google Users) ---
    Route::post('/verificar-cuenta', [AuthController::class, 'verificarCuenta']);

    // --- RUTAS DE PERFIL ---
    Route::get('/user/profile', [ProfileController::class, 'show']);
    Route::post('/user/profile/update', [ProfileController::class, 'update']);
    Route::post('/user/profile/password', [ProfileController::class, 'changePassword']);

    // --- RUTAS DE INCIDENCIAS (REPORTES) ---
    Route::post('/incidencias', [IncidenciaController::class, 'store']); 
    Route::get('/mis-reportes', [IncidenciaController::class, 'misReportes']);
    Route::get('/categorias', [App\Http\Controllers\CategoriaController::class, 'getCategoriasApi']);

    // Cerrar sesión (Mata el token en la base de datos)
    Route::post('/logout', [AuthController::class, 'logout']);

});