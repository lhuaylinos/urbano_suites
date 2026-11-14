<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    public function create(array $input): User
    {
        // --- reglas del registro ---
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
            'name'             => 'nombres',
            'tipo_documento'   => 'tipo de documento',
            'numero_documento' => 'número de documento',
            'telefono'         => 'teléfono',
            'password'         => 'contraseña',
            'terminos'         => 'términos y condiciones',
        ])->validate();

        // --- guardado del usuario ---
        $usuario = User::create([
            'name'             => $input['name'],
            'apellidos'        => $input['apellidos'],
            'tipo_documento'   => $input['tipo_documento'],
            'numero_documento' => $input['numero_documento'],
            'email'            => $input['email'],
            'telefono'         => $input['telefono'] ?? null,
            'password'         => $input['password'], // el modelo User cifra la contrasena con el cast hashed
        ]);

        // todo el que se registra desde la web es huesped
        $usuario->assignRole('huesped');

        return $usuario;
    }
}
