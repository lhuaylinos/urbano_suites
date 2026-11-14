<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tarifa;
use App\Models\TipoHabitacion;
use Illuminate\Http\Request;
use Throwable;

class TarifaController extends Controller
{
    private function reglas(): array
    {
        return [
            'tipo_habitacion_id' => ['required', 'exists:tipos_habitacion,id'],
            'temporada'          => ['required', 'string', 'max:100'],
            'fecha_inicio'       => ['required', 'date'],
            'fecha_fin'          => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'precio_noche'       => ['required', 'numeric', 'min:1', 'max:99999'],
        ];
    }

    private function nombres(): array
    {
        return [
            'tipo_habitacion_id' => 'tipo de habitación',
            'fecha_inicio'       => 'fecha de inicio',
            'fecha_fin'          => 'fecha de fin',
            'precio_noche'       => 'precio por noche',
        ];
    }

    private function tiposParaElegir(?Tarifa $tarifa = null)
    {
        return TipoHabitacion::where('activo', true)
            ->when($tarifa, fn ($q) => $q->orWhere('id', $tarifa->tipo_habitacion_id))
            ->orderBy('nombre')
            ->pluck('nombre', 'id');
    }

    // dos tarifas activas del mismo tipo no pueden cruzarse en fechas
    private function hayCruce(array $datos, bool $activa, ?Tarifa $tarifa = null): bool
    {
        if (! $activa) {
            return false;
        }

        return Tarifa::where('tipo_habitacion_id', $datos['tipo_habitacion_id'])
            ->where('activo', true)
            ->when($tarifa, fn ($q) => $q->where('id', '!=', $tarifa->id))
            ->where('fecha_inicio', '<=', $datos['fecha_fin'])
            ->where('fecha_fin', '>=', $datos['fecha_inicio'])
            ->exists();
    }

    public function index()
    {
        try {
            $tarifas = Tarifa::with('tipo')->orderByDesc('fecha_inicio')->paginate(10);
        } catch (Throwable $e) {
            return redirect()->route('admin.panel')
                ->with('error', 'No se pudo cargar la lista de tarifas');
        }

        return view('admin.tarifas.index', compact('tarifas'));
    }

    public function create()
    {
        $tipos = $this->tiposParaElegir();

        return view('admin.tarifas.create', compact('tipos'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate($this->reglas(), [], $this->nombres());
        $datos['activo'] = $request->boolean('activo');

        try {
            if ($this->hayCruce($datos, $datos['activo'])) {
                return back()->withInput()->withErrors([
                    'fecha_inicio' => 'Ya hay una tarifa activa de ese tipo que se cruza con esas fechas',
                ]);
            }

            Tarifa::create($datos);
        } catch (Throwable $e) {
            return back()->withInput()
                ->with('error', 'No se pudo guardar la tarifa, intenta de nuevo');
        }

        return redirect()->route('admin.tarifas.index')
            ->with('exito', 'Tarifa creada correctamente');
    }

    public function edit(Tarifa $tarifa)
    {
        $tipos = $this->tiposParaElegir($tarifa);

        return view('admin.tarifas.edit', compact('tarifa', 'tipos'));
    }

    public function update(Request $request, Tarifa $tarifa)
    {
        $datos = $request->validate($this->reglas(), [], $this->nombres());
        $datos['activo'] = $request->boolean('activo');

        try {
            if ($this->hayCruce($datos, $datos['activo'], $tarifa)) {
                return back()->withInput()->withErrors([
                    'fecha_inicio' => 'Ya hay una tarifa activa de ese tipo que se cruza con esas fechas',
                ]);
            }

            $tarifa->update($datos);
        } catch (Throwable $e) {
            return back()->withInput()
                ->with('error', 'No se pudo actualizar la tarifa, intenta de nuevo');
        }

        return redirect()->route('admin.tarifas.index')
            ->with('exito', 'Tarifa actualizada correctamente');
    }

    // las reservas ya hechas conservan su precio
    public function destroy(Tarifa $tarifa)
    {
        try {
            $tarifa->update(['activo' => false]);
        } catch (Throwable $e) {
            return back()->with('error', 'No se pudo desactivar la tarifa');
        }

        return redirect()->route('admin.tarifas.index')
            ->with('exito', 'Tarifa desactivada');
    }
}
