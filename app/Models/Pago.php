<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['reserva_id', 'concepto', 'monto', 'metodo', 'referencia', 'estado'])]
class Pago extends Model
{
    protected function casts(): array
    {
        return ['monto' => 'decimal:2'];
    }

    public function reserva(): BelongsTo
    {
        return $this->belongsTo(Reserva::class);
    }
}
