<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Compra;
use App\Models\Butaca;
use App\Models\Session;
use App\Models\Pelicules;
use App\Models\User;

class CompraController extends Controller
{
    public function mostrarCompra()
    {
        $compra = Compra::all();
        return response()->json($compra);
    }
    public function obtenerEntradasPorCorreo(Request $request)
    {
        $data = $request->all();
        $correo = $data['correo'];

        // Obtener el usuario por su correo
        $usuario = User::where('email', $correo)->first();

        if ($usuario) {
            // Obtener todas las compras asociadas al usuario
            $compras = Compra::where('id_user', $usuario->id)->get();

            // Array para almacenar los datos de la sesión y las butacas compradas
            $sesionYButacas = [];

            foreach ($compras as $compra) {
                // Obtener los datos de la sesión asociada a la compra
                $sesion = Session::find($compra->sesion_id);

                // Obtener los datos de la película asociada a la sesión
                $pelicula = Pelicules::find($sesion->pelicula_id);

                // Obtener el título de la película
                $titulo = $pelicula->títol;
                
                // Obtener los datos de las butacas compradas en esta compra
                $butacasCompradas = [
                    'butaca' => $compra->butaca,
                    'precio' => $compra->precio,
                ];
                

                // Agregar los datos de la sesión y las butacas compradas al array
                $sesionYButacas[] = [
                    'sesion' => $sesion,
                    'pelicula' => $titulo,
                    'butacas' => [$butacasCompradas],
                ];
            }

            // Devolver los datos en formato JSON
            return response()->json($sesionYButacas);
        } else {
            // No se encontró un usuario con el correo proporcionado
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
    }
    public function obtenerEntradasPorUsuario($userID)
    {
    
        // Obtener el usuario por su ID
        $usuario = User::find($userID);
    
        if ($usuario) {
            // Obtener todas las compras asociadas al usuario
            $compras = Compra::where('id_user', $userID)->get();
    
            // Array para almacenar los datos de la sesión y las butacas compradas
            $sesionYButacas = [];
    
            foreach ($compras as $compra) {
                // Obtener los datos de la sesión asociada a la compra
                $sesion = Session::find($compra->sesion_id);
    
                // Obtener los datos de la película asociada a la sesión
                $pelicula = Pelicules::find($sesion->pelicula_id);
    
                // Obtener el título de la película
                $titulo = $pelicula->títol;
                
                // Obtener los datos de las butacas compradas en esta compra
                $butacasCompradas = [
                    'butaca' => $compra->butaca,
                    'precio' => $compra->precio,
                ];
                
    
                // Agregar los datos de la sesión y las butacas compradas al array
                $sesionYButacas[] = [
                    'sesion' => $sesion,
                    'pelicula' => $titulo,
                    'butacas' => [$butacasCompradas],
                ];
            }
    
            // Devolver los datos en formato JSON
            return response()->json($sesionYButacas);
        } else {
            // No se encontró un usuario con el ID proporcionado
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
    }
    public function guardarCompra(Request $request)
    {
        // Verificar si el usuario está autenticado
        if ($user = auth('sanctum')->user()) {
            $data = $request->all();

            // Obtener el precio base
            $precioBase = 6;

            // Iterar sobre cada asiento y guardar cada uno en la tabla Compra
            foreach ($data['seats'] as $seatData) {
                $compra = new Compra();

                $compra->sesion_id = $data['sessionId'];
                $compra->id_user = auth('sanctum')->id(); // Asignar el ID del usuario autenticado
                $compra->butaca = $seatData['row'] . '-' . $seatData['column']; // Supongo que tienes una forma de identificar cada butaca

                // Verificar si la butaca está en la fila 6 y ajustar el precio
                if ($seatData['row'] == 6) {
                    $compra->precio = 8; // Precio diferente para la fila 6
                } else {
                    $compra->precio = $precioBase;
                }

                $compra->ocupacion = 'ocupado';

                // Guardar la compra en la base de datos
                $compra->save();
            }

            // Devolver la respuesta exitosa
            return response()->json(['message' => 'Compra guardada correctamente'], 200);
        } else {
            // El usuario no está autenticado, devolver una respuesta de error
            return response()->json(['error' => 'No autorizado'], 401);
        }
    }
    public function obtenerButacasOcupadas($sessionId)
    {
        $compras = Compra::where('sesion_id', $sessionId)->where('ocupacion', 'ocupado')->get();

        // Array para almacenar las butacas ocupadas
        $butacasOcupadas = [];

        // Iterar sobre las compras y obtener las butacas ocupadas
        foreach ($compras as $compra) {
            $butacasOcupadas[] = $compra->butaca;
        }

        return response()->json($butacasOcupadas);
    }

    public function obtenerButacasPorSesion()
{
    // Obtener todas las sesiones
    $sesiones = Session::all();
    
    // Array para almacenar el número total de butacas ocupadas por sesión
    $butacasPorSesion = [];
    
    // Iterar sobre las sesiones y contar las butacas ocupadas
    foreach ($sesiones as $sesion) {
        // Obtener todas las compras asociadas a la sesión específica
        $compras = Compra::where('sesion_id', $sesion->id)->get();
    
        // Contador para almacenar el número total de butacas ocupadas
        $numeroButacasOcupadas = 0;
        Log::info($compras);
        // Iterar sobre las compras de esta sesión y contar las butacas ocupadas
        foreach ($compras as $compra) {
            if ($compra->ocupacion === 'ocupado') {
                $numeroButacasOcupadas++;
                Log::info($numeroButacasOcupadas);
            }
        }
        
        // Almacenar el número total de butacas ocupadas por sesión
        $butacasPorSesion[$sesion->id] = $numeroButacasOcupadas;
        Log::info($butacasPorSesion);
    }
    
    return $butacasPorSesion;
}

    

}
