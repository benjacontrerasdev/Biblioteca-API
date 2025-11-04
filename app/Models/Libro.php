<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Libro extends Model
{

    use HasFactory;
    
    //
    protected $fillable = [
        'titulo',
        'autor',
        'isbn',
        'ejemplares_disponibles',
        'ejemplares_totales',
    ];
}
