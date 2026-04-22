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

    // Relación original
    public function incidencias()
    {
        return $this->hasMany(Incidencia::class, 'categoria_id');
    }

    // NUEVO: Relación para contar solo pendientes
    public function incidenciasPendientes()
    {
        return $this->hasMany(Incidencia::class, 'categoria_id')->where('estado', 'pendiente');
    }

    // NUEVO: Relación para contar solo en proceso
    public function incidenciasEnProceso()
    {
        return $this->hasMany(Incidencia::class, 'categoria_id')->where('estado', 'en proceso');
    }
}