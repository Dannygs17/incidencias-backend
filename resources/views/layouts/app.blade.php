<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Incidencias Smart') }}</title>

        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Configuración global para que los iconos de Google se alineen bien con el texto */
            .material-symbols-outlined {
                font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
                display: inline-block;
                vertical-align: middle;
                line-height: 1;
                text-transform: none;
                letter-spacing: normal;
                word-wrap: normal;
                white-space: nowrap;
                direction: ltr;
            }

            /* Estilo suave para el scrollbar en modo oscuro */
            .dark ::-webkit-scrollbar { width: 8px; }
            .dark ::-webkit-scrollbar-track { background: #1f2937; }
            .dark ::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 10px; }
            .dark ::-webkit-scrollbar-thumb:hover { background: #6b7280; }
        </style>
    </head>
    <body class="font-sans antialiased text-gray-900 dark:text-gray-100 transition-colors duration-300">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex">
            
            <aside class="w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 hidden md:block shadow-sm">
                <div class="p-6">
                    <div class="flex items-center mb-8">
                        <span class="material-symbols-outlined text-indigo-600 text-3xl mr-2">location_city</span>
                        <h2 class="text-lg font-bold text-gray-800 dark:text-white tracking-tight">Incidencias Smart</h2>
                    </div>

                    <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">Administración</h2>
                    <nav class="space-y-2">
                        
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center p-3 rounded-lg transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-indigo-600' }}">
                            <span class="material-symbols-outlined mr-3 {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-500' }}">home</span>
                            <span class="font-medium">Inicio</span>
                        </a>

                        <a href="{{ route('admin.usuarios') }}" 
                           class="flex items-center p-3 rounded-lg transition-all duration-200 group {{ request()->routeIs('admin.usuarios') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-indigo-600' }}">
                            <span class="material-symbols-outlined mr-3 {{ request()->routeIs('admin.usuarios') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-500' }}">group</span>
                            <span class="font-medium">Usuarios</span>
                        </a>

                        <a href="{{ route('admin.incidencias') }}" 
                           class="flex items-center p-3 rounded-lg transition-all duration-200 group {{ request()->routeIs('admin.incidencias') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-indigo-600' }}">
                            <span class="material-symbols-outlined mr-3 {{ request()->routeIs('admin.incidencias') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-500' }}">report_problem</span>
                            <span class="font-medium">Incidencias</span>
                        </a>

                        <a href="{{ route('admin.estadisticas') }}" 
                           class="flex items-center p-3 rounded-lg transition-all duration-200 group {{ request()->routeIs('admin.estadisticas') ? 'bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-indigo-600' }}">
                            <span class="material-symbols-outlined mr-3 {{ request()->routeIs('admin.estadisticas') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-500' }}">leaderboard</span>
                            <span class="font-medium">Estadísticas</span>
                        </a>

                    </nav>
                </div>
            </aside>

            <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
                
                @include('layouts.navigation')

                @if (isset($header))
                    <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-100 dark:border-gray-700">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                            <h1 class="text-xl font-bold text-gray-800 dark:text-white leading-tight">
                                {{ $header }}
                            </h1>
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