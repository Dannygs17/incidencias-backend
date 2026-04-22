<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-smart-text tracking-wide">
            {{ __('Módulo de Estadísticas y Monitoreo') }}
        </h2>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

    <style>
        .leaflet-container { background-color: transparent !important; z-index: 10; }
        .dark .map-tiles { filter: invert(100%) hue-rotate(180deg) brightness(85%) contrast(90%); }
        /* Animación para el pulso del radar */
        @keyframes pulse-radar {
            0% { box-shadow: 0 0 0 0 rgba(231, 76, 60, 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(231, 76, 60, 0); }
            100% { box-shadow: 0 0 0 0 rgba(231, 76, 60, 0); }
        }
        .radar-active { animation: pulse-radar 2s infinite; }
    </style>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8">

            {{-- 1. Tarjetas Superiores de KPI (Responsivas) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
                {{-- Total --}}
                <div class="bg-white dark:bg-[#212121] border border-gray-100 dark:border-white/5 rounded-2xl shadow-xl p-5 sm:p-7 flex items-center gap-4 sm:gap-5 transition hover:scale-[1.02] duration-300">
                    <div class="p-3 sm:p-4 bg-smart-action/10 rounded-2xl text-smart-action shrink-0">
                        <span class="material-symbols-outlined text-4xl sm:text-5xl">inventory_2</span>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-gray-400 font-black uppercase tracking-widest text-[9px] sm:text-[10px] mb-1">Total Histórico</p>
                        <p class="text-3xl sm:text-5xl font-black text-smart-cobalto-dark dark:text-white tracking-tighter">{{ $totalReportes }}</p>
                    </div>
                </div>

                {{-- Pendientes --}}
                <div class="bg-white dark:bg-[#212121] border border-gray-100 dark:border-white/5 rounded-2xl shadow-xl p-5 sm:p-7 flex items-center gap-4 sm:gap-5 border-l-4 border-l-smart-warning transition hover:scale-[1.02] duration-300">
                    <div class="p-3 sm:p-4 bg-smart-warning/10 rounded-2xl text-smart-warning shrink-0">
                        <span class="material-symbols-outlined text-4xl sm:text-5xl">pending_actions</span>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-gray-400 font-black uppercase tracking-widest text-[9px] sm:text-[10px] mb-1">Por Atender</p>
                        <p class="text-3xl sm:text-5xl font-black text-smart-warning tracking-tighter">{{ $casosActivos }}</p>
                    </div>
                </div>

                {{-- Resueltos --}}
                <div class="bg-white dark:bg-[#212121] border border-gray-100 dark:border-white/5 rounded-2xl shadow-xl p-5 sm:p-7 flex items-center gap-4 sm:gap-5 border-l-4 border-l-smart-success transition hover:scale-[1.02] duration-300 sm:col-span-2 md:col-span-1">
                    <div class="p-3 sm:p-4 bg-smart-success/10 rounded-2xl text-smart-success shrink-0">
                        <span class="material-symbols-outlined text-4xl sm:text-5xl">task_alt</span>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-gray-400 font-black uppercase tracking-widest text-[9px] sm:text-[10px] mb-1">Casos Resueltos</p>
                        <p class="text-3xl sm:text-5xl font-black text-smart-success tracking-tighter">{{ $resueltos }}</p>
                    </div>
                </div>
            </div>

            {{-- 2. Gráfica de Barras --}}
            <div class="bg-white dark:bg-[#212121] border border-gray-100 dark:border-white/5 rounded-[2rem] shadow-2xl p-6 sm:p-8">
                <div class="flex items-center gap-3 mb-6 sm:mb-8">
                    <div class="h-8 w-1 bg-smart-cobalto rounded-full"></div>
                    <h3 class="text-sm sm:text-base font-black text-smart-cobalto-dark dark:text-white tracking-tight uppercase">Estatus por Categoría</h3>
                </div>
                <div class="relative h-72 sm:h-96 w-full">
                    <canvas id="graficaCategorias"></canvas>
                </div>
            </div>

            {{-- 3. Mapa de Calor --}}
            <div class="bg-white dark:bg-[#212121] border border-gray-100 dark:border-white/5 rounded-[2rem] shadow-2xl p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-2">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-1 bg-smart-error rounded-full radar-active"></div>
                        <h3 class="text-sm sm:text-base font-black text-smart-cobalto-dark dark:text-white tracking-tight uppercase">Zonas de Incidencia (Radar Térmico)</h3>
                    </div>
                    <span class="text-[9px] sm:text-[10px] font-black text-gray-400 dark:text-smart-linea uppercase tracking-widest">Monitoreo Satelital Activo</span>
                </div>
                
                <div id="mapaCalor" class="w-full h-[350px] sm:h-[500px] rounded-3xl border border-gray-200 dark:border-white/10 relative overflow-hidden shadow-inner bg-gray-50 dark:bg-black/20"></div>
            </div>

        </div>
    </div>

    <script>
        // --- 1. GRÁFICA CON AJUSTE DINÁMICO ---
        const ctx = document.getElementById('graficaCategorias').getContext('2d');
        const categoriasData = @json($categoriasEstadisticas);
        
        const nombresCategorias = categoriasData.map(cat => cat.nombre);
        const dataPendientes = categoriasData.map(cat => cat.pendientes_count);
        const dataProceso = categoriasData.map(cat => cat.proceso_count);
        const dataResueltos = categoriasData.map(cat => cat.resueltos_count);

        const universalTextColor = '#9CA3AF'; 
        const isMobile = window.innerWidth < 768;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: nombresCategorias,
                datasets: [
                    { 
                        label: 'Pendientes', 
                        data: dataPendientes, 
                        backgroundColor: '#E74C3C',
                        barPercentage: 1, // <--- Elimina el espacio entre barras del mismo grupo
                        categoryPercentage: 0.8 // <--- Controla el ancho del grupo completo
                    },
                    { 
                        label: 'En Proceso', 
                        data: dataProceso, 
                        backgroundColor: '#F39C12',
                        barPercentage: 1,
                        categoryPercentage: 0.8
                    },
                    { 
                        label: 'Resueltos', 
                        data: dataResueltos, 
                        backgroundColor: '#1ABC9C',
                        barPercentage: 1,
                        categoryPercentage: 0.8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: true,
                        position: isMobile ? 'bottom' : 'right', 
                        labels: { 
                            color: universalTextColor, 
                            font: { size: isMobile ? 10 : 12, weight: 'bold' }, 
                            usePointStyle: true,
                            padding: isMobile ? 10 : 20 
                        }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: 'rgba(156, 163, 175, 0.1)' }, 
                        ticks: { color: universalTextColor } 
                    },
                    x: { 
                        grid: { display: false }, 
                        ticks: { 
                            color: universalTextColor, 
                            font: { size: isMobile ? 9 : 11, weight: 'bold' },
                            maxRotation: 45,
                            minRotation: 45
                        } 
                    }
                }
            }
        });

        // --- 2. MAPA ---
        const puntos = @json($coordenadas);
        const latCentro = puntos.length > 0 ? puntos[0].latitud : 20.5333;
        const lngCentro = puntos.length > 0 ? puntos[0].longitud : -97.4500;

        const mapa = L.map('mapaCalor', { zoomControl: false, scrollWheelZoom: false }).setView([latCentro, lngCentro], 14);
        L.control.zoom({ position: 'bottomright' }).addTo(mapa);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            className: 'map-tiles'
        }).addTo(mapa);

        const coordenadasCalor = puntos.map(punto => [punto.latitud, punto.longitud, 0.8]);

        if (coordenadasCalor.length > 0) {
            L.heatLayer(coordenadasCalor, {
                radius: isMobile ? 25 : 35, 
                blur: 20,   
                maxZoom: 15,
                gradient: { 0.4: '#0033FF', 0.6: '#1ABC9C', 0.8: '#F39C12', 1.0: '#E74C3C' }
            }).addTo(mapa);
        }
    </script>
</x-app-layout>