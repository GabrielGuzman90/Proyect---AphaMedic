<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicamento extends Model
{
    use HasFactory;

    protected $table = 'medicamentos';

    protected $fillable = [
        'nombre',
        'presentacion',
        'cantidad',
        'mg',
        'image_path',
        'categoria',
        'guardado',
        'disponibilidad'
    ];
}