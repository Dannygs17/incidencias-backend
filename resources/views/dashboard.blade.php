<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Inicio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                <div class="p-8 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="p-3 bg-indigo-100 dark:bg-indigo-900/40 rounded-full mr-4">
                            <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z"></path>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-extrabold">¡Bienvenido, {{ auth()->user()->name }}!</h1>
                    </div>

                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 border-l-4 border-indigo-500 pl-4 italic">
                        Tu panel de control está actualizado. Esto es lo que requiere tu atención hoy:
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="flex items-start p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                            <div class="flex-shrink-0 pt-1">
                                <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    Tienes <span class="font-bold text-amber-600 dark:text-amber-400">{{ $pendientesAprobar }}</span> usuarios esperando aprobación.
                                </p>
                                <a href="{{ route('admin.usuarios') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-semibold mt-1 inline-block">Revisar solicitudes →</a>
                            </div>
                        </div>

                        <div class="flex items-start p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                            <div class="flex-shrink-0 pt-1">
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    Hay <span class="font-bold text-blue-600 dark:text-blue-400">{{ $reportesNuevos }}</span> nuevos reportes de ciudadanos sin atender.
                                </p>
                                <a href="{{ route('admin.incidencias') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-semibold mt-1 inline-block">Ir a incidencias →</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>