<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('habitaciones')]
#[Fillable(['numero', 'piso', 'tipo_habitacion_id', 'estado', 'activo'])]
class Habitacion extends Model
{
    // Estados posibles de una habitación (ver guía maestra)
    public const ESTADOS = ['Disponible', 'Ocupada', 'Limpieza', 'Mantenimiento'];

    protected function casts(): array
    {
        return [
            'piso'   => 'integer',
            'activo' => 'boolean',
        ];
    }

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(TipoHabitacion::class, 'tipo_habitacion_id');
    }

    public function scopeActivas(Builder $query): Builder
    {
        return $query->where('activo', true);
    }
}
