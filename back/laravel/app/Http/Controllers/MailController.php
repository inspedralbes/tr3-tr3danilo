<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DatosCompraMail;

class MailController extends Controller
{
    public function enviarCorreoConfirmacion(Request $request)
    {
        /*
        $data = $request->validate([
            'nombre' => 'required|string',
            'apellido' => 'required|string',
            'correoElectronico' => 'required|email',
            'datosCompra' => 'required|array', // Asegúrate de que los datos de compra estén presentes y sean un array
        ]);
*/
        $correoElectronico = $request['correoElectronico'];
        $nombre = $request['nombre'];
        $apellido = $request['apellido'];
        $datosCompra = $request['datosCompra']; // Datos de compra proporcionados en la solicitud

        // Aquí puedes personalizar los datos que deseas enviar al correo electrónico

        Mail::to($correoElectronico)->send(new DatosCompraMail($datosCompra));

        return response()->json(['message' => 'Correo electrónico de confirmación enviado correctamente']);
    }
}
