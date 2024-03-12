<?php

namespace App\Http\Controllers;

use App\Models\Pelicules;
use Illuminate\Http\Request;

class PeliculesController extends Controller
{
    public function showPelicules()
    {
        // Obtener las películas desde la base de datos
        $pelicules = Pelicules::all();

        // Convertir las películas a formato JSON y devolverlas directamente
        return response()->json($pelicules);
    }
}

?>