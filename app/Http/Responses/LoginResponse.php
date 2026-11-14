<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $usuario = $request->user();

        // --- destino segun el rol ---
        if ($usuario->hasRole('administrador')) {
            return redirect()->route('admin.panel');
        }

        if ($usuario->hasRole('recepcionista')) {
            return redirect()->route('recepcion.panel');
        }

        if ($usuario->hasRole('limpieza')) {
            return redirect()->route('limpieza.panel');
        }

        // el huesped vuelve a donde estaba o al inicio
        return redirect()->intended(route('inicio'));
    }
}
