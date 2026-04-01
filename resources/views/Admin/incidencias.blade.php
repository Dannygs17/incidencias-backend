<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            <span class="material-symbols-outlined text-blue-500">dashboard</span>
            {{ __('Dashboard de Incidencias') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-700 dark:text-gray-300">
                    Bandeja de Entrada por Categoría
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Selecciona una categoría para gestionar los reportes ciudadanos.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach($categorias as $cat)
                    @php
                        // Solución al compilador de Tailwind: Clases completas mapeadas
                        $paleta = [
                            ['border' => 'border-blue-500', 'icon' => 'text-blue-500', 'badgeBg' => 'bg-blue-100 dark:bg-blue-900', 'badgeText' => 'text-blue-800 dark:text-blue-200'],
                            ['border' => 'border-emerald-500', 'icon' => 'text-emerald-500', 'badgeBg' => 'bg-emerald-100 dark:bg-emerald-900', 'badgeText' => 'text-emerald-800 dark:text-emerald-200'],
                            ['border' => 'border-orange-500', 'icon' => 'text-orange-500', 'badgeBg' => 'bg-orange-100 dark:bg-orange-900', 'badgeText' => 'text-orange-800 dark:text-orange-200'],
                            ['border' => 'border-purple-500', 'icon' => 'text-purple-500', 'badgeBg' => 'bg-purple-100 dark:bg-purple-900', 'badgeText' => 'text-purple-800 dark:text-purple-200'],
                            ['border' => 'border-red-500', 'icon' => 'text-red-500', 'badgeBg' => 'bg-red-100 dark:bg-red-900', 'badgeText' => 'text-red-800 dark:text-red-200'],
                        ];
                        $tema = $paleta[$loop->index % count($paleta)];
                        
                        $pendientes = $cat->incidencias_count ?? 0;
                    @endphp

                    <a href="{{ route('admin.tabla_incidencias', $cat->id) }}" 
                       class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-sm border-t-4 {{ $tema['border'] }} relative text-center hover:shadow-lg dark:hover:bg-gray-750 transition-all cursor-pointer group transform hover:-translate-y-1">
                        
                        @if($pendientes > 0)
                            <span class="absolute top-4 right-4 {{ $tema['badgeBg'] }} {{ $tema['badgeText'] }} text-xs font-black px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1 animate-bounce duration-300" style="animation-iteration-count: 3;">
                                <span class="h-2 w-2 rounded-full bg-current animate-pulse"></span>
                                {{ $pendientes }} Pendientes
                            </span>
                        @else
                            <span class="absolute top-4 right-4 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs font-medium px-3 py-1.5 rounded-full flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">check_circle</span>
                                Al día
                            </span>
                        @endif

                        <div class="flex justify-center mb-4 mt-2">
                            <span class="material-symbols-outlined text-6xl {{ $tema['icon'] }} group-hover:scale-110 transition-transform duration-300">
                                {{ $cat->icono ?? 'help' }}
                            </span>
                        </div>

                        <h4 class="text-xl font-bold text-gray-900 dark:text-white mt-2">
                            {{ $cat->nombre }}
                        </h4>
                    </a>
                @endforeach
                
                <a href="{{ route('categorias.index') }}" 
                   class="bg-gray-50 dark:bg-gray-800/50 p-8 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 flex flex-col items-center justify-center text-gray-400 dark:text-gray-500 hover:bg-white dark:hover:bg-gray-700 hover:border-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-400 transition-all cursor-pointer group">
                    <span class="material-symbols-outlined text-5xl mb-2 group-hover:rotate-90 transition-transform duration-500">
                        settings_suggest
                    </span>
                    <span class="font-bold">Gestionar Categorías</span>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>