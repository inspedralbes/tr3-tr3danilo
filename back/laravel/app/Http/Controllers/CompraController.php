<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compra;
use App\Models\Butaca; // Asegúrate de que este modelo exista

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
        $butaca->ocupacion = $seatData['status'];

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

}