<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 dark:text-gray-100">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex">
            
            <aside class="w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 hidden md:block transition-colors duration-300">
                <div class="p-6">
                    <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">Administración</h2>
                    <nav class="space-y-2">
                        
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center p-3 rounded-lg transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-indigo-600' }}">
                            <svg class="w-5 h-5 mr-3 transition-colors {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <span class="font-medium">Inicio</span>
                        </a>

                        <a href="{{ route('admin.usuarios') }}" 
                           class="flex items-center p-3 rounded-lg transition-all duration-200 group {{ request()->routeIs('admin.usuarios') ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-indigo-600' }}">
                            <svg class="w-5 h-5 mr-3 transition-colors {{ request()->routeIs('admin.usuarios') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span class="font-medium">Usuarios</span>
                        </a>

                        <a href="{{ route('admin.incidencias') }}" 
                           class="flex items-center p-3 rounded-lg transition-all duration-200 group {{ request()->routeIs('admin.incidencias') ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-indigo-600' }}">
                            <svg class="w-5 h-5 mr-3 transition-colors {{ request()->routeIs('admin.incidencias') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                            </svg>
                            <span class="font-medium">Incidencias</span>
                        </a>
                        <a href="{{ route('admin.estadisticas') }}" 
   class="flex items-center p-3 rounded-lg transition-all duration-200 group {{ request()->routeIs('admin.estadisticas') ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-indigo-600' }}">
    
    <i class="fa-solid fa-chart-line w-5 text-center mr-3 text-lg transition-colors {{ request()->routeIs('admin.estadisticas') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-500' }}"></i>

    <span class="font-medium">Estadísticas</span>
</a>

                    </nav>
                </div>
            </aside>

            <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
                @include('layouts.navigation')

                @if (isset($header))
                    <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-100 dark:border-gray-700">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 font-bold">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>