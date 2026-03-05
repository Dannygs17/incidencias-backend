<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IncidenciaController extends Controller
{
    // RUTAS PARA LA APP MÓVIL (API - IONIC)

    //  Recibir y guardar un nuevo reporte desde Ionic
    public function store(Request $request)
    {
        $request->validate([
            'categoria'   => 'required|in:agua,alumbrado,bacheo,basura',
            'descripcion' => 'nullable|string',
            'imagen'      => 'nullable|string', 
            'latitud'     => 'required|numeric',
            'longitud'    => 'required|numeric',
        ]);

        $path = null;

        if ($request->imagen) {
            $img = $request->imagen;
            $img = str_replace('data:image/jpeg;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            
            $nombreFoto = 'incidencia_' . time() . '.jpg';
            Storage::disk('public')->put('incidencias/' . $nombreFoto, base64_decode($img));
            $path = 'incidencias/' . $nombreFoto;
        }

        $incidencia = Incidencia::create([
            'user_id'     => auth()->id() ?? 1, 
            'categoria'   => $request->categoria,
            'descripcion' => $request->descripcion,
            'imagen_path' => $path,
            'latitud'     => $request->latitud,
            'longitud'    => $request->longitud,
            'estado'      => 'pendiente'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Reporte creado correctamente',
            'data' => $incidencia
        ], 201);
    }

    // Devolver los reportes del usuario logueado en Ionic
    public function misReportes()
    {
        $reportes = Incidencia::where('user_id', auth()->id())
                              ->orderBy('created_at', 'desc')
                              ->get();

        $reportes->transform(function ($reporte) {
            if ($reporte->imagen_path) {
                $reporte->imagen_url = asset('storage/' . $reporte->imagen_path);
            } else {
                $reporte->imagen_url = null;
            }
            return $reporte;
        });

        return response()->json($reportes);
    }


    // RUTAS PARA EL PANEL DE ADMINISTRACIÓN (WEB - LARAVEL BREEZE)

    // Mostrar el Dashboard principal con los cuadros de categorías (Solo cuenta pendientes)
    public function dashboardAdmin()
    {
        // Se agregó ->where('estado', 'pendiente') a cada consulta
        $conteos = [
            'agua'      => Incidencia::where('categoria', 'agua')->where('estado', 'pendiente')->count(),
            'alumbrado' => Incidencia::where('categoria', 'alumbrado')->where('estado', 'pendiente')->count(),
            'bacheo'    => Incidencia::where('categoria', 'bacheo')->where('estado', 'pendiente')->count(),
            'basura'    => Incidencia::where('categoria', 'basura')->where('estado', 'pendiente')->count(),
        ];

        return view('Admin.incidencias', compact('conteos'));
    }

    // Mostrar la tabla detallada filtrando por categoría y estado (Pendiente, En Proceso, Resuelto)
    public function tablaIncidencias(Request $request, $categoria)
    {
        // Vemos qué pestaña (estado) quiere ver el admin (por defecto 'pendiente')
        $estadoActual = $request->query('estado', 'pendiente');

        // Traemos las incidencias de esa categoría Y ese estado, incluyendo el usuario
        $incidencias = Incidencia::with('user')
            ->where('categoria', $categoria)
            ->where('estado', $estadoActual)
            ->orderBy('created_at', 'desc')
            ->get();

        // Contamos cuántas hay de cada estado para pintar los números en las pestañas
        $conteos = [
            'pendiente'  => Incidencia::where('categoria', $categoria)->where('estado', 'pendiente')->count(),
            'en_proceso' => Incidencia::where('categoria', $categoria)->where('estado', 'en proceso')->count(),
            'resuelto'   => Incidencia::where('categoria', $categoria)->where('estado', 'resuelto')->count(),
        ];

        return view('Admin.tabla_incidencias', compact('incidencias', 'categoria', 'estadoActual', 'conteos'));
    }

    // Función que ejecuta el Modal para cambiar el estado (Pendiente -> En Proceso -> Resuelto)
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

    // Mostrar la vista de Estadísticas (Gráficos, etc)
    public function mostrarEstadisticas()
    {

    // 1. Calculamos los cuadros superiores (KPIs)
        $totalReportes = Incidencia::count();
        $totalResueltos = Incidencia::where('estado', 'resuelto')->count();
        
        // Calculamos el porcentaje de resueltos (evitando dividir por cero)
        $porcentajeResueltos = $totalReportes > 0 
            ? round(($totalResueltos / $totalReportes) * 100) 
            : 0;

        // 2. Datos para la gráfica de barras (Incidencias por Tipo)
        $incidenciasPorTipo = [
            'Agua' => Incidencia::where('categoria', 'agua')->count(),
            'Alumbrado' => Incidencia::where('categoria', 'alumbrado')->count(),
            'Baches' => Incidencia::where('categoria', 'bacheo')->count(),
            'Limpieza' => Incidencia::where('categoria', 'basura')->count(),
        ];

        // 3. Datos para el Mapa de Calor (Necesitamos latitud y longitud de todos los reportes)
        // Solo traemos latitud y longitud para no cargar tanta info innecesaria
        $coordenadas = Incidencia::select('latitud', 'longitud')->get();

        return view('Admin.estadisticas', compact(
            'totalReportes', 
            'porcentajeResueltos', 
            'incidenciasPorTipo', 
            'coordenadas'
        ));
        
    }
}