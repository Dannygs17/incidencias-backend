<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    // Mostrar lista de categorías (Panel Web)
    public function index()
    {
        // NUEVO: Usamos withCount para traer los contadores de forma optimizada
        $categorias = Categoria::withCount(['incidenciasPendientes', 'incidenciasEnProceso'])->get();
        return view('admin.categorias', compact('categorias'));
    }

    // Guardar nueva categoría (Panel Web)
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:categorias,nombre',
            'icono'  => 'required|string'
        ]);
        
        Categoria::create($request->all());
        return redirect()->back()->with('success', 'Categoría creada correctamente');
    }

    // Actualizar categoría existente (Panel Web)
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|unique:categorias,nombre,'.$id,
            'icono'  => 'required|string'
        ]);
        
        $categoria = Categoria::findOrFail($id);
        $categoria->update($request->all());
        return redirect()->back()->with('success', 'Categoría actualizada');
    }

    // Eliminar categoría (Panel Web)
    public function destroy($id)
    {
        try {
            Categoria::destroy($id);
            return redirect()->back()->with('success', 'Categoría eliminada');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se puede eliminar porque tiene reportes asociados');
        }
    }

    // ==========================================
    // RUTAS PARA LA APP MÓVIL (API - IONIC)
    // ==========================================
    
    public function getCategoriasApi()
    {
        // Traemos todas las categorías activas y las enviamos en JSON
        $categorias = Categoria::all();
        return response()->json($categorias, 200);
    }
}