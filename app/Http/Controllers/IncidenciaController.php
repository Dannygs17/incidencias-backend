<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\Categoria; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IncidenciaController extends Controller
{
    // ==========================================
    // RUTAS PARA LA APP MÓVIL (API - IONIC)
    // ==========================================

    public function store(Request $request)
    {
        $request->validate([
            'categoria_id' => 'required|exists:categorias,id', 
            'descripcion'  => 'nullable|string',
            'imagen'       => 'nullable|string', 
            'latitud'      => 'required|numeric',
            'longitud'     => 'required|numeric',
        ]);

        $path = null;
        if ($request->imagen) {
            $img = $request->imagen;
            
            // SOLUCIÓN: Separamos dinámicamente cualquier prefijo (jpeg, png, etc.)
            // Todo lo que esté después de la primera coma (,) es la imagen real en base64
            if (strpos($img, ',') !== false) {
                $img = explode(',', $img)[1];
            }
            
            $img = str_replace(' ', '+', $img);
            
            // Guardamos la imagen
            $nombreFoto = 'incidencia_' . time() . '.jpg';
            Storage::disk('public')->put('incidencias/' . $nombreFoto, base64_decode($img));
            $path = 'incidencias/' . $nombreFoto;
        }

        $incidencia = Incidencia::create([
            'user_id'      => auth()->id(), 
            'categoria_id' => $request->categoria_id, 
            'descripcion'  => $request->descripcion,
            'imagen_path'  => $path,
            'latitud'      => $request->latitud,
            'longitud'     => $request->longitud,
            'estado'       => 'pendiente'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Reporte creado correctamente',
            'data' => $incidencia
        ], 201);
    }

    public function misReportes()
    {
        // Cargamos la relación 'categoria' para que Ionic sepa el nombre y el icono
        $reportes = Incidencia::with('categoria')
                              ->where('user_id', auth()->id())
                              ->orderBy('created_at', 'desc')
                              ->get();

        $reportes->transform(function ($reporte) {
            $reporte->imagen_url = $reporte->imagen_path ? asset('storage/' . $reporte->imagen_path) : null;
            return $reporte;
        });

        return response()->json($reportes);
    }

    // ==========================================
    // RUTAS PARA EL PANEL DE ADMINISTRACIÓN (WEB)
    // ==========================================

    public function dashboardAdmin()
    {
        // Traemos todas las categorías activas y contamos sus incidencias PENDIENTES
        $categorias = Categoria::withCount(['incidencias' => function($query) {
            $query->where('estado', 'pendiente');
        }])->where('activa', true)->get();

        return view('Admin.incidencias', compact('categorias'));
    }

    public function tablaIncidencias(Request $request, $categoria_id)
    {
        $categoria = Categoria::findOrFail($categoria_id);
        $estadoActual = $request->query('estado', 'pendiente');

        $incidencias = Incidencia::with('user')
            ->where('categoria_id', $categoria_id)
            ->where('estado', $estadoActual)
            ->orderBy('created_at', 'desc')
            ->get();

        $conteos = [
            'pendiente'  => Incidencia::where('categoria_id', $categoria_id)->where('estado', 'pendiente')->count(),
            'en_proceso' => Incidencia::where('categoria_id', $categoria_id)->where('estado', 'en proceso')->count(),
            'resuelto'   => Incidencia::where('categoria_id', $categoria_id)->where('estado', 'resuelto')->count(),
        ];

        return view('Admin.tabla_incidencias', compact('incidencias', 'categoria', 'estadoActual', 'conteos'));
    }

    public function actualizarEstado(Request $request, $id)
    {
        $request->validate([
            'nuevo_estado' => 'required|in:en proceso,resuelto'
        ]);

        $incidencia = Incidencia::findOrFail($id);
        $incidencia->estado = $request->nuevo_estado;
        $incidencia->save();

        return back()->with('success', 'El estado del reporte ha sido actualizado correctamente.');
    }

    public function mostrarEstadisticas()
    {
        $totalReportes = Incidencia::count();
        $totalResueltos = Incidencia::where('estado', 'resuelto')->count();
        $porcentajeResueltos = $totalReportes > 0 ? round(($totalResueltos / $totalReportes) * 100) : 0;

        $categorias = Categoria::withCount('incidencias')->get();
        $incidenciasPorTipo = [];
        foreach($categorias as $cat) {
            $incidenciasPorTipo[$cat->nombre] = $cat->incidencias_count;
        }

        $coordenadas = Incidencia::select('latitud', 'longitud')->get();

        return view('Admin.estadisticas', compact(
            'totalReportes', 
            'porcentajeResueltos', 
            'incidenciasPorTipo', 
            'coordenadas'
        ));
    }
}