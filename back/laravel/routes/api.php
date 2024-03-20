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
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//RUTAS DE AUTENTICACION
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
//RUTAS CORREO
Route::post('/enviarCorreo', [MailController::class, 'enviarCorreoConfirmacion']);
//RUTAS DE COMPRA
Route::post('/{sessionId}/ocupadas',  [CompraController::class, 'obtenerButacasOcupadas']);
Route::post('/efectuarCompra', [CompraController::class, 'guardarCompra']);
Route::get('/compras', [CompraController::class, 'mostrarCompra']);
//RUTAS DE SESIONES Y PELICULAS
Route::get('/sessions', [SessionController::class, 'mostrarSesion']);
Route::post('/sessions/{sesionId}', [SessionController::class, 'mostrarSesionPorId']);
Route::get('/pelicules', [PeliculesController::class, 'showPelicules']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
