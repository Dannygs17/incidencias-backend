<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <h2 class="font-black text-2xl text-smart-text tracking-wide">
                {{ __('Centro de Mando') }}
            </h2>
        </div>
    </x-slot>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Banner de Bienvenida con Degradado Smart --}}
            <div class="bg-gradient-to-r from-smart-cobalto-dark to-smart-cobalto border border-white/10 rounded-3xl shadow-2xl p-8 relative overflow-hidden">
                {{-- Efecto de luz ambiental --}}
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-smart-niebla rounded-full mix-blend-overlay filter blur-3xl opacity-20"></div>

                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="p-4 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 text-white shadow-inner">
                            <span class="material-symbols-outlined text-5xl">waving_hand</span>
                        </div>
                        <div>
                            <h1 class="text-3xl font-black text-white tracking-tight">¡Hola, {{ auth()->user()->name }}!</h1>
                            <p class="text-smart-niebla/80 mt-1 text-lg font-medium">Bienvenido al panel de control de incidencias.</p>
                        </div>
                    </div>
                    
                    {{-- Badge de Fecha Estilizado --}}
                    <div class="text-right hidden md:block text-white bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/10 shadow-lg">
                        <p class="text-xs font-black uppercase tracking-widest text-smart-niebla">
                            <span class="material-symbols-outlined text-[16px] inline-block align-text-bottom mr-1">calendar_today</span>
                            HOY
                        </p>
                        <p class="text-sm mt-1 font-bold opacity-90">{{ now()->translatedFormat('l, d \d\e F Y') }}</p>
                    </div>
                </div>
            </div>

            {{-- Sección de Alertas --}}
            <div>
                <h3 class="text-sm font-black text-smart-text/60 dark:text-smart-niebla/60 uppercase tracking-[0.25em] mb-6 flex items-center gap-2 px-2 transition-colors">
                    <span class="material-symbols-outlined text-smart-warning animate-pulse">notifications_active</span>
                    Requiere tu atención
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    {{-- Card: Cuentas por Aprobar (Efecto Glass mantenido) --}}
                    <div class="bg-smart-cobalto-dark/10 dark:bg-[#212121]/80 backdrop-blur-md border border-smart-cobalto-dark/10 dark:border-white/10 rounded-3xl shadow-xl p-6 flex flex-col justify-between hover:bg-white/50 dark:hover:bg-[#212121] transition-all duration-300 group">
                        <div class="flex items-start gap-4">
                            <div class="p-4 bg-smart-warning/20 rounded-2xl text-smart-warning shadow-sm border border-smart-warning/20">
                                <span class="material-symbols-outlined text-4xl group-hover:rotate-12 transition-transform duration-300">how_to_reg</span>
                            </div>
                            <div>
                                <h4 class="text-smart-cobalto-dark dark:text-white font-black text-xl mb-1 transition-colors">Cuentas por Aprobar</h4>
                                <p class="text-smart-cobalto-dark/70 dark:text-smart-niebla/70 text-sm font-medium leading-relaxed transition-colors">
                                    Tienes <span class="px-2 py-0.5 bg-smart-warning/20 text-smart-warning rounded-lg font-black">{{ $pendientesAprobar }}</span> ciudadanos esperando validación.
                                </p>
                            </div>
                        </div>
                        <div class="mt-8">
                            <a href="{{ route('admin.usuarios') }}" class="flex items-center justify-center gap-2 w-full py-3 bg-smart-cobalto-dark dark:bg-smart-cobalto text-white text-xs font-black uppercase rounded-xl transition-all shadow-lg hover:shadow-smart-cobalto/40 hover:-translate-y-1 active:scale-95">
                                Revisar Solicitudes <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>

                    {{-- Card: Nuevas Incidencias (Efecto Glass mantenido) --}}
                    <div class="bg-smart-cobalto-dark/10 dark:bg-[#212121]/80 backdrop-blur-md border border-smart-cobalto-dark/10 dark:border-white/10 rounded-3xl shadow-xl p-6 flex flex-col justify-between hover:bg-white/50 dark:hover:bg-[#212121] transition-all duration-300 group">
                        <div class="flex items-start gap-4">
                            <div class="p-4 bg-smart-action/20 rounded-2xl text-smart-action shadow-sm border border-smart-action/20">
                                <span class="material-symbols-outlined text-4xl group-hover:scale-110 transition-transform duration-300">campaign</span>
                            </div>
                            <div>
                                <h4 class="text-smart-cobalto-dark dark:text-white font-black text-xl mb-1 transition-colors">Nuevas Incidencias</h4>
                                <p class="text-smart-cobalto-dark/70 dark:text-smart-niebla/70 text-sm font-medium leading-relaxed transition-colors">
                                    Hay <span class="px-2 py-0.5 bg-smart-action/20 text-smart-action rounded-lg font-black">{{ $reportesNuevos }}</span> reportes recientes sin atender.
                                </p>
                            </div>
                        </div>
                        <div class="mt-8">
                            <a href="{{ route('admin.incidencias') }}" class="flex items-center justify-center gap-2 w-full py-3 bg-smart-action text-white text-xs font-black uppercase rounded-xl transition-all shadow-lg hover:shadow-smart-action/40 hover:-translate-y-1 active:scale-95">
                                Ir a la Bandeja <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>