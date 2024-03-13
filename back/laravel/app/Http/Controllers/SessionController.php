<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\Pelicules;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function mostrarSesion()
    {
        // Obtener una película aleatoria
        $pelicula = Pelicules::inRandomOrder()->first();

        // Obtener todas las sesiones para la película seleccionada
        $sessions = Session::where('pelicula_id', $pelicula->id)->get();

        // Aquí decides qué datos de la película quieres enviar junto con las sesiones
        // Por ejemplo, podrías enviar el título y la descripción de la película
        $datosPelicula = [
            'titulo' => $pelicula->títol,
            'descripcion' => $pelicula->descripció,
            'imagen'=> $pelicula->enllaç_imatge
            // Puedes agregar más datos aquí según tus necesidades
        ];

        return response()->json(['pelicula' => $datosPelicula, 'sessions' => $sessions]);
    }
}
