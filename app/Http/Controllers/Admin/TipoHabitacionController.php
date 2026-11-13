<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TipoHabitacion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Throwable;

class TipoHabitacionController extends Controller
{
    // Reglas de validación: se usan igual al crear y al editar
    private function reglas(?TipoHabitacion $tipo = null): array
    {
        return [
            'nombre'      => ['required', 'string', 'max:100', Rule::unique('tipos_habitacion', 'nombre')->ignore($tipo?->id)],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'capacidad'   => ['required', 'integer', 'min:1', 'max:10'],
            'tamano_m2'   => ['nullable', 'numeric', 'min:1', 'max:999'],
            'precio_base' => ['required', 'numeric', 'min:1', 'max:99999'],
            'imagen'      => ['nullable', 'string', 'max:255'],
        ];
    }

    // Nombres bonitos para los mensajes de error
    private function nombres(): array
    {
        return [
            'tamano_m2'   => 'tamaño',
            'precio_base' => 'precio base',
        ];
    }

    public function index()
    {
        try {
            $tipos = TipoHabitacion::orderBy('nombre')->paginate(10);
        } catch (Throwable $e) {
            return redirect()->route('admin.panel')
                ->with('error', 'No se pudo cargar la lista de tipos de habitación');
        }

        return view('admin.tipos.index', compact('tipos'));
    }

    public function create()
    {
        return view('admin.tipos.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate($this->reglas(), [], $this->nombres());
        $datos['activo'] = $request->boolean('activo');

        try {
            TipoHabitacion::create($datos);
        } catch (Throwable $e) {
            return back()->withInput()
                ->with('error', 'No se pudo guardar el tipo de habitación, intenta de nuevo');
        }

        return redirect()->route('admin.tipos-habitacion.index')
            ->with('exito', 'Tipo de habitación creado correctamente');
    }

    public function edit(TipoHabitacion $tipo)
    {
        return view('admin.tipos.edit', compact('tipo'));
    }

    public function update(Request $request, TipoHabitacion $tipo)
    {
        $datos = $request->validate($this->reglas($tipo), [], $this->nombres());
        $datos['activo'] = $request->boolean('activo');

        try {
            $tipo->update($datos);
        } catch (Throwable $e) {
            return back()->withInput()
                ->with('error', 'No se pudo actualizar el tipo de habitación, intenta de nuevo');
        }

        return redirect()->route('admin.tipos-habitacion.index')
            ->with('exito', 'Tipo de habitación actualizado correctamente');
    }

    // "Eliminar" = desactivar (activo = false). No se borra el registro.
    public function destroy(TipoHabitacion $tipo)
    {
        try {
            if ($tipo->habitaciones()->where('activo', true)->exists()) {
                return back()->with('error', 'No se puede desactivar: todavía tiene habitaciones activas');
            }

            $tipo->update(['activo' => false]);
        } catch (Throwable $e) {
            return back()->with('error', 'No se pudo desactivar el tipo de habitación');
        }

        return redirect()->route('admin.tipos-habitacion.index')
            ->with('exito', 'Tipo de habitación desactivado');
    }
}
