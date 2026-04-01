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
            
            if (strpos($img, ',') !== false) {
                $img = explode(',', $img)[1];
            }
            
            $img = str_replace(' ', '+', $img);
            
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
        // Cargamos la relación 'categoria'
        $reportes = Incidencia::with('categoria')
                              ->where('user_id', auth()->id())
                              ->orderBy('created_at', 'desc')
                              ->get();

        $reportes->transform(function ($reporte) {
            $reporte->imagen_url = $reporte->imagen_path ? asset('storage/' . $reporte->imagen_path) : null;
            
            // NUEVO: Enviamos la URL completa de la evidencia a Ionic
            $reporte->evidencia_url = $reporte->evidencia_path ? asset('storage/' . $reporte->evidencia_path) : null;
            
            return $reporte;
        });

        return response()->json($reportes);
    }

    // ==========================================
    // RUTAS PARA EL PANEL DE ADMINISTRACIÓN (WEB)
    // ==========================================

    public function dashboardAdmin()
    {
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

    // ACTUALIZADO: Maneja la subida de foto de evidencia y el comentario del admin
    public function actualizarEstado(Request $request, $id)
    {
        $request->validate([
            'nuevo_estado'     => 'required|in:en proceso,resuelto',
            'comentario_admin' => 'nullable|string|max:1000',
            'evidencia'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // Máx 5MB
        ]);

        $incidencia = Incidencia::findOrFail($id);
        $incidencia->estado = $request->nuevo_estado;

        // Guardar comentario si existe
        if ($request->has('comentario_admin')) {
            $incidencia->comentario_admin = $request->comentario_admin;
        }

        // Guardar foto de evidencia si existe
        if ($request->hasFile('evidencia')) {
            $nombreFoto = 'evidencia_' . time() . '.' . $request->file('evidencia')->getClientOriginalExtension();
            $path = $request->file('evidencia')->storeAs('evidencias', $nombreFoto, 'public');
            $incidencia->evidencia_path = $path;
        }

        $incidencia->save();

        return back()->with('success', 'El estado del reporte ha sido actualizado.');
    }


    public function mostrarEstadisticas()
    {
        // 1. Conteos básicos para las tarjetas (Puros números, sin porcentajes)
        $totalReportes = Incidencia::count();
        $resueltos = Incidencia::where('estado', 'resuelto')->count();
        $enProceso = Incidencia::where('estado', 'en proceso')->count();
        $pendientes = Incidencia::where('estado', 'pendiente')->count();
        $casosActivos = $pendientes + $enProceso;

        // 2. Gráfica de 3 barras POR CATEGORÍA
        // Contamos cuántas incidencias hay de cada estado dentro de cada categoría
        $categoriasEstadisticas = Categoria::withCount([
            'incidencias as pendientes_count' => function ($query) {
                $query->where('estado', 'pendiente');
            },
            'incidencias as proceso_count' => function ($query) {
                $query->where('estado', 'en proceso');
            },
            'incidencias as resueltos_count' => function ($query) {
                $query->where('estado', 'resuelto');
            }
        ])->where('activa', true)->get();

        // 3. Coordenadas para el mapa de calor
        $coordenadas = Incidencia::select('latitud', 'longitud')->get();

        return view('Admin.estadisticas', compact(
            'totalReportes', 
            'resueltos',
            'casosActivos',
            'categoriasEstadisticas', 
            'coordenadas'
        ));
    }


}