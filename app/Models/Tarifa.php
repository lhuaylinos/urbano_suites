<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['tipo_habitacion_id', 'temporada', 'fecha_inicio', 'fecha_fin', 'precio_noche', 'activo'])]
class Tarifa extends Model
{
    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin'    => 'date',
            'precio_noche' => 'decimal:2',
            'activo'       => 'boolean',
        ];
    }

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(TipoHabitacion::class, 'tipo_habitacion_id');
    }

    public function estaVigente(): bool
    {
        return $this->activo && now()->between($this->fecha_inicio->startOfDay(), $this->fecha_fin->endOfDay());
    }
}
