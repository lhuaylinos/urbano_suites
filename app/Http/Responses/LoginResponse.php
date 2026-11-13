<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

/**
 * Decide a dónde va cada usuario después de iniciar sesión, según su rol.
 * Fortify usa esta clase porque la registramos en FortifyServiceProvider::register().
 */
class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $usuario = $request->user();

        if ($usuario->hasRole('administrador')) {
            return redirect()->route('admin.panel');
        }

        if ($usuario->hasRole('recepcionista')) {
            return redirect()->route('recepcion.panel');
        }

        if ($usuario->hasRole('limpieza')) {
            return redirect()->route('limpieza.panel');
        }

        // Huésped: vuelve a la página que quería ver (por ejemplo, la reserva) o al inicio
        return redirect()->intended(route('inicio'));
    }
}
