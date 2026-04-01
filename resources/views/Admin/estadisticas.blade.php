<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight flex items-center gap-3">
            <span class="material-symbols-outlined text-indigo-400 text-3xl">analytics</span>
            {{ __('Módulo de Estadísticas y Monitoreo') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="bg-gray-900 border border-gray-800 rounded-xl shadow-lg p-6 flex items-center gap-4 transition hover:border-gray-700">
                    <div class="p-3 bg-indigo-500/10 rounded-full text-indigo-400">
                        <span class="material-symbols-outlined text-4xl">inventory_2</span>
                    </div>
                    <div>
                        <p class="text-gray-400 font-bold uppercase tracking-wider text-xs mb-1">Total Histórico</p>
                        <p class="text-5xl font-black text-white">{{ $totalReportes }}</p>
                        <p class="text-xs text-gray-500 mt-1">Reportes recibidos</p>
                    </div>
                </div>

                <div class="bg-gray-900 border border-gray-800 rounded-xl shadow-lg p-6 flex items-center gap-4 border-l-4 border-l-amber-500 transition hover:border-gray-700">
                    <div class="p-3 bg-amber-500/10 rounded-full text-amber-400">
                        <span class="material-symbols-outlined text-4xl">pending_actions</span>
                    </div>
                    <div>
                        <p class="text-gray-400 font-bold uppercase tracking-wider text-xs mb-1">Por Atender</p>
                        <p class="text-5xl font-black text-amber-500">{{ $casosActivos }}</p>
                        <p class="text-xs text-amber-600/80 mt-1 font-medium">Pendientes + En Proceso</p>
                    </div>
                </div>

                <div class="bg-gray-900 border border-gray-800 rounded-xl shadow-lg p-6 flex items-center gap-4 border-l-4 border-l-emerald-500 transition hover:border-gray-700">
                    <div class="p-3 bg-emerald-500/10 rounded-full text-emerald-400">
                        <span class="material-symbols-outlined text-4xl">task_alt</span>
                    </div>
                    <div>
                        <p class="text-gray-400 font-bold uppercase tracking-wider text-xs mb-1">Casos Resueltos</p>
                        <p class="text-5xl font-black text-emerald-500">{{ $resueltos }}</p>
                        <p class="text-xs text-emerald-600/80 mt-1 font-medium">Reportes finalizados</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8">
                
                <div class="bg-gray-900 border border-gray-800 rounded-2xl shadow-xl p-7">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-extrabold text-white tracking-tight">Estatus por Categoría</h3>
                    </div>
                    <div class="relative h-80 w-full">
                        <canvas id="graficaCategorias"></canvas>
                    </div>
                </div>

                <div class="bg-gray-900 border border-gray-800 rounded-2xl shadow-xl p-7">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-extrabold text-white tracking-tight">Zonas de Mayor Incidencia</h3>
                    </div>
                    <div id="mapaCalor" class="w-full h-80 rounded-xl border-2 border-gray-800 z-0 relative shadow-inner"></div>
                </div>

            </div>

        </div>
    </div>

    <script>
        // ------------------------------------------------
        // 1. CONFIGURACIÓN DE LA GRÁFICA (Chart.js)
        // ------------------------------------------------
        const ctx = document.getElementById('graficaCategorias').getContext('2d');
        const categoriasData = @json($categoriasEstadisticas);
        
        // Preparamos los arrays para Chart.js
        const nombresCategorias = categoriasData.map(cat => cat.nombre);
        const dataPendientes = categoriasData.map(cat => cat.pendientes_count);
        const dataProceso = categoriasData.map(cat => cat.proceso_count);
        const dataResueltos = categoriasData.map(cat => cat.resueltos_count);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: nombresCategorias, // En el eje X van las categorías
                datasets: [
                    {
                        label: 'Pendientes',
                        data: dataPendientes,
                        backgroundColor: 'rgba(239, 68, 68, 0.7)',  // Rojo
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 1,
                        borderRadius: 4
                    },
                    {
                        label: 'En Proceso',
                        data: dataProceso,
                        backgroundColor: 'rgba(245, 158, 11, 0.7)', // Amarillo
                        borderColor: 'rgb(245, 158, 11)',
                        borderWidth: 1,
                        borderRadius: 4
                    },
                    {
                        label: 'Resueltos',
                        data: dataResueltos,
                        backgroundColor: 'rgba(34, 197, 94, 0.7)',   // Verde
                        borderColor: 'rgb(34, 197, 94)',
                        borderWidth: 1,
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: true,
                        labels: { color: '#9ca3af', font: { size: 11, weight: 'bold' } }
                    },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        padding: 12,
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        border: { display: false },
                        ticks: { 
                            color: '#6b7280',
                            precision: 0, // Forzar números enteros (1, 2, 3...)
                            stepSize: 1
                        }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: { color: '#9ca3af', font: { weight: 'bold' } }
                    }
                }
            }
        });

        // ------------------------------------------------
        // 2. CONFIGURACIÓN DEL MAPA DE CALOR (Leaflet)
        // ------------------------------------------------
        const puntos = @json($coordenadas);
        const latCentro = puntos.length > 0 ? puntos[0].latitud : 20.5333;
        const lngCentro = puntos.length > 0 ? puntos[0].longitud : -97.4500;

        const mapa = L.map('mapaCalor', { zoomControl: false }).setView([latCentro, lngCentro], 13);
        L.control.zoom({ position: 'bottomright' }).addTo(mapa);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '© CARTO'
        }).addTo(mapa);

        // Preparamos el array para el mapa de calor: [lat, lng, intensidad]
        const coordenadasCalor = puntos.map(punto => [punto.latitud, punto.longitud, 1]);

        if (coordenadasCalor.length > 0) {
            L.heatLayer(coordenadasCalor, {
                radius: 25,
                blur: 15,
                maxZoom: 15,
                gradient: { 
                    0.4: 'blue', 
                    0.6: 'cyan', 
                    0.7: 'lime', 
                    0.8: 'yellow', 
                    1.0: 'red'
                }
            }).addTo(mapa);
        }

    </script>
</x-app-layout>