<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Contracts\CreatesNewUsers;

/**
 * CUS01 Registrarse.
 * Fortify llama a esta clase cuando se envía el formulario de registro.
 * Valida los datos, crea el usuario y le asigna el rol "huesped".
 */
class CreateNewUser implements CreatesNewUsers
{
    public function create(array $input): User
    {
        Validator::make($input, [
            'name'             => ['required', 'string', 'max:100'],
            'apellidos'        => ['required', 'string', 'max:100'],
            'tipo_documento'   => ['required', Rule::in(['DNI', 'Pasaporte', 'CE'])],
            'numero_documento' => ['required', 'string', 'max:20', Rule::unique(User::class)],
            'email'            => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'telefono'         => ['nullable', 'string', 'max:20'],
            'password'         => ['required', 'string', Password::min(8)],
            'terminos'         => ['accepted'],
        ], [], [
            // Nombres en español de los campos para los mensajes de error
            'name'             => 'nombres',
            'tipo_documento'   => 'tipo de documento',
            'numero_documento' => 'número de documento',
            'telefono'         => 'teléfono',
            'password'         => 'contraseña',
            'terminos'         => 'términos y condiciones',
        ])->validate();

        $usuario = User::create([
            'name'             => $input['name'],
            'apellidos'        => $input['apellidos'],
            'tipo_documento'   => $input['tipo_documento'],
            'numero_documento' => $input['numero_documento'],
            'email'            => $input['email'],
            'telefono'         => $input['telefono'] ?? null,
            'password'         => $input['password'], // el modelo User la cifra con el cast 'hashed'
        ]);

        // Todo el que se registra desde la web es huésped (D5, RN13)
        $usuario->assignRole('huesped');

        return $usuario;
    }
}
