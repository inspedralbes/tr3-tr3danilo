<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\DatosCompraMail;
use Illuminate\Support\Facades\Log;

class MailController extends Controller
{
    public function enviarCorreoConfirmacion(Request $request)
    {

        $datosUsuario = $request->input('datosUsuario');
        $correoElectronico = $datosUsuario['correoElectronico'];
        $datosCompra = $request->input('datosCompra');
        
        // Convertir los arrays $datosUsuario y $datosCompra a cadenas de texto JSON
        $datosUsuarioString = json_encode($datosUsuario);
        $datosCompraString = json_encode($datosCompra);
        
        // Registrar los datos en el log
        Log::info("Datos del usuario: " . $datosUsuarioString);
        Log::info("Datos de la compra: " . $datosCompraString);
        Log::info("Correo electrónico: " . $correoElectronico);

        // Aquí puedes personalizar los datos que deseas enviar al correo electrónico

        Mail::to($correoElectronico)->send(new DatosCompraMail($datosCompra));

        return response()->json(['message' => 'Correo electrónico de confirmación enviado correctamente']);
    }
}
