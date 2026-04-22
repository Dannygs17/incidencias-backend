<?php

namespace App\Http\Controllers;

use App\Models\Incidencia;
use App\Models\Categoria; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// --- NUEVAS IMPORTACIONES PARA EL CORREO ---
use App\Mail\ReporteResuelto;
use Illuminate\Support\Facades\Mail;

// --- NUEVAS IMPORTACIONES PARA FIREBASE (PUSH NOTIFICATIONS) ---
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;

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
        // NUEVO: Contamos por separado pendientes y en proceso
        $categorias = Categoria::withCount([
            'incidenciasPendientes as incidencias_pendientes_count', 
            'incidenciasEnProceso as incidencias_en_proceso_count'
        ])->where('activa', true)->get();

        return view('admin.incidencias', compact('categorias'));
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

        return view('admin.tabla_incidencias', compact('incidencias', 'categoria', 'estadoActual', 'conteos'));
    }

    // ACTUALIZADO: Maneja la subida de foto de evidencia, el comentario y ENVÍA EL CORREO + PUSH NOTIFICATION
    public function actualizarEstado(Request $request, $id)
    {
        $request->validate([
            'nuevo_estado'     => 'required|in:en proceso,resuelto',
            'comentario_admin' => 'nullable|string|max:1000',
            'evidencia'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // Máx 5MB
        ]);

        $incidencia = Incidencia::with('user')->findOrFail($id);
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

        // === ENVIAR NOTIFICACIÓN PUSH A FIREBASE (INDIFERENTE DEL ESTADO) ===
        $usuario = $incidencia->user;
        
        if ($usuario && $usuario->fcm_token) {
            try {
                // Instanciar Firebase con la llave maestra
                $firebase = (new Factory)
                    ->withServiceAccount(storage_path('app/firebase-credentials.json'));
                
                $messaging = $firebase->createMessaging();

                // Construir el título y mensaje dependiendo del estatus
                $titulo = ($incidencia->estado === 'resuelto') ? '¡Reporte Resuelto! ✅' : 'Reporte en Proceso 🚧';
                $cuerpo = 'Tu reporte de la categoría "' . ($incidencia->categoria->nombre ?? 'Incidencia') . '" ha cambiado a estado: ' . strtoupper($incidencia->estado) . '.';
                
                if ($incidencia->comentario_admin) {
                    $cuerpo .= ' Comentario: ' . $incidencia->comentario_admin;
                }

                // Preparamos el mensaje usando el formato de array (Compatible con v8+)
                $mensaje = CloudMessage::fromArray([
                    'token' => $usuario->fcm_token,
                    'notification' => [
                        'title' => $titulo,
                        'body'  => $cuerpo,
                    ],
                ]);

                // Enviamos el mensaje
                $messaging->send($mensaje);
                Log::info('Notificación Push enviada a: ' . $usuario->email);
            } catch (\Exception $e) {
                Log::error('Error enviando notificación Push: ' . $e->getMessage());
            }
        }

        // === ENVIAR CORREO SI EL ESTADO ES RESUELTO ===
        if ($incidencia->estado === 'resuelto') {
            try {
                 Mail::to($usuario->email)->send(new ReporteResuelto($incidencia));
                 return back()->with('success', 'Reporte actualizado, ciudadano notificado por Push y Correo.');
            } catch (\Exception $e) {
                 Log::error('Error enviando correo: ' . $e->getMessage());
                 return back()->with('success', 'Reporte actualizado y notificación Push enviada (falló el correo).');
            }
        }

        return back()->with('success', 'El estado del reporte ha sido actualizado y se envió la notificación Push.');
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

        return view('admin.estadisticas', compact(
            'totalReportes', 
            'resueltos',
            'casosActivos',
            'categoriasEstadisticas', 
            'coordenadas'
        ));
    }
}