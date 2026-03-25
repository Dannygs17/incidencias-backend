<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Incidencias') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach($categorias as $cat)
                    @php
                        // Paleta de colores dinámica
                        $colores = ['blue', 'yellow', 'emerald', 'red', 'purple', 'orange', 'indigo'];
                        $color = $colores[$loop->index % count($colores)];
                    @endphp

                    <a href="{{ route('admin.tabla_incidencias', $cat->id) }}" 
                       class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md border-t-4 border-{{ $color }}-500 relative text-center hover:shadow-lg dark:hover:bg-gray-750 transition cursor-pointer group">
                        
                        <span class="absolute top-2 right-2 bg-{{ $color }}-100 dark:bg-{{ $color }}-900 text-{{ $color }}-800 dark:text-{{ $color }}-200 text-xs font-bold px-2 py-1 rounded-full">
                            {{ $cat->incidencias_count ?? 0 }}
                        </span>

                        <div class="flex justify-center mb-4">
                            <span class="material-symbols-outlined text-5xl text-{{ $color }}-500 group-hover:scale-110 transition-transform">
                                {{ $cat->icono ?? 'help' }}
                            </span>
                        </div>

                        <h4 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-{{ $color }}-500">
                            {{ $cat->nombre }}
                        </h4>
                    </a>
                @endforeach
                
                <a href="{{ route('categorias.index') }}" 
                   class="bg-gray-50 dark:bg-gray-700 p-8 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 flex flex-col items-center justify-center text-gray-400 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-600 transition cursor-pointer group">
                    <span class="material-symbols-outlined text-4xl mb-2 group-hover:text-indigo-500">
                        settings_suggest
                    </span>
                    <span class="font-medium group-hover:text-indigo-500">Gestionar Categorías</span>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>