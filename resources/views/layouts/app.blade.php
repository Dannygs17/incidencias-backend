<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ asset('images/loguito.png') }}" type="image/png">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-smart-text">
        <div class="min-h-screen bg-gradient-to-br from-smart-cobalto to-smart-niebla flex transition-all duration-500">
            
            <aside class="w-64 bg-smart-cobalto-dark border-r border-white/5 hidden md:block shadow-2xl">
                <div class="p-6">
                    {{-- Título de Sección --}}
                    <h2 class="text-[10px] font-bold text-smart-niebla/40 uppercase tracking-[0.2em] mb-8 px-3">
                        Administración
                    </h2>
                    
                    <nav class="space-y-1.5">
                        
                        {{-- Inicio --}}
                        <a href="{{ route('dashboard') }}" 
                        class="flex items-center p-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('dashboard') ? 'bg-smart-cobalto text-white shadow-[0_10px_20px_-5px_rgba(0,51,255,0.4)]' : 'text-smart-niebla/60 hover:bg-white/5 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-smart-cobalto group-hover:scale-110 transition-transform' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <span class="font-semibold tracking-wide">Inicio</span>
                        </a>

                        {{-- Usuarios --}}
                        <a href="{{ route('admin.usuarios') }}" 
                        class="flex items-center p-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('admin.usuarios') ? 'bg-smart-cobalto text-white shadow-[0_10px_20px_-5px_rgba(0,51,255,0.4)]' : 'text-smart-niebla/60 hover:bg-white/5 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.usuarios') ? 'text-white' : 'text-smart-cobalto group-hover:scale-110 transition-transform' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span class="font-semibold tracking-wide">Usuarios</span>
                        </a>

                        {{-- Incidencias --}}
                        <a href="{{ route('admin.incidencias') }}" 
                        class="flex items-center p-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('admin.incidencias') ? 'bg-smart-cobalto text-white shadow-[0_10px_20px_-5px_rgba(0,51,255,0.4)]' : 'text-smart-niebla/60 hover:bg-white/5 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.incidencias') ? 'text-white' : 'text-smart-cobalto group-hover:scale-110 transition-transform' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                            </svg>
                            <span class="font-semibold tracking-wide">Incidencias</span>
                        </a>

                        {{-- Estadísticas --}}
                        <a href="{{ route('admin.estadisticas') }}" 
                        class="flex items-center p-3 rounded-xl transition-all duration-300 group {{ request()->routeIs('admin.estadisticas') ? 'bg-smart-cobalto text-white shadow-[0_10px_20px_-5px_rgba(0,51,255,0.4)]' : 'text-smart-niebla/60 hover:bg-white/5 hover:text-white' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.estadisticas') ? 'text-white' : 'text-smart-cobalto group-hover:scale-110 transition-transform' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="font-semibold tracking-wide">Estadísticas</span>
                        </a>

                    </nav>
                </div>
    
                {{-- Tip: Un pequeño separador visual al final si lo deseas --}}
                <div class="absolute bottom-0 w-full p-6 border-t border-white/5">
                </div>
            </aside>

            <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
                @include('layouts.navigation')

            @if (isset($header))
                <header class="bg-smart-interface-bg/80 md:bg-niebla/20 backdrop-blur-md shadow-lg border border-white/10 mt-6 mx-4 md:mx-auto rounded-2xl overflow-hidden w-auto md:w-fit md:min-w-[600px]">
                    <div class="py-4 px-4 md:px-8 text-center flex justify-center">
                        {{ $header }}
                    </div>
                </header>
            @endif

                <main class="flex-1 overflow-y-auto p-6 bg-transparent">
                    <div class="max-w-7xl mx-auto">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>