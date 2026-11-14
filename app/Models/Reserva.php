<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['codigo', 'user_id', 'tipo_habitacion_id', 'habitacion_id', 'fecha_entrada', 'fecha_salida',
            'huespedes', 'noches', 'precio_noche', 'total_estadia', 'estado'])]
class Reserva extends Model
{
    public const IGV = 0.18;

    protected function casts(): array
    {
        return [
            'fecha_entrada' => 'date',
            'fecha_salida'  => 'date',
            'precio_noche'  => 'decimal:2',
            'total_estadia' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(TipoHabitacion::class, 'tipo_habitacion_id');
    }

    public function habitacion(): BelongsTo
    {
        return $this->belongsTo(Habitacion::class);
    }

    public function consumos(): HasMany
    {
        return $this->hasMany(Consumo::class);
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }

    public function subtotalConsumos(): float
    {
        return (float) $this->consumos->sum(fn ($c) => $c->cantidad * $c->precio_unitario);
    }

    public static function montosEstadia(float $precioNoche, int $noches): array
    {
        $subtotal = round($precioNoche * $noches, 2);
        $igv = round($subtotal * self::IGV, 2);

        return ['subtotal' => $subtotal, 'igv' => $igv, 'total' => round($subtotal + $igv, 2)];
    }

    public function subtotalEstadia(): float
    {
        return round($this->precio_noche * $this->noches, 2);
    }

    public function igvEstadia(): float
    {
        return round($this->total_estadia - $this->subtotalEstadia(), 2);
    }

    // la columna codigo es unica: si dos reservan a la vez, uno repite el intento
    public static function generarCodigo(): string
    {
        $siguiente = ((int) static::max('id')) + 1;

        return 'RES-' . str_pad((string) $siguiente, 4, '0', STR_PAD_LEFT);
    }
}
