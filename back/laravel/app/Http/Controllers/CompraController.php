<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compra;

class CompraController extends Controller
{
    public function mostrarCompra()
    {
        // Obtener las películas desde la base de datos
        $compra = Compra::all();

        // Convertir las películas a formato JSON y devolverlas directamente
        return response()->json($compra);
    }

    public function guardarCompra()
    {
        $compra = new Compra();

        // Asignar los datos recibidos al modelo
        $compra->sesion_id = $request->sesion_id;
        $compra->asientos = $request->asientos;
        $compra->precio = $request->precio;

        // Guardar la película en la base de datos
        $compra->save();

        // Devolver la película en formato JSON
        return response()->json($compra);
    }	

}
