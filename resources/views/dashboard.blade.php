<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight flex items-center gap-3">
            <span class="material-symbols-outlined text-indigo-400 text-3xl">space_dashboard</span>
            {{ __('Centro de Mando') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="bg-gradient-to-r from-indigo-900 to-gray-900 border border-indigo-500/30 rounded-2xl shadow-2xl p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-40 h-40 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>

                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="p-4 bg-indigo-500/20 rounded-full border border-indigo-500/30 text-indigo-300">
                            <span class="material-symbols-outlined text-5xl">waving_hand</span>
                        </div>
                        <div>
                            <h1 class="text-3xl font-black text-white tracking-tight">¡Hola, {{ auth()->user()->name }}!</h1>
                            <p class="text-indigo-200 mt-1 text-lg">Bienvenido al panel de control de incidencias.</p>
                        </div>
                    </div>
                    
                    <div class="text-right hidden md:block text-gray-400 bg-gray-900/50 p-4 rounded-xl border border-gray-700/50">
                        <p class="text-sm font-bold uppercase tracking-widest text-indigo-300">
                            <span class="material-symbols-outlined text-[16px] inline-block align-text-bottom">calendar_today</span>
                            HOY
                        </p>
                        <p class="text-xs mt-1 text-gray-400">{{ now()->translatedFormat('l, d \d\e F Y') }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-amber-500">notifications_active</span>
                    Requiere tu atención
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="bg-gray-900 border border-gray-800 rounded-xl shadow-lg p-6 flex flex-col justify-between border-l-4 border-l-amber-500 hover:border-gray-700 transition group">
                        <div class="flex items-start gap-4">
                            <div class="p-3 bg-amber-500/10 rounded-full text-amber-400">
                                <span class="material-symbols-outlined text-4xl group-hover:scale-110 transition-transform">how_to_reg</span>
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-lg mb-1">Cuentas por Aprobar</h4>
                                <p class="text-gray-400 text-sm">
                                    Tienes <span class="font-black text-amber-400 text-base">{{ $pendientesAprobar }}</span> ciudadanos esperando validación.
                                </p>
                            </div>
                        </div>
                        <div class="mt-6 text-right">
                            <a href="{{ route('admin.usuarios') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-800 hover:bg-gray-700 border border-gray-700 hover:border-amber-500/50 text-amber-400 text-xs font-bold uppercase rounded-lg transition shadow-sm">
                                Revisar Solicitudes <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>

                    <div class="bg-gray-900 border border-gray-800 rounded-xl shadow-lg p-6 flex flex-col justify-between border-l-4 border-l-blue-500 hover:border-gray-700 transition group">
                        <div class="flex items-start gap-4">
                            <div class="p-3 bg-blue-500/10 rounded-full text-blue-400">
                                <span class="material-symbols-outlined text-4xl group-hover:scale-110 transition-transform">campaign</span>
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-lg mb-1">Nuevas Incidencias</h4>
                                <p class="text-gray-400 text-sm">
                                    Hay <span class="font-black text-blue-400 text-base">{{ $reportesNuevos }}</span> reportes recientes sin atender.
                                </p>
                            </div>
                        </div>
                        <div class="mt-6 text-right">
                            <a href="{{ route('admin.incidencias') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-800 hover:bg-gray-700 border border-gray-700 hover:border-blue-500/50 text-blue-400 text-xs font-bold uppercase rounded-lg transition shadow-sm">
                                Ir a la Bandeja <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

           

        </div>
    </div>
</x-app-layout>