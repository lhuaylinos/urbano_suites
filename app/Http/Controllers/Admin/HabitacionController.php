<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Habitacion;
use App\Models\TipoHabitacion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Throwable;

class HabitacionController extends Controller
{
    private function reglas(?Habitacion $habitacion = null): array
    {
        return [
            'numero'             => ['required', 'string', 'max:10', Rule::unique('habitaciones', 'numero')->ignore($habitacion?->id)],
            'piso'               => ['required', 'integer', 'min:1', 'max:20'],
            'tipo_habitacion_id' => ['required', 'exists:tipos_habitacion,id'],
            'estado'             => ['required', Rule::in(Habitacion::ESTADOS)],
        ];
    }

    private function nombres(): array
    {
        return [
            'numero'             => 'número',
            'tipo_habitacion_id' => 'tipo de habitación',
        ];
    }

    // Tipos que se muestran en el desplegable (los activos; al editar, también el que ya tenía)
    private function tiposParaElegir(?Habitacion $habitacion = null)
    {
        return TipoHabitacion::where('activo', true)
            ->when($habitacion, fn ($q) => $q->orWhere('id', $habitacion->tipo_habitacion_id))
            ->orderBy('nombre')
            ->pluck('nombre', 'id');
    }

    public function index()
    {
        try {
            $habitaciones = Habitacion::with('tipo')->orderBy('numero')->paginate(10);
        } catch (Throwable $e) {
            return redirect()->route('admin.panel')
                ->with('error', 'No se pudo cargar la lista de habitaciones');
        }

        return view('admin.habitaciones.index', compact('habitaciones'));
    }

    public function create()
    {
        $tipos = $this->tiposParaElegir();

        return view('admin.habitaciones.create', compact('tipos'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate($this->reglas(), [], $this->nombres());
        $datos['activo'] = $request->boolean('activo');

        try {
            Habitacion::create($datos);
        } catch (Throwable $e) {
            return back()->withInput()
                ->with('error', 'No se pudo guardar la habitación, intenta de nuevo');
        }

        return redirect()->route('admin.habitaciones.index')
            ->with('exito', 'Habitación creada correctamente');
    }

    public function edit(Habitacion $habitacion)
    {
        $tipos = $this->tiposParaElegir($habitacion);

        return view('admin.habitaciones.edit', compact('habitacion', 'tipos'));
    }

    public function update(Request $request, Habitacion $habitacion)
    {
        $datos = $request->validate($this->reglas($habitacion), [], $this->nombres());
        $datos['activo'] = $request->boolean('activo');

        try {
            $habitacion->update($datos);
        } catch (Throwable $e) {
            return back()->withInput()
                ->with('error', 'No se pudo actualizar la habitación, intenta de nuevo');
        }

        return redirect()->route('admin.habitaciones.index')
            ->with('exito', 'Habitación actualizada correctamente');
    }

    // "Eliminar" = desactivar (activo = false)
    public function destroy(Habitacion $habitacion)
    {
        try {
            if ($habitacion->estado === 'Ocupada') {
                return back()->with('error', 'No se puede desactivar una habitación que está ocupada');
            }

            $habitacion->update(['activo' => false]);
        } catch (Throwable $e) {
            return back()->with('error', 'No se pudo desactivar la habitación');
        }

        return redirect()->route('admin.habitaciones.index')
            ->with('exito', 'Habitación desactivada');
    }
}
