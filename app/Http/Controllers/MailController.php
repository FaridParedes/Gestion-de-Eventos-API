<?php

namespace App\Http\Controllers;

use App\Mail\Invitacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function enviarInvitacion(Request $request) {
        // Recupera los datos del formulario de Vue.js
        $datos = $request->all();

        try {
            // Envía el correo de invitación utilizando la clase Mailable
            Mail::to($datos['mail'])->send(new Invitacion($datos['enlace']));

            // Si el correo se envía con éxito, puedes devolver una respuesta de éxito
            return response()->json([
                'code' => 200,
                'mensaje' => 'Correo de invitación enviado con éxito'
            ], 200);
        } catch (\Exception $e) {
            // Maneja cualquier error que ocurra durante el envío del correo
            return response()->json([
                'code' => 500,
                'error' => 'No se pudo enviar el correo de invitación'
            ], 500);
        }
    }
}
