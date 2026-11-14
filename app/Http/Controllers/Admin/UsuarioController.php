<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Throwable;

class UsuarioController extends Controller
{
    // --- roles que se pueden asignar ---
    private const ROLES = [
        'huesped'       => 'Huésped',
        'recepcionista' => 'Recepcionista',
        'limpieza'      => 'Personal de limpieza',
        'administrador' => 'Administrador',
    ];

    // --- listado (orden y paginacion) ---
    public function index()
    {
        try {
            $usuarios = User::with('roles')->orderBy('name')->paginate(10);
        } catch (Throwable $e) {
            return redirect()->route('admin.panel')
                ->with('error', 'No se pudo cargar la lista de usuarios');
        }

        return view('admin.usuarios.index', ['usuarios' => $usuarios, 'roles' => self::ROLES]);
    }

    // --- edicion: formulario ---
    public function edit(User $usuario)
    {
        return view('admin.usuarios.edit', ['usuario' => $usuario, 'roles' => self::ROLES]);
    }

    // --- edicion: guardar rol y estado ---
    public function update(Request $request, User $usuario)
    {
        $datos = $request->validate([
            'rol' => ['required', Rule::in(array_keys(self::ROLES))],
        ]);
        $activo = $request->boolean('activo');

        // el admin no puede quitarse su rol ni desactivarse a si mismo
        if ($usuario->is($request->user()) && ($datos['rol'] !== 'administrador' || ! $activo)) {
            return back()->withInput()
                ->with('error', 'No puedes quitarte el rol de administrador ni desactivar tu propia cuenta');
        }

        try {
            $usuario->update(['activo' => $activo]);
            $usuario->syncRoles($datos['rol']);
        } catch (Throwable $e) {
            return back()->withInput()
                ->with('error', 'No se pudo actualizar el usuario, intenta de nuevo');
        }

        return redirect()->route('admin.usuarios.index')
            ->with('exito', 'Usuario actualizado correctamente');
    }
}
