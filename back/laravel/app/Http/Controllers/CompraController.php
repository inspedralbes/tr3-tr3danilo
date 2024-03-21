<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compra;
use App\Models\Butaca; 
use App\Models\Session; 

class CompraController extends Controller
{
    public function mostrarCompra()
    {
        $compra = Compra::all();
        return response()->json($compra);
    }

        public function obtenerSesionYButacasDeUsuario(Request $request)
        {
            // Verificar si el usuario está autenticado
            if ($user = auth('sanctum')->user()) {
                $data = $request->all();
                $sessionId = $data['sessionId'];

                // Obtener las compras asociadas a la sesión y al usuario
                $compras = Compra::where('sesion_id', $sessionId)
                    ->where('id_user', $user->id)
                    ->get();

                // Array para almacenar los datos de la sesión y las butacas compradas
                $sesionYButacas = [];

                // Obtener los datos de la sesión
                $sesionYButacas['sesion'] = Session::find($sessionId);

                // Obtener los datos de las butacas compradas
                $butacasCompradas = [];
                foreach ($compras as $compra) {
                    $butacasCompradas[] = Butaca::find($compra->butaca_id);
                }
                $sesionYButacas['butacas'] = $butacasCompradas;

                // Devolver los datos en formato JSON
                return response()->json($sesionYButacas);
            } else {
                // El usuario no está autenticado, devolver una respuesta de error
                return response()->json(['error' => 'No autorizado'], 401);
            }
        }

    public function guardarCompra(Request $request)
    {
        // Verificar si el usuario está autenticado
            if($user = auth('sanctum')->user()) {    
            $data = $request->all();
            // Iterar sobre cada asiento y guardar cada uno en su propia fila en la tabla Butaca
            foreach ($data['seats'] as $seatData) {
                $butaca = new Butaca();

                $butaca->id = $seatData['id'];
                $butaca->precio = $seatData['price'];
                $butaca->ocupacion = 'ocupado';

                // Guardar los datos en la tabla Butaca
                $butaca->save();

                $compra = new Compra();

                $compra->sesion_id = $data['sessionId'];
                $compra->butaca_id = $butaca->id; // Guardar el id del asiento en la tabla Compra
                $compra->id_user = auth('sanctum')->id(); // Asignar el ID del usuario autenticado

                // Guardar la compra en la base de datos
                $compra->save();
            }

            // Devolver la compra en formato JSON
            return response()->json($compra);
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

}