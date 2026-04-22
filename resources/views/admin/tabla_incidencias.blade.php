<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-smart-text tracking-wide capitalize flex items-center gap-3">
            <span class="material-symbols-outlined text-4xl text-smart-cobalto">{{ $categoria->icono }}</span>
            {{ __('Gestión de ') . $categoria->nombre }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        /* Ajuste para que el mapa se integre al modo oscuro */
        .dark .leaflet-container { background: #1a1a1a; border-color: #333; } 
    </style>

    <div class="py-12" x-data="{ openModal: false, openMap: false, reporteSeleccionado: {}, previewUrl: null }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ALERTA DE ÉXITO MEJORADA PARA MAXIMA LEGIBILIDAD --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-white dark:bg-[#212121] border-l-4 border-l-smart-success shadow-xl rounded-r-xl rounded-l-sm flex items-center gap-4 animate-[pulse_1s_ease-in-out_1]">
                    <div class="p-2 bg-smart-success/10 rounded-lg text-smart-success flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-2xl">check_circle</span>
                    </div>
                    <p class="font-black text-sm sm:text-base text-gray-700 dark:text-white tracking-wide">
                        {{ session('success') }}
                    </p>
                </div>
            @endif

            <div class="mb-6 px-4 sm:px-0">
                <a href="{{ route('admin.incidencias') }}" class="inline-flex items-center gap-2 px-5 py-2 border border-gray-200 dark:border-white/10 rounded-xl bg-white dark:bg-[#212121] hover:bg-gray-50 dark:hover:bg-black text-gray-600 dark:text-smart-linea text-xs font-black uppercase tracking-widest shadow-sm transition">
                    <span class="material-symbols-outlined text-sm">arrow_back</span> Volver
                </a>
            </div>

            <div class="bg-white dark:bg-[#212121] border border-gray-100 dark:border-white/5 sm:rounded-2xl shadow-xl overflow-hidden">
                
                {{-- Navegación de Estados (Tabs) --}}
                <div class="flex border-b border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-black/20 overflow-x-auto custom-scrollbar">
                    <a href="{{ route('admin.tabla_incidencias', ['categoria' => $categoria->id, 'estado' => 'pendiente']) }}" 
                       class="px-6 sm:px-8 py-4 border-r border-gray-100 dark:border-white/5 min-w-max transition text-[10px] sm:text-xs font-black uppercase tracking-tighter
                              {{ $estadoActual === 'pendiente' ? 'bg-white dark:bg-[#212121] border-t-4 border-t-smart-error text-smart-error' : 'text-gray-400 hover:text-gray-600 dark:hover:text-white' }}">
                        Pendientes ({{ $conteos['pendiente'] }})
                    </a>
                    
                    <a href="{{ route('admin.tabla_incidencias', ['categoria' => $categoria->id, 'estado' => 'en proceso']) }}" 
                       class="px-6 sm:px-8 py-4 border-r border-gray-100 dark:border-white/5 min-w-max transition text-[10px] sm:text-xs font-black uppercase tracking-tighter
                              {{ $estadoActual === 'en proceso' ? 'bg-white dark:bg-[#212121] border-t-4 border-t-smart-warning text-smart-warning' : 'text-gray-400 hover:text-gray-600 dark:hover:text-white' }}">
                        En Proceso ({{ $conteos['en_proceso'] }})
                    </a>
                    
                    <a href="{{ route('admin.tabla_incidencias', ['categoria' => $categoria->id, 'estado' => 'resuelto']) }}" 
                       class="px-6 sm:px-8 py-4 border-r border-gray-100 dark:border-white/5 min-w-max transition text-[10px] sm:text-xs font-black uppercase tracking-tighter
                              {{ $estadoActual === 'resuelto' ? 'bg-white dark:bg-[#212121] border-t-4 border-t-smart-success text-smart-success' : 'text-gray-400 hover:text-gray-600 dark:hover:text-white' }}">
                        Resueltos ({{ $conteos['resuelto'] }})
                    </a>
                </div>

                {{-- CONTENEDOR DE LA TABLA --}}
                <div class="p-0 sm:p-6">
                    <div class="w-full overflow-x-auto custom-scrollbar sm:border border-gray-100 dark:border-white/5 sm:rounded-xl shadow-inner bg-gray-50/50 dark:bg-black/10">
                        <table class="w-full min-w-[800px] text-left text-sm border-collapse">
                            <thead class="bg-gray-100/50 dark:bg-white/5 text-gray-500 dark:text-smart-linea border-b border-gray-100 dark:border-white/5 font-black uppercase text-[10px] tracking-[0.15em]">
                                <tr>
                                    <th class="p-4 whitespace-nowrap">Ciudadano</th>
                                    <th class="p-4 whitespace-nowrap">Descripción</th>
                                    <th class="p-4 text-center whitespace-nowrap">Ubicación</th>
                                    <th class="p-4 text-center whitespace-nowrap">Fecha</th>
                                    <th class="p-4 text-center whitespace-nowrap">Estado</th>
                                    <th class="p-4 text-center whitespace-nowrap">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                                @forelse($incidencias as $incidencia)
                                <tr class="hover:bg-white dark:hover:bg-white/5 transition group">
                                    <td class="p-4 font-bold text-gray-700 dark:text-white whitespace-nowrap">
                                        {{ $incidencia->user ? $incidencia->user->name : 'Usuario Anónimo' }}
                                    </td>
                                    <td class="p-4 italic text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                        {{ Str::limit($incidencia->descripcion, 40) ?: 'Sin descripción' }}
                                    </td>
                                    <td class="p-4 text-center">
                                        <button @click="openMap = true; setTimeout(() => window.cargarMapa({{ $incidencia->latitud }}, {{ $incidencia->longitud }}), 300)" 
                                                class="bg-smart-action/10 text-smart-action border border-smart-action/20 hover:bg-smart-action hover:text-white px-3 py-1.5 rounded-lg text-[10px] font-black transition uppercase tracking-widest inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px]">location_on</span> Ver Mapa
                                        </button>
                                    </td>
                                    <td class="p-4 text-center text-gray-400 font-medium text-xs whitespace-nowrap">
                                        {{ $incidencia->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="p-4 text-center">
                                        @php
                                            $colores = [
                                                'pendiente' => 'bg-smart-error/10 text-smart-error border-smart-error/20',
                                                'en proceso' => 'bg-smart-warning/10 text-smart-warning border-smart-warning/20',
                                                'resuelto' => 'bg-smart-success/10 text-smart-success border-smart-success/20'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black border uppercase tracking-wider whitespace-nowrap {{ $colores[$incidencia->estado] }}">
                                            {{ $incidencia->estado }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <button @click="reporteSeleccionado = {{ json_encode($incidencia) }}; previewUrl = null; openModal = true"
                                                class="bg-smart-cobalto/10 text-smart-cobalto border border-smart-cobalto/20 hover:bg-smart-cobalto hover:text-white px-4 py-1.5 rounded-lg text-[10px] font-black transition uppercase tracking-widest shadow-sm">
                                            Gestionar
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-16 text-center text-gray-400">
                                            <span class="material-symbols-outlined text-6xl mb-4 text-gray-200 dark:text-white/10 block">folder_open</span>
                                            <span class="font-bold uppercase tracking-widest text-xs">No hay reportes {{ $estadoActual }}s</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL MAPA --}}
        <div x-show="openMap" class="fixed inset-0 bg-black/90 flex items-center justify-center z-[60] p-4 backdrop-blur-md" x-cloak>
            <div class="bg-white dark:bg-[#212121] border dark:border-white/10 w-full max-w-4xl shadow-2xl rounded-[2rem] overflow-hidden" @click.away="openMap = false">
                <div class="p-4 sm:p-6 border-b dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-black/40">
                    <h3 class="text-lg sm:text-xl text-smart-cobalto-dark dark:text-white font-black uppercase tracking-tighter flex items-center gap-2">
                        <span class="material-symbols-outlined text-smart-action">map</span> Ubicación
                    </h3>
                    <button @click="openMap = false" class="text-gray-400 hover:text-smart-error transition text-3xl font-light">×</button>
                </div>
                <div class="p-4">
                    <div id="mapAdmin" style="height: 50vh; width: 100%; min-height: 400px;" class="rounded-2xl border dark:border-white/5 z-0 relative shadow-inner"></div>
                </div>
            </div>
        </div>

        {{-- MODAL GESTIÓN (Se mantiene sin cambios) --}}
        <div x-show="openModal" class="fixed inset-0 bg-black/90 flex items-center justify-center z-50 p-4 backdrop-blur-md" x-cloak>
            <div class="bg-white dark:bg-[#212121] border dark:border-white/10 w-full max-w-2xl shadow-2xl rounded-[2rem] overflow-hidden max-h-[90vh] flex flex-col" @click.away="openModal = false">
                
                <div class="p-4 sm:p-6 border-b dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-black/40 shrink-0">
                    <h3 class="text-lg sm:text-xl text-smart-cobalto-dark dark:text-white font-black uppercase tracking-tighter">Detalles de Incidencia</h3>
                    <button @click="openModal = false" class="text-gray-400 hover:text-smart-error transition text-3xl font-light">×</button>
                </div>
                
                <div class="p-4 sm:p-8 overflow-y-auto custom-scrollbar">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Evidencia del Ciudadano</h4>
                            <template x-if="reporteSeleccionado.imagen_path">
                                <div class="w-full h-48 sm:h-56 bg-gray-100 dark:bg-black/40 rounded-2xl border dark:border-white/5 overflow-hidden shadow-inner group">
                                    <img :src="'/storage/' + reporteSeleccionado.imagen_path" class="w-full h-full object-cover group-hover:scale-110 transition duration-500 cursor-zoom-in" @click="window.open('/storage/' + reporteSeleccionado.imagen_path)">
                                </div>
                            </template>
                            <template x-if="!reporteSeleccionado.imagen_path">
                                <div class="w-full h-48 sm:h-56 bg-gray-50 dark:bg-black/20 rounded-2xl flex flex-col items-center justify-center border-2 border-dashed dark:border-white/5 text-gray-400">
                                    <span class="material-symbols-outlined text-5xl mb-2 opacity-20">no_photography</span>
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-center px-4">Sin evidencia fotográfica</span>
                                </div>
                            </template>
                        </div>

                        <div class="bg-gray-50 dark:bg-black/20 p-4 sm:p-5 rounded-2xl border-l-4 border-smart-cobalto">
                            <h4 class="text-[10px] font-black text-smart-cobalto uppercase tracking-[0.2em] mb-2">Relato de los hechos</h4>
                            <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed italic" x-text="reporteSeleccionado.descripcion || 'Sin descripción detallada.'"></p>
                        </div>

                        {{-- Vista Resuelto --}}
                        <template x-if="reporteSeleccionado.estado === 'resuelto'">
                            <div class="mt-2 sm:mt-4 pt-4 sm:pt-6 border-t dark:border-white/5">
                                <div class="flex items-center gap-2 mb-4">
                                    <span class="material-symbols-outlined text-smart-success">verified</span>
                                    <h4 class="text-[10px] font-black text-smart-success uppercase tracking-[0.2em]">Resolución Oficial</h4>
                                </div>
                                <template x-if="reporteSeleccionado.evidencia_path">
                                    <div class="w-full h-40 sm:h-48 bg-gray-100 dark:bg-black/40 rounded-2xl mb-4 border dark:border-white/5 overflow-hidden">
                                        <img :src="'/storage/' + reporteSeleccionado.evidencia_path" class="w-full h-full object-cover">
                                    </div>
                                </template>
                                <p class="text-gray-600 dark:text-gray-300 text-sm p-4 bg-smart-success/5 rounded-xl border border-smart-success/10" x-text="reporteSeleccionado.comentario_admin"></p>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Acciones del Admin --}}
                <div class="p-4 sm:p-6 bg-gray-50 dark:bg-black/40 border-t dark:border-white/5 shrink-0">
                    <template x-if="reporteSeleccionado.estado === 'pendiente'">
                        <form :action="'/admin/incidencia/' + reporteSeleccionado.id + '/estado'" method="POST">
                            @csrf
                            <input type="hidden" name="nuevo_estado" value="en proceso">
                            <button type="submit" class="w-full bg-smart-warning text-white font-black py-4 rounded-xl sm:rounded-2xl transition-all uppercase text-[10px] sm:text-xs tracking-[0.1em] shadow-lg shadow-smart-warning/20 hover:brightness-110 active:scale-95 flex justify-center items-center gap-2">
                                <span class="material-symbols-outlined">engineering</span> Mover a en proceso
                            </button>
                        </form>
                    </template>

                    <template x-if="reporteSeleccionado.estado === 'en proceso'">
                        <form :action="'/admin/incidencia/' + reporteSeleccionado.id + '/estado'" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <input type="hidden" name="nuevo_estado" value="resuelto">
                            
                            <div class="space-y-4">
                                <textarea name="comentario_admin" rows="2" class="w-full bg-white dark:bg-black/40 border-gray-200 dark:border-white/10 text-gray-700 dark:text-white rounded-xl focus:ring-smart-success text-sm placeholder-gray-400 font-medium p-3" placeholder="Mensaje de cierre..."></textarea>
                                
                                <div class="flex items-center gap-4">
                                    <label class="flex-1">
                                        <span class="sr-only">Elegir foto</span>
                                        <input type="file" name="evidencia" accept="image/*" @change="const file = $event.target.files[0]; if(file) previewUrl = URL.createObjectURL(file);" 
                                               class="block w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl sm:file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-smart-cobalto/10 file:text-smart-cobalto hover:file:bg-smart-cobalto/20 cursor-pointer">
                                    </label>
                                    <template x-if="previewUrl">
                                        <img :src="previewUrl" class="w-12 h-12 rounded-lg border-2 border-smart-success object-cover">
                                    </template>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-smart-success text-white font-black py-4 rounded-xl sm:rounded-2xl transition-all uppercase text-[10px] sm:text-xs tracking-[0.1em] shadow-lg shadow-smart-success/20 hover:brightness-110 flex justify-center items-center gap-2">
                                <span class="material-symbols-outlined text-lg">task_alt</span> Resolver
                            </button>
                        </form>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.cargarMapa = function(lat, lng) {
            if (!window.adminMap) {
                window.adminMap = L.map('mapAdmin', { zoomControl: false }).setView([lat, lng], 18);
                L.control.zoom({ position: 'bottomright' }).addTo(window.adminMap);
                
                L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
                    maxZoom: 20,
                    attribution: '© Google'
                }).addTo(window.adminMap);
                
                window.adminMarker = L.marker([lat, lng]).addTo(window.adminMap);
            } else {
                window.adminMap.setView([lat, lng], 18);
                window.adminMarker.setLatLng([lat, lng]);
            }

            setTimeout(() => {
                if(window.adminMap) {
                    window.adminMap.invalidateSize(true);
                }
            }, 150); 
        };
    </script>
</x-app-layout>