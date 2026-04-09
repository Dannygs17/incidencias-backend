<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
        'activa',
        'icono'
    ];

    // Relación: Una categoría tiene muchas incidencias
    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'categoria_id');
    }
}