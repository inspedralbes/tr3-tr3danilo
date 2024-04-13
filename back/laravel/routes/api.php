<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeliculesController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MailController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí es donde puedes registrar las rutas API para tu aplicación.
| Estas rutas son cargadas por el RouteServiceProvider dentro de un grupo al que se le asigna el middleware "api".
|
*/

// Rutas de autenticación que no requieren autenticación
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::get('/{idUser}/obtenRolUsuario', [AuthController::class, 'obtenRolUsuario']);
Route::delete('borrarUsuario/{idUser}', [AuthController::class, 'borrarUsuario']);

// Rutas de correo que no requieren autenticación
Route::post('/enviarCorreo', [MailController::class, 'enviarCorreoConfirmacion']);

// Rutas de compra que requieren autenticación
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/efectuarCompra', [CompraController::class, 'guardarCompra']);
});

Route::post('/obtenerEntradasPorCorreo', [CompraController::class, 'obtenerEntradasPorCorreo']);
Route::get('/entradas/{id_usuario}', [CompraController::class, 'obtenerEntradasPorUsuario']);
Route::post('/{sessionId}/ocupadas',  [CompraController::class, 'obtenerButacasOcupadas']);
Route::get('/compras', [CompraController::class, 'mostrarCompra']);
Route::get('/butacasPorSesion', [CompraController::class, 'obtenerButacasPorSesion']);

// Rutas de sesiones y películas que no requieren autenticación
Route::get('/sessions', [SessionController::class, 'mostrarSesion']);
Route::post('/sessions/{sesionId}', [SessionController::class, 'mostrarSesionPorId']);
Route::post('/afegirSessio', [SessionController::class, 'afegirSessio']);
Route::delete('esborrarSessio/{sesionId}', [SessionController::class, 'esborrarSession']);
Route::get('/pelicules', [PeliculesController::class, 'showPelicules']);

// Ruta de usuario autenticado (solo para fines de prueba)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
