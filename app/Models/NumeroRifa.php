<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NumeroRifa extends Model
{
    protected $table = 'numeros_rifa';

    protected $fillable = [
        'rifa_id', 'numero', 'estado',
        'user_id', 'comprador_nombre', 'comprador_apellido',
        'comprador_ubicacion', 'comprador_celular',
    ];

    public function rifa()
    {
        return $this->belongsTo(Rifa::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}