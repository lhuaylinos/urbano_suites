<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Table('servicios_adicionales')]
#[Fillable(['nombre', 'descripcion', 'precio', 'nota', 'imagen', 'activo'])]
class ServicioAdicional extends Model
{
    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'activo' => 'boolean',
        ];
    }

    // --- filtros reutilizables ---
    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    }
}
