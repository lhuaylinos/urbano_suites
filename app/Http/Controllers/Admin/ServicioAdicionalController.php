<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServicioAdicional;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Throwable;

class ServicioAdicionalController extends Controller
{
    // Reglas de validación: se usan igual al crear y al editar
    private function reglas(?ServicioAdicional $servicio = null): array
    {
        return [
            'nombre'      => ['required', 'string', 'max:100', Rule::unique('servicios_adicionales', 'nombre')->ignore($servicio?->id)],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'precio'      => ['required', 'numeric', 'min:1', 'max:99999'],
            'nota'        => ['nullable', 'string', 'max:255'],
            'imagen'      => ['nullable', 'string', 'max:255'],
        ];
    }

    public function index()
    {
        try {
            $servicios = ServicioAdicional::orderBy('nombre')->paginate(10);
        } catch (Throwable $e) {
            return redirect()->route('admin.panel')
                ->with('error', 'No se pudo cargar la lista de servicios adicionales');
        }

        return view('admin.servicios.index', compact('servicios'));
    }

    public function create()
    {
        return view('admin.servicios.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate($this->reglas());
        $datos['activo'] = $request->boolean('activo');

        try {
            ServicioAdicional::create($datos);
        } catch (Throwable $e) {
            return back()->withInput()
                ->with('error', 'No se pudo guardar el servicio, intenta de nuevo');
        }

        return redirect()->route('admin.servicios-adicionales.index')
            ->with('exito', 'Servicio adicional creado correctamente');
    }

    public function edit(ServicioAdicional $servicio)
    {
        return view('admin.servicios.edit', compact('servicio'));
    }

    public function update(Request $request, ServicioAdicional $servicio)
    {
        $datos = $request->validate($this->reglas($servicio));
        $datos['activo'] = $request->boolean('activo');

        try {
            $servicio->update($datos);
        } catch (Throwable $e) {
            return back()->withInput()
                ->with('error', 'No se pudo actualizar el servicio, intenta de nuevo');
        }

        return redirect()->route('admin.servicios-adicionales.index')
            ->with('exito', 'Servicio adicional actualizado correctamente');
    }

    // "Eliminar" = desactivar (activo = false). Los consumos ya registrados no se tocan.
    public function destroy(ServicioAdicional $servicio)
    {
        try {
            $servicio->update(['activo' => false]);
        } catch (Throwable $e) {
            return back()->with('error', 'No se pudo desactivar el servicio');
        }

        return redirect()->route('admin.servicios-adicionales.index')
            ->with('exito', 'Servicio adicional desactivado');
    }
}
