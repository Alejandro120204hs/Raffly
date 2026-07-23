<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rifa extends Model
{
    
    public function numeros()
    {
        return $this->hasMany(NumeroRifa::class);
    }

    protected $fillable = [
        // Definición de los atributos que se pueden asignar masivamente
        'nombre',
        'tipo',
        'premio',
        'cifras',
        'precio',
        'loteria',
        'juega',
        'fecha',
        'estado',
        'resultado',
    ];
}
