<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-smart-text tracking-wide">
            {{ __('Dashboard de Incidencias') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <h3 class="text-smart-cobalto-dark dark:text-white font-black text-xl transition-colors">
                    Bandeja de Entrada por Categoría
                </h3>
                <p class="text-smart-cobalto-dark/70 dark:text-smart-niebla/70 text-sm font-medium leading-relaxed transition-colors">
                    Selecciona una categoría para gestionar los reportes ciudadanos.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach($categorias as $cat)
                    @php
                        // Solución al compilador de Tailwind: Clases completas mapeadas (Mantenemos su lógica intacta)
                        $paleta = [
                            ['border' => 'border-smart-action', 'icon' => 'text-smart-action', 'badgeBg' => 'bg-blue-100 dark:bg-blue-900/30', 'badgeText' => 'text-blue-800 dark:text-blue-200'],
                            ['border' => 'border-smart-success', 'icon' => 'text-smart-success', 'badgeBg' => 'bg-emerald-100 dark:bg-emerald-900/30', 'badgeText' => 'text-emerald-800 dark:text-emerald-200'],
                            ['border' => 'border-smart-warning', 'icon' => 'text-smart-warning', 'badgeBg' => 'bg-orange-100 dark:bg-orange-900/30', 'badgeText' => 'text-orange-800 dark:text-orange-200'],
                            ['border' => 'border-smart-cobalto', 'icon' => 'text-smart-cobalto', 'badgeBg' => 'bg-indigo-100 dark:bg-indigo-900/30', 'badgeText' => 'text-indigo-800 dark:text-indigo-200'],
                            ['border' => 'border-smart-error', 'icon' => 'text-smart-error', 'badgeBg' => 'bg-red-100 dark:bg-red-900/30', 'badgeText' => 'text-red-800 dark:text-red-200'],
                        ];
                        $tema = $paleta[$loop->index % count($paleta)];
                        
                        $pendientes = $cat->incidencias_count ?? 0;
                    @endphp

                    <a href="{{ route('admin.tabla_incidencias', $cat->id) }}" 
                       class="bg-white dark:bg-[#212121] p-8 rounded-xl shadow-sm border-t-4 {{ $tema['border'] }} relative text-center hover:shadow-lg dark:hover:bg-[#2a2a2a] transition-all cursor-pointer group transform hover:-translate-y-1 border border-gray-100 dark:border-white/5">
                        
                        @if($pendientes > 0)
                            <span class="absolute top-4 right-4 {{ $tema['badgeBg'] }} {{ $tema['badgeText'] }} text-[10px] font-black px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1 animate-bounce duration-300" style="animation-iteration-count: 3;">
                                <span class="h-1.5 w-1.5 rounded-full bg-current animate-pulse"></span>
                                {{ $pendientes }} PENDIENTES
                            </span>
                        @else
                            <span class="absolute top-4 right-4 bg-gray-100 dark:bg-black/20 text-gray-500 dark:text-smart-linea text-[10px] font-bold px-3 py-1.5 rounded-full flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">check_circle</span>
                                AL DÍA
                            </span>
                        @endif

                        <div class="flex justify-center mb-4 mt-2">
                            <span class="material-symbols-outlined text-6xl {{ $tema['icon'] }} group-hover:scale-110 transition-transform duration-300">
                                {{ $cat->icono ?? 'help' }}
                            </span>
                        </div>

                        <h4 class="text-xl font-bold text-gray-900 dark:text-white mt-2 transition-colors uppercase tracking-tight">
                            {{ $cat->nombre }}
                        </h4>
                    </a>
                @endforeach
                
                <a href="{{ route('categorias.index') }}" 
                   class="bg-gray-50 dark:bg-[#212121]/50 p-8 rounded-xl border-2 border-dashed border-gray-300 dark:border-white/10 flex flex-col items-center justify-center text-gray-400 dark:text-gray-500 hover:bg-white dark:hover:bg-[#2a2a2a] hover:border-smart-cobalto hover:text-smart-cobalto transition-all cursor-pointer group">
                    <span class="material-symbols-outlined text-5xl mb-2 group-hover:rotate-90 transition-transform duration-500">
                        settings_suggest
                    </span>
                    <span class="font-bold transition-colors uppercase text-xs tracking-widest">Gestionar Categorías</span>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>