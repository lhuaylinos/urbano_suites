<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['reserva_id', 'servicio_adicional_id', 'cantidad', 'precio_unitario'])]
class Consumo extends Model
{
    protected function casts(): array
    {
        return [
            'cantidad'        => 'integer',
            'precio_unitario' => 'decimal:2',
        ];
    }

    // --- relaciones ---
    public function reserva(): BelongsTo
    {
        return $this->belongsTo(Reserva::class);
    }

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(ServicioAdicional::class, 'servicio_adicional_id');
    }
}
