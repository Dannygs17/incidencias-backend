<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
public function run(): void
{
    $categorias = [
        ['nombre' => 'Via Pública', 'icono' => 'add_road'],
        ['nombre' => 'Drenaje y Alcantarillado', 'icono' => 'water_damage'],
        ['nombre' => 'Recolección de Basura', 'icono' => 'delete'],
        ['nombre' => 'Protección Civil', 'icono' => 'shield_with_heart'],
        ['nombre' => 'Medio Ambiente', 'icono' => 'eco'],
        ['nombre' => 'Parques', 'icono' => 'forest'],
    ];

    foreach ($categorias as $cat) {
        \App\Models\Categoria::updateOrCreate(
            ['nombre' => $cat['nombre']],
            ['icono' => $cat['icono']]
        );
    }
}
}