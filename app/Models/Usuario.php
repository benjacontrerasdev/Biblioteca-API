<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{

    use HasFactory;

    //

    protected $fillable = [
        'nombre',
        'apellido',
        'codigo_estudiante',
        'email',
    ];

    /**
     * Define la relación para TODOS los préstamos del usuario.
     */
    public function prestamos()
    {
        return $this->hasMany(Prestamo::class);
    }

    /**
     * Define una relación que SOLO incluye préstamos activos (no devueltos).
     * ESTA ES LA NUEVA RELACIÓN QUE NECESITAMOS.
     */
    public function prestamosActivos()
    {
        return $this->hasMany(Prestamo::class)->whereNull('fecha_devolucion_real');
    }
}
