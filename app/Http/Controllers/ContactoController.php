<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactoController extends Controller
{
    // El mensaje solo se valida: este proyecto no envía correos reales
    public function enviar(Request $request)
    {
        $request->validate([
            'nombre'  => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email', 'max:150'],
            'mensaje' => ['required', 'string', 'max:1000'],
        ], [], [
            'email' => 'correo electrónico',
        ]);

        return redirect()->route('contacto')
            ->with('exito', 'Mensaje enviado, te responderemos pronto');
    }
}
