<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight capitalize flex items-center gap-2">
            <span class="material-symbols-outlined text-3xl text-indigo-400">{{ $categoria->icono }}</span>
            {{ __('Dashboard - ') . $categoria->nombre }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .leaflet-container { background: #1f2937; } 
    </style>

    <div class="py-12" x-data="{ openModal: false, openMap: false, reporteSeleccionado: {} }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-500/20 border border-green-500 text-green-100 rounded-lg shadow-sm font-medium flex items-center">
                    <span class="material-symbols-outlined mr-2">check_circle</span> {{ session('success') }}
                </div>
            @endif

            <div class="mb-6">
                <a href="{{ route('admin.incidencias') }}" class="flex items-center gap-2 px-4 py-1.5 border border-gray-700 rounded bg-gray-800 hover:bg-gray-700 text-gray-300 text-sm font-medium shadow-sm transition w-fit">
                    <span class="material-symbols-outlined text-sm">arrow_back</span> Volver al Dashboard
                </a>
            </div>

            <div class="bg-gray-900 border border-gray-800 rounded-lg shadow-sm overflow-hidden">
                
                <div class="flex border-b border-gray-800 bg-gray-900 overflow-x-auto">
                    <a href="{{ route('admin.tabla_incidencias', ['categoria' => $categoria->id, 'estado' => 'pendiente']) }}" 
                       class="px-6 py-3 border-r border-gray-800 min-w-max transition 
                              {{ $estadoActual === 'pendiente' ? 'bg-gray-800 font-bold border-t-4 border-t-red-500 text-red-400' : 'hover:bg-gray-800 text-gray-500' }}">
                        [ Pendientes ({{ $conteos['pendiente'] }}) ]
                    </a>
                    
                    <a href="{{ route('admin.tabla_incidencias', ['categoria' => $categoria->id, 'estado' => 'en proceso']) }}" 
                       class="px-6 py-3 border-r border-gray-800 min-w-max transition
                              {{ $estadoActual === 'en proceso' ? 'bg-gray-800 font-bold border-t-4 border-t-yellow-500 text-yellow-400' : 'hover:bg-gray-800 text-gray-500' }}">
                        [ En Proceso ({{ $conteos['en_proceso'] }}) ]
                    </a>
                    
                    <a href="{{ route('admin.tabla_incidencias', ['categoria' => $categoria->id, 'estado' => 'resuelto']) }}" 
                       class="px-6 py-3 border-r border-gray-800 min-w-max transition
                              {{ $estadoActual === 'resuelto' ? 'bg-gray-800 font-bold border-t-4 border-t-green-500 text-green-400' : 'hover:bg-gray-800 text-gray-500' }}">
                        [ Resueltos ({{ $conteos['resuelto'] }}) ]
                    </a>
                </div>

                <div class="p-5">
                    <div class="border border-gray-800 rounded overflow-hidden shadow-inner">
                        <table class="w-full text-left text-sm border-collapse">
                            <thead class="bg-gray-800/50 text-gray-400 border-b border-gray-800 font-bold uppercase text-[11px] tracking-wider">
                                <tr>
                                    <th class="p-3 border-r border-gray-800">Ciudadano</th>
                                    <th class="p-3 border-r border-gray-800">Descripción (Preview)</th>
                                    <th class="p-3 border-r border-gray-800 text-center">Ubicación</th>
                                    <th class="p-3 border-r border-gray-800 text-center">Fecha</th>
                                    <th class="p-3 border-r border-gray-800 text-center">Estado</th>
                                    <th class="p-3 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800">
                                @forelse($incidencias as $incidencia)
                                <tr class="hover:bg-gray-800/50 transition border-b border-gray-800 last:border-0">
                                    
                                    <td class="p-3 border-r border-gray-800 font-medium text-gray-300">
                                        {{ $incidencia->user ? $incidencia->user->name : 'Usuario Anónimo' }}
                                    </td>
                                    
                                    <td class="p-3 border-r border-gray-800 italic text-gray-400 max-w-xs truncate" title="{{ $incidencia->descripcion }}">
                                        {{ Str::limit($incidencia->descripcion, 40) ?: 'Sin descripción' }}
                                    </td>
                                    
                                    <td class="p-3 border-r border-gray-800 text-center">
                                        <button @click="openMap = true; setTimeout(() => cargarMapa({{ $incidencia->latitud }}, {{ $incidencia->longitud }}), 200)" 
                                                class="bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 hover:bg-cyan-500/20 px-3 py-1 rounded text-xs font-bold transition shadow-sm uppercase tracking-tighter inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px]">location_on</span> Mapa
                                        </button>
                                    </td>
                                    
                                    <td class="p-3 border-r border-gray-800 text-center text-gray-500">
                                        {{ $incidencia->created_at->format('d/m/Y') }}
                                    </td>
                                    
                                    <td class="p-3 border-r border-gray-800 text-center">
                                        @if($incidencia->estado === 'pendiente')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-500/10 text-red-500 border border-red-500/20 uppercase">Pendiente</span>
                                        @elseif($incidencia->estado === 'en proceso')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-500/10 text-yellow-500 border border-yellow-500/20 uppercase">En Proceso</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-500/10 text-green-500 border border-green-500/20 uppercase">Resuelto</span>
                                        @endif
                                    </td>
                                    
                                    <td class="p-3 text-center">
                                        <button @click="reporteSeleccionado = {{ json_encode($incidencia) }}; openModal = true"
                                                class="bg-blue-500/10 text-blue-400 border border-blue-500/20 hover:bg-blue-500/20 px-3 py-1 rounded text-xs font-bold transition shadow-sm uppercase tracking-tighter">
                                            Ver detalles
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-10 text-center text-gray-500">
                                            <span class="material-symbols-outlined text-4xl mb-2 text-gray-600 block">inbox</span>
                                            No hay reportes {{ $estadoActual }}s en esta categoría.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="openMap" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-4 backdrop-blur-sm" style="margin: 0;" x-cloak>
            <div class="bg-gray-900 border border-gray-800 w-full max-w-3xl shadow-2xl rounded-xl overflow-hidden" @click.away="openMap = false">
                
                <div class="p-4 border-b border-gray-800 flex justify-between items-center bg-gray-900">
                    <h3 class="text-xl text-white font-black uppercase tracking-tight flex items-center gap-2">
                        <span class="material-symbols-outlined text-cyan-400">map</span> Ubicación Exacta
                    </h3>
                    <button @click="openMap = false" class="text-gray-500 hover:text-red-500 transition font-bold text-2xl px-2">×</button>
                </div>
                
                <div class="p-2 bg-gray-800">
                    <div id="mapAdmin" class="w-full h-[60vh] rounded-lg border border-gray-700 z-0 relative"></div>
                </div>

                <div class="p-4 bg-gray-900 border-t border-gray-800 text-center">
                    <button @click="openMap = false" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg transition-all text-sm uppercase">
                        Cerrar Mapa
                    </button>
                </div>
            </div>
        </div>

        <div x-show="openModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-4 backdrop-blur-sm" style="margin: 0;" x-cloak>
            <div class="bg-gray-900 border border-gray-800 w-full max-w-lg shadow-2xl rounded-xl overflow-hidden" @click.away="openModal = false">
                
                <div class="p-4 border-b border-gray-800 flex justify-between items-center bg-gray-900">
                    <h3 class="text-xl text-white font-black uppercase tracking-tight">Detalle del Reporte</h3>
                    <button @click="openModal = false" class="text-gray-500 hover:text-red-500 transition font-bold text-2xl px-2">×</button>
                </div>
                
                <div class="p-6">
                    <template x-if="reporteSeleccionado.imagen_path">
                        <div class="w-full h-48 bg-gray-800 rounded-lg mb-4 flex items-center justify-center border border-gray-700 shadow-inner overflow-hidden">
                            <img :src="'/storage/' + reporteSeleccionado.imagen_path" class="w-full h-full object-cover cursor-pointer hover:scale-105 transition" @click="window.open('/storage/' + reporteSeleccionado.imagen_path)">
                        </div>
                    </template>
                    <template x-if="!reporteSeleccionado.imagen_path">
                        <div class="w-full h-48 bg-gray-800 rounded-lg mb-4 flex flex-col items-center justify-center border border-gray-700 shadow-inner text-gray-500">
                            <span class="material-symbols-outlined text-5xl mb-2">hide_image</span>
                            <span class="text-xs uppercase">Sin imagen</span>
                        </div>
                    </template>

                    <div class="space-y-3">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Descripción Ciudadana</p>
                        <p class="text-gray-300 leading-relaxed italic border-l-4 border-blue-500 pl-4 bg-blue-900/20 py-2 rounded-r" x-text="reporteSeleccionado.descripcion || 'Sin descripción'"></p>
                    </div>
                </div>

                <div class="p-4 bg-gray-900 border-t border-gray-800 flex gap-3">
                    <template x-if="reporteSeleccionado.estado === 'pendiente'">
                        <form :action="'/admin/incidencia/' + reporteSeleccionado.id + '/estado'" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="nuevo_estado" value="en proceso">
                            <button type="submit" class="w-full bg-yellow-500/10 hover:bg-yellow-500/20 text-yellow-500 border border-yellow-500/30 font-black py-3 rounded-lg transition-all uppercase text-xs shadow-md">
                                Mover a "En Proceso"
                            </button>
                        </form>
                    </template>

                    <template x-if="reporteSeleccionado.estado === 'en proceso'">
                        <form :action="'/admin/incidencia/' + reporteSeleccionado.id + '/estado'" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="nuevo_estado" value="resuelto">
                            <button type="submit" class="w-full bg-green-500/10 hover:bg-green-500/20 text-green-500 border border-green-500/30 font-black py-3 rounded-lg transition-all uppercase text-xs shadow-md">
                                Marcar como Resuelto
                            </button>
                        </form>
                    </template>
                    
                    <template x-if="reporteSeleccionado.estado === 'resuelto'">
                         <div class="w-full text-center py-2 text-green-500 font-bold text-sm uppercase flex justify-center items-center gap-2">
                             <span class="material-symbols-outlined">check_circle</span> Este reporte ha sido solucionado
                         </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Variables globales para guardar el mapa y el "pin"
        let adminMap = null;
        let adminMarker = null;

        function cargarMapa(lat, lng) {
            if (!adminMap) {
                adminMap = L.map('mapAdmin').setView([lat, lng], 18);
                
                L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
                    maxZoom: 20,
                    attribution: '© Google'
                }).addTo(adminMap);
                
                adminMarker = L.marker([lat, lng]).addTo(adminMap);
            } else {
                adminMap.setView([lat, lng], 18);
                adminMarker.setLatLng([lat, lng]);
            }
            
            adminMap.invalidateSize();
        }
    </script>
</x-app-layout>