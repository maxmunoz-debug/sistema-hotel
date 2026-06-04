<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Habitacion extends Model
{
    // Le decimos a Laravel que use el nombre de tabla correcto
    protected $table = 'habitaciones';

    // Campos permitidos para llenar
    protected $fillable = ['tipo_habitacion_id', 'numero', 'precio', 'imagen'];

    // Relación inversa: Una Habitación PERTENECE A un Tipo de Habitación
    public function tipoHabitacion(): BelongsTo
    {
        return $this->belongsTo(TipoHabitacion::class, 'tipo_habitacion_id');
    }
}