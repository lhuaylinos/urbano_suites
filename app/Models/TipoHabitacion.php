<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('tipos_habitacion')]
#[Fillable(['nombre', 'descripcion', 'capacidad', 'tamano_m2', 'precio_base', 'imagen', 'activo'])]
class TipoHabitacion extends Model
{
    protected function casts(): array
    {
        return [
            'capacidad'   => 'integer',
            'tamano_m2'   => 'decimal:2',
            'precio_base' => 'decimal:2',
            'activo'      => 'boolean',
        ];
    }

    public function habitaciones(): HasMany
    {
        return $this->hasMany(Habitacion::class, 'tipo_habitacion_id');
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'tipo_habitacion_id');
    }

    public function tarifas(): HasMany
    {
        return $this->hasMany(Tarifa::class, 'tipo_habitacion_id');
    }

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    }

    public function tarifaVigente(string $fechaIngreso): ?Tarifa
    {
        return $this->tarifas()
            ->where('activo', true)
            ->where('fecha_inicio', '<=', $fechaIngreso)
            ->where('fecha_fin', '>=', $fechaIngreso)
            ->orderByDesc('fecha_inicio')
            ->first();
    }

    public function precioPara(string $fechaIngreso): float
    {
        return (float) ($this->tarifaVigente($fechaIngreso)?->precio_noche ?? $this->precio_base);
    }

    // dos rangos se cruzan cuando entrada_a < salida_b y salida_a > entrada_b
    public function habitacionesLibres(string $entrada, string $salida): int
    {
        $total = $this->habitaciones()
            ->where('activo', true)
            ->where('estado', '!=', 'Mantenimiento')
            ->count();

        $reservadas = $this->reservas()
            ->whereIn('estado', ['Pendiente', 'Confirmada', 'En curso'])
            ->where('fecha_entrada', '<', $salida)
            ->where('fecha_salida', '>', $entrada)
            ->count();

        return max(0, $total - $reservadas);
    }
}
