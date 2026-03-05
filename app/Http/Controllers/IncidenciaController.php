<?php
namespace App\Http\Controllers;

use App\Models\Incidencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IncidenciaController extends Controller
{
    // Esta es la función que ya tienes para recibir datos de Ionic
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

    // --- FUNCIÓN PARA EL ADMIN (Boceto web) ---
    public function dashboardAdmin()
    {
        $conteos = [
            'agua'      => Incidencia::where('categoria', 'agua')->count(),
            'alumbrado' => Incidencia::where('categoria', 'alumbrado')->count(),
            'bacheo'    => Incidencia::where('categoria', 'bacheo')->count(),
            'basura'    => Incidencia::where('categoria', 'basura')->count(),
        ];

        return view('Admin.incidencias', compact('conteos'));
        
    }

    // NUEVA FUNCIÓN (para la tabla detallada)
public function tablaIncidencias($categoria)
{
    // Obtenemos las incidencias filtradas por la categoría que viene en la URL
    $incidencias = Incidencia::where('categoria', $categoria)->get();

    return view('Admin.tabla_incidencias', compact('incidencias', 'categoria'));
}

//Funcion para mostrar vista incidencias 

public function mostrarEstadisticas()
{
    return view('Admin.estadisticas');
}








    ///Esto es ionic 

    // --- NUEVA FUNCIÓN PARA LA APP MÓVIL (Mis Reportes) ---
    public function misReportes()
    {
        // 1. Buscamos las incidencias que le pertenecen a este usuario logueado
        $reportes = Incidencia::where('user_id', auth()->id())
                              ->orderBy('created_at', 'desc') // Las más nuevas primero
                              ->get();

        // 2. Transformamos los datos para crear una URL completa para la imagen
        $reportes->transform(function ($reporte) {
            if ($reporte->imagen_path) {
                // Genera el link completo (ej. http://localhost:8000/storage/incidencias/foto.jpg)
                $reporte->imagen_url = asset('storage/' . $reporte->imagen_path);
            } else {
                $reporte->imagen_url = null;
            }
            return $reporte;
        });

        // 3. Enviamos la respuesta a Ionic
        return response()->json($reportes);
    }
}