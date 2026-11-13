<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    }
}