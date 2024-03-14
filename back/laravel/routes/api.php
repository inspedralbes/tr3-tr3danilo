<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PeliculesController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\SessionController;
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
Route::post('/compras', [CompraController::class, 'guardarCompra']);
Route::get('/compras', [CompraController::class, 'mostrarCompra']);
Route::get('/sessions', [SessionController::class, 'mostrarSesion']);
Route::get('/pelicules', [PeliculesController::class, 'showPelicules']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
