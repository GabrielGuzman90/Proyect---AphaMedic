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
    'mg',
    'image_path',       // Para mostrar imagen en el carrito
    'categoria',
    'guardado',
    'disponibilidad',
    'institucion'       // <-- agregar si quieres guardar la institución directamente en MySQL
    ];
}