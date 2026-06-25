<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rifa extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'imagen',
        'precio_numero',
        'total_numeros',
        'numeros_vendidos',
        'estado',
        'premio_descripcion',
        'monto_premio',
        'fecha_sorteo',
        'numero_ganador',
    ];

    protected $casts = [
        'fecha_sorteo'  => 'datetime',
        'precio_numero' => 'decimal:2',
        'monto_premio'  => 'decimal:2',
    ];

    public function participaciones(): HasMany
    {
        return $this->hasMany(Participacion::class);
    }

    public function getNumerosDisponiblesAttribute(): int
    {
        return max(0, $this->total_numeros - $this->numeros_vendidos);
    }

    public function getPorcentajeVendidoAttribute(): float
    {
        if ($this->total_numeros <= 0) {
            return 0;
        }
        return round(($this->numeros_vendidos / $this->total_numeros) * 100, 1);
    }
}
