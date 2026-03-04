<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Incidencias') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                <a href="{{ route('admin.tabla_incidencias', 'agua') }}"class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md border-t-4 border-blue-500 relative text-center hover:shadow-lg dark:hover:bg-gray-750 transition cursor-pointer group">
                    <span class="absolute top-2 right-2 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-bold px-2 py-1 rounded-full">
                        {{ $conteos['agua'] }}
                    </span>
                    <div class="flex justify-center mb-4">
                        <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86 7.717l.644 1.288a2 2 0 002.268.964l2.386-.477a2 2 0 001.022-.547l2.146-2.146a2 2 0 000-2.828l-2.146-2.146z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M13.8 12H3"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-blue-500">Agua</h4>
                </a>
                
                <a href="{{ route('admin.tabla_incidencias', 'alumbrado') }}"  class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md border-t-4 border-yellow-400 relative text-center hover:shadow-lg dark:hover:bg-gray-750 transition cursor-pointer group">
                    <span class="absolute top-2 right-2 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 text-xs font-bold px-2 py-1 rounded-full">
                        {{ $conteos['alumbrado'] }}
                    </span>
                    <div class="flex justify-center mb-4">
                        <svg class="w-12 h-12 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-yellow-400">Alumbrado</h4>
                </a>

                <a href="{{ route('admin.tabla_incidencias', 'bacheo') }}" class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md border-t-4 border-gray-500 relative text-center hover:shadow-lg dark:hover:bg-gray-750 transition cursor-pointer group">
                    <span class="absolute top-2 right-2 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs font-bold px-2 py-1 rounded-full">
                        {{ $conteos['bacheo'] }}
                    </span>
                    <div class="flex justify-center mb-4">
                        <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-gray-500">Baches</h4>
                </a>

                <a href="{{ route('admin.tabla_incidencias', 'basura') }}" class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md border-t-4 border-green-500 relative text-center hover:shadow-lg dark:hover:bg-gray-750 transition cursor-pointer group">
                    <span class="absolute top-2 right-2 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-xs font-bold px-2 py-1 rounded-full">
                        {{ $conteos['basura'] }}
                    </span>
                    <div class="flex justify-center mb-4">
                        <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-green-500">Limpieza</h4>
                </a>

                <a class="bg-gray-50 dark:bg-gray-700 p-8 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 flex flex-col items-center justify-center text-gray-400 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-600 transition cursor-pointer">
                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    <span class="font-medium">Crear nueva</span>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>