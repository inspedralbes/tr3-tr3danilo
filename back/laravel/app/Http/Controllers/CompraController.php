<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compra;
use App\Models\Butaca;
use App\Models\Session;
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
            // Obtener las compras asociadas al usuario
            $compras = Compra::where('id_user', $usuario->id)->get();

            // Array para almacenar los datos de la sesión y las butacas compradas
            $sesionYButacas = [];

            // Obtener los datos de la sesión
            $sesionYButacas['sesion'] = [];

            foreach ($compras as $compra) {
                // Obtener los datos de la sesión
                $sesionYButacas['sesion'][] = Session::find($compra->sesion_id);

                // Obtener los datos de las butacas compradas
                $butacasCompradas = [];
                $butacasCompradas[] = Butaca::find($compra->butaca_id);
                $sesionYButacas['butacas'][] = $butacasCompradas;
            }

            // Devolver los datos en formato JSON
            return response()->json($sesionYButacas);
        } else {
            // No se encontró un usuario con el correo proporcionado
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
        // Buscar todas las compras asociadas a la sesión específica
        $compras = Compra::where('sesion_id', $sessionId)->get();

        // Array para almacenar los IDs de las butacas ocupadas
        $butacasOcupadas = [];

        // Iterar sobre las compras y obtener los IDs de las butacas ocupadas
        foreach ($compras as $compra) {
            $butacasOcupadas[] = $compra->butaca_id;
        }

        // Buscar las butacas ocupadas en la tabla Butaca que tengan el campo 'ocupacion' igual a 'ocupado'
        $butacas = Butaca::whereIn('id', $butacasOcupadas)->where('ocupacion', 'ocupado')->get();

        return response()->json($butacas);
    }
    
    public function obtenerButacasPorSesion()
    {
        // Obtener todas las sesiones
        $sesiones = Session::all();

        // Array para almacenar el número total de butacas ocupadas por sesión
        $butacasOcupadasPorSesion = [];

        // Iterar sobre las sesiones y contar las butacas ocupadas
        foreach ($sesiones as $sesion) {
            // Buscar todas las compras asociadas a la sesión específica
            $compras = Compra::where('sesion_id', $sesion->id)->get();

            // Contador para almacenar el número total de butacas ocupadas
            $numeroButacasOcupadas = 0;

            // Iterar sobre las compras y contar las butacas ocupadas
            foreach ($compras as $compra) {
                $butacasOcupadas = Butaca::where('id', $compra->butaca_id)->where('ocupacion', 'ocupado')->count();
                $numeroButacasOcupadas += $butacasOcupadas;
            }

            // Almacenar el número total de butacas ocupadas por sesión
            $butacasOcupadasPorSesion[$sesion->id] = $numeroButacasOcupadas;
        }

        return $butacasOcupadasPorSesion;
    }
 
    
    


}
