<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Participacion extends Model
{
    protected $table = 'participaciones';

    protected $fillable = [
        'rifa_id',
        'user_id',
        'nombre_participante',
        'numero',
        'estado',
    ];

    public function rifa(): BelongsTo
    {
        return $this->belongsTo(Rifa::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getNombreAttribute(): string
    {
        return $this->user?->name ?? $this->nombre_participante ?? 'Participante';
    }
}
