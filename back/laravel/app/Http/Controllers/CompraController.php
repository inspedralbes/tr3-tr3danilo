<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compra;
use App\Models\Butaca; 

class CompraController extends Controller
{
    public function mostrarCompra()
    {
        $compra = Compra::all();
        return response()->json($compra);
    }

    public function guardarCompra(Request $request)
{
    $data = $request->all();
    // Iterar sobre cada asiento y guardar cada uno en su propia fila en la tabla Butaca
    foreach ($data['seats'] as $seatData) {
        $butaca = new Butaca();

        $butaca->id = $seatData['id'];
        $butaca->precio = $seatData['price'];
        $butaca->ocupacion ='ocupado';

        // Guardar los datos en la tabla Butaca
        $butaca->save();

        $compra = new Compra();

        $compra->sesion_id = $data['sessionId'];
        $compra->butaca_id = $butaca->id; // Guardar el id del asiento en la tabla Compra

        // Guardar la compra en la base de datos
        $compra->save();
    }

    // Devolver la compra en formato JSON
    return response()->json($compra);
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