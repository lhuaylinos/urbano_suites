<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactoController extends Controller
{
    // solo se valida, el proyecto no envia correos
    public function enviar(Request $request)
    {
        // --- reglas del formulario ---
        $request->validate([
            'nombre'  => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email', 'max:150'],
            'mensaje' => ['required', 'string', 'max:1000'],
        ], [], [
            'email' => 'correo electrónico',
        ]);

        // --- vuelve al formulario con el aviso ---
        return redirect()->route('contacto')
            ->with('exito', 'Mensaje enviado, te responderemos pronto');
    }
}
