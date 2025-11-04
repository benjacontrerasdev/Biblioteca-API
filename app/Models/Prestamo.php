<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prestamo extends Model
{
    use HasFactory;

    //

    protected $fillable = [
        'libro_id',
        'usuario_id',
        'empleado_id',
        'fecha_prestamo',
        'fecha_devolucion_estimada',
        'fecha_devolucion_real',
    ];

    public function libro()
    {
        return $this->belongsTo(Libro::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
