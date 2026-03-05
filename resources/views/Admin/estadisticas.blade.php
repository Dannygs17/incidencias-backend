<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Módulo de Estadísticas') }}
        </h2>
    </x-slot>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gray-900 border border-gray-800 rounded-lg shadow-sm p-6 text-center">
                    <p class="text-gray-400 font-bold uppercase tracking-wider text-sm mb-2">Total Reportes</p>
                    <p class="text-4xl font-black text-white">{{ $totalReportes }}</p>
                </div>

                <div class="bg-gray-900 border border-gray-800 rounded-lg shadow-sm p-6 text-center border-b-4 border-b-green-500">
                    <p class="text-gray-400 font-bold uppercase tracking-wider text-sm mb-2">% Resueltos</p>
                    <p class="text-4xl font-black text-green-500">{{ $porcentajeResueltos }}%</p>
                </div>

                <div class="bg-gray-900 border border-gray-800 rounded-lg shadow-sm p-6 text-center border-b-4 border-b-red-500">
                    <p class="text-gray-400 font-bold uppercase tracking-wider text-sm mb-2">Casos Pendientes</p>
                    <p class="text-4xl font-black text-red-500">{{ $totalReportes - ($totalReportes * ($porcentajeResueltos / 100)) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <div class="bg-gray-900 border border-gray-800 rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-white mb-4">Incidencias Por Tipo</h3>
                    <div class="relative h-72 w-full">
                        <canvas id="miGrafica"></canvas>
                    </div>
                </div>

                <div class="bg-gray-900 border border-gray-800 rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-white mb-4">Mapa de Calor (Zonas de Reportes)</h3>
                    <div id="mapaCalor" class="w-full h-72 rounded-lg border border-gray-700 z-0 relative"></div>
                </div>

            </div>

        </div>
    </div>

    <script>
        lucide.createIcons();

        // ------------------------------------------------
        // 1. CONFIGURACIÓN DE LA GRÁFICA (Chart.js)
        // ------------------------------------------------
        const ctx = document.getElementById('miGrafica').getContext('2d');
        
        // Recibimos los datos desde Laravel
        const datosGrafica = @json($incidenciasPorTipo);
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(datosGrafica), // ['Agua', 'Alumbrado', 'Baches', 'Limpieza']
                datasets: [{
                    label: 'Cantidad de Reportes',
                    data: Object.values(datosGrafica), // [10, 5, 20, 8]
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.5)', // Azul (Agua)
                        'rgba(234, 179, 8, 0.5)',  // Amarillo (Alumbrado)
                        'rgba(107, 114, 128, 0.5)',// Gris (Baches)
                        'rgba(34, 197, 94, 0.5)'   // Verde (Limpieza)
                    ],
                    borderColor: [
                        'rgb(59, 130, 246)',
                        'rgb(234, 179, 8)',
                        'rgb(107, 114, 128)',
                        'rgb(34, 197, 94)'
                    ],
                    borderWidth: 2,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false } // Ocultamos la leyenda para que se vea más limpio
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.1)' },
                        ticks: { color: '#9ca3af' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#9ca3af', font: { weight: 'bold' } }
                    }
                }
            }
        });

        // ------------------------------------------------
        // 2. CONFIGURACIÓN DEL MAPA DE CALOR (Leaflet)
        // ------------------------------------------------
        // Obtenemos las coordenadas desde Laravel
        const puntos = @json($coordenadas);
        
        // Si hay puntos, centramos el mapa en el primer reporte; si no, en Poza Rica por defecto
        const latCentro = puntos.length > 0 ? puntos[0].latitud : 20.5333;
        const lngCentro = puntos.length > 0 ? puntos[0].longitud : -97.4500;

        // Inicializamos el mapa en modo oscuro para que combine con tu panel
        const mapa = L.map('mapaCalor').setView([latCentro, lngCentro], 13);
        
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '© OpenStreetMap contributors © CARTO'
        }).addTo(mapa);

        // Preparamos el array de coordenadas en el formato que pide el plugin de calor: [lat, lng, intensidad]
        const coordenadasCalor = puntos.map(punto => [punto.latitud, punto.longitud, 1]);

        // Dibujamos el mapa de calor
        if (coordenadasCalor.length > 0) {
            L.heatLayer(coordenadasCalor, {
                radius: 25,     // Tamaño del círculo de calor
                blur: 15,       // Qué tan difuminado se ve
                maxZoom: 15,
                gradient: {     // Colores: de azul (frío) a rojo (caliente)
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