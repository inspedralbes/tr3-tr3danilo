<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\Pelicules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class SessionController extends Controller
{
    public function mostrarSesion()
    {
        // Obtener todas las sesiones disponibles
        $sessions = Session::all();
        //Log::info($sessions);
        // Lista para almacenar sesiones con película asociada
        $sesionesConPelicula = [];
    
        foreach ($sessions as $session) {
            // Obtener el ID de la película de la sesión
            $peliculaId = $session->pelicula_id;
            //Log::info("Pelicula id: $peliculaId");
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
        //Log::info($sesionesConPelicula);
        // Devolver la lista de sesiones con la información de la película asociada
        return response()->json(['sessions' => $sesionesConPelicula]);
    }
    public function mostrarSesionPorId($sesionId)
    {
        // Obtener la sesión específica
        $session = Session::find($sesionId);
    
        if (!$session) {
            Log::info("No se encontró la sesión con id: $sesionId");
            return response()->json(['error' => 'No se encontró la sesión']);
        }
    
        //Log::info($session);
    
        // Obtener el ID de la película de la sesión
        $peliculaId = $session->pelicula_id;
        //Log::info("Pelicula id: $peliculaId");
    
        // Buscar la película correspondiente en la tabla de películas
        $pelicula = Pelicules::find($peliculaId);
    
        if ($pelicula) {
            // Si la película existe, agregar la sesión y la información de la película a la lista
            $sesionConPelicula = [
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
            return response()->json(['error' => 'No hay película asociada a la sesión']);
        }
    
        //Log::info($sesionConPelicula);
    
        // Devolver la sesión con la información de la película asociada
        return response()->json(['session' => $sesionConPelicula]);
    }
    public function afegirSessio(Request $request)
    {
        /*
        $request->validate([
            'data.pelicula_id' => 'required',
            'data.fecha' => 'required|date_format:d/m/Y',
            'data.hora' => 'required|date_format:H:i:s',
        ]);
    */
        $session = new Session();
        $session->pelicula_id = $request->input('data.pelicula_id');
        $session->fecha = Carbon::createFromFormat('d/m/Y', $request->input('data.fecha'))->toDateString();
        $session->hora = $request->input('data.hora');
    
        $session->save();
    
        return response()->json(['session' => $session]);
    }
    public function esborrarSession($sesionId)
    {
        $session = Session::find($sesionId);
    
        if (!$session) {
            return response()->json(['error' => 'No se encontró la sesión']);
        }
    
        $session->delete();
    
        return response()->json(['message' => 'Sesión eliminada']);
    }    

}
