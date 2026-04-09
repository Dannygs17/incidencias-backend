<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    use HasFactory;

    protected $table = 'incidencias';

    // Actualizamos los campos permitidos
    protected $fillable = [
        'user_id',
        'categoria_id', // Cambiado de 'categoria' a 'categoria_id'
        'descripcion',
        'imagen_path',
        'latitud',
        'longitud',
        'estado'
    ];

    // Relación con el usuario (quién reportó)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // NUEVA RELACIÓN: Para saber a qué categoría pertenece el reporte
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}