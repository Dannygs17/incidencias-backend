<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    use HasFactory;

    // Indicamos el nombre de la tabla (opcional si sigue la convención, pero mejor asegurar)
    protected $table = 'incidencias';

    // Estos son los campos que permitimos que Ionic guarde en la BD
    protected $fillable = [
        'user_id',
        'categoria',
        'descripcion',
        'imagen_path',
        'latitud',
        'longitud',
        'estado'
    ];

    // Relación con el usuario (para saber quién hizo el reporte)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}