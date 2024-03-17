<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\Pelicules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SessionController extends Controller
{
    public function mostrarSesion()
    {
        // Obtener todas las sesiones disponibles
        $sessions = Session::all();
        Log::info($sessions);
        // Lista para almacenar sesiones con película asociada
        $sesionesConPelicula = [];
    
        foreach ($sessions as $session) {
            // Obtener el ID de la película de la sesión
            $peliculaId = $session->pelicula_id;
            Log::info("Pelicula id: $peliculaId");
            // Buscar la película correspondiente en la tabla de películas
            $pelicula = Pelicules::find($peliculaId);
        
            if ($pelicula) {
                // Si la película existe, agregar la sesión y la información de la película a la lista
                $sesionesConPelicula[] = [
                    'sesion' => $session,
                    'pelicula' => [
                        'titulo' => $pelicula->títol,
                        'descripcion' => $pelicula->descripció,
                        'imagen' => $pelicula->enllaç_imatge
                        // Puedes agregar más datos aquí según tus necesidades
                    ]
                ];
            } else {
                Log::info("No hay pelicula asociada a la sesion");
            }
        }
        Log::info($sesionesConPelicula);
        // Devolver la lista de sesiones con la información de la película asociada
        return response()->json(['sessions' => $sesionesConPelicula]);
    }
    

}
