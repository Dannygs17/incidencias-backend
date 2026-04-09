<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-smart-text tracking-wide">
            {{ __('Panel de Control de Usuarios') }}
        </h2>
    </x-slot>

    {{-- Agregamos activeTab: 'pending' para controlar las pestañas --}}
    <div class="py-12" x-data="{ activeTab: 'pending', openModal: false, selectedUser: {}, showCorrection: false, showRejection: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Alertas de Sesión --}}
            @if(session('success'))
                <div class="mb-4 p-4 mx-4 sm:mx-0 bg-smart-success/10 border border-smart-success/20 text-smart-success rounded-xl shadow-sm font-bold flex items-center">
                    <span class="material-symbols-outlined mr-2">check_circle</span> {{ session('success') }}
                </div>
            @endif
            @if(session('warning'))
                <div class="mb-4 p-4 mx-4 sm:mx-0 bg-smart-warning/10 border border-smart-warning/20 text-smart-warning rounded-xl shadow-sm font-bold flex items-center">
                    <span class="material-symbols-outlined mr-2">warning</span> {{ session('warning') }}
                </div>
            @endif
            @if(session('danger'))
                <div class="mb-4 p-4 mx-4 sm:mx-0 bg-smart-error/10 border border-smart-error/20 text-smart-error rounded-xl shadow-sm font-bold flex items-center">
                    <span class="material-symbols-outlined mr-2">error</span> {{ session('danger') }}
                </div>
            @endif

            <div class="bg-white dark:bg-[#212121] overflow-hidden shadow-xl sm:rounded-3xl border-t border-b sm:border border-gray-100 dark:border-white/5">
                <div class="p-6 sm:p-8">
                    
                    {{-- Separación de usuarios por estado usando PHP --}}
                    @php
                        $pendientes = $users->whereIn('status', ['pending', 'action_required']);
                        $aprobados = $users->whereIn('status', ['approved', 'invitado']);
                        $rechazados = $users->where('status', 'rejected');
                    @endphp

                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 gap-4">
                        <h3 class="text-xl font-black text-smart-cobalto-dark dark:text-white uppercase tracking-tight flex items-center flex-wrap gap-3">
                            Ciudadanos Registrados 
                            <span class="px-3 py-1 bg-smart-cobalto/10 text-smart-cobalto rounded-full text-xs">{{ $users->count() }}</span>
                        </h3>
                    </div>

                    {{-- NAVEGACIÓN DE PESTAÑAS --}}
                    <div class="flex flex-wrap gap-2 mb-6 border-b border-gray-100 dark:border-white/5 pb-3">
                        <button @click="activeTab = 'pending'" 
                                :class="activeTab === 'pending' ? 'bg-smart-warning/10 text-smart-warning border-smart-warning' : 'border-transparent text-gray-500 hover:bg-gray-50 dark:hover:bg-white/5'"
                                class="px-5 py-2.5 rounded-t-xl sm:rounded-xl font-black uppercase tracking-widest text-[10px] sm:text-xs transition-all border-b-2 sm:border-2">
                            Pendientes ({{ $pendientes->count() }})
                        </button>
                        
                        <button @click="activeTab = 'approved'" 
                                :class="activeTab === 'approved' ? 'bg-smart-success/10 text-smart-success border-smart-success' : 'border-transparent text-gray-500 hover:bg-gray-50 dark:hover:bg-white/5'"
                                class="px-5 py-2.5 rounded-t-xl sm:rounded-xl font-black uppercase tracking-widest text-[10px] sm:text-xs transition-all border-b-2 sm:border-2">
                            Aprobados ({{ $aprobados->count() }})
                        </button>
                        
                        <button @click="activeTab = 'rejected'" 
                                :class="activeTab === 'rejected' ? 'bg-smart-error/10 text-smart-error border-smart-error' : 'border-transparent text-gray-500 hover:bg-gray-50 dark:hover:bg-white/5'"
                                class="px-5 py-2.5 rounded-t-xl sm:rounded-xl font-black uppercase tracking-widest text-[10px] sm:text-xs transition-all border-b-2 sm:border-2">
                            Rechazados ({{ $rechazados->count() }})
                        </button>
                    </div>

                    {{-- CONTENEDOR DE LA TABLA --}}
                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-left min-w-max border-collapse">
                            {{-- Encabezado General --}}
                            <thead>
                                <tr class="text-gray-400 dark:text-smart-linea text-[10px] uppercase tracking-[0.2em] font-black border-b border-gray-100 dark:border-white/5">
                                    <th class="pb-4 px-4 whitespace-nowrap">Información Personal</th>
                                    <th class="pb-4 px-4 text-center whitespace-nowrap">Estado</th>
                                    <th class="pb-4 px-4 text-right whitespace-nowrap">Acciones</th>
                                </tr>
                            </thead>
                            
                            {{-- PESTAÑA: PENDIENTES --}}
                            <tbody x-show="activeTab === 'pending'" class="divide-y divide-gray-50 dark:divide-white/5" style="display: none;">
                                @forelse($pendientes as $user)
                                    @include('admin.row', ['user' => $user])
                                @empty
                                    <tr><td colspan="3" class="py-12 text-center text-gray-400 font-bold uppercase tracking-widest text-xs">No hay usuarios pendientes.</td></tr>
                                @endforelse
                            </tbody>

                            {{-- PESTAÑA: APROBADOS --}}
                            <tbody x-show="activeTab === 'approved'" class="divide-y divide-gray-50 dark:divide-white/5" style="display: none;">
                                @forelse($aprobados as $user)
                                    @include('admin.row', ['user' => $user])
                                @empty
                                    <tr><td colspan="3" class="py-12 text-center text-gray-400 font-bold uppercase tracking-widest text-xs">No hay usuarios aprobados.</td></tr>
                                @endforelse
                            </tbody>

                            {{-- PESTAÑA: RECHAZADOS --}}
                            <tbody x-show="activeTab === 'rejected'" class="divide-y divide-gray-50 dark:divide-white/5" style="display: none;">
                                @forelse($rechazados as $user)
                                    @include('admin.row', ['user' => $user])
                                @empty
                                    <tr><td colspan="3" class="py-12 text-center text-gray-400 font-bold uppercase tracking-widest text-xs">No hay usuarios rechazados.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL DE VALIDACIÓN (IMÁGENES CORREGIDAS) --}}
        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-md" x-cloak>
            <div class="fixed inset-0 bg-black/80 transition-opacity" x-show="openModal" x-transition.opacity @click="openModal = false"></div>
            
            <div class="relative w-full max-w-2xl bg-white dark:bg-[#212121] border dark:border-white/10 rounded-[2rem] sm:rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col max-h-[95vh] sm:max-h-[90vh]" 
                 x-show="openModal" x-transition.scale.95 @click.stop>
                
                <div class="p-4 sm:p-6 border-b dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-black/20 shrink-0">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-black text-smart-cobalto-dark dark:text-white tracking-tighter uppercase">Validación de Identidad</h2>
                        <p class="text-[10px] sm:text-xs font-bold text-gray-400 dark:text-smart-linea uppercase tracking-widest mt-1">Verificación de documentos</p>
                    </div>
                    <button @click="openModal = false" class="text-gray-400 hover:text-smart-error transition text-3xl sm:text-4xl font-light leading-none">×</button>
                </div>

                <div class="p-4 sm:p-8 overflow-y-auto custom-scrollbar">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-8 text-gray-300 mb-8 sm:mb-10">
                        <div class="bg-gray-50 dark:bg-black/20 p-4 sm:p-5 rounded-2xl border border-gray-100 dark:border-white/5">
                            <p class="text-[10px] uppercase text-smart-cobalto font-black tracking-widest mb-1">Nombre Completo</p>
                            <p class="text-base sm:text-lg text-gray-800 dark:text-white font-black tracking-tight" x-text="selectedUser.name"></p>
                        </div>
                        <div class="bg-gray-50 dark:bg-black/20 p-4 sm:p-5 rounded-2xl border border-gray-100 dark:border-white/5 overflow-hidden">
                            <p class="text-[10px] uppercase text-smart-cobalto font-black tracking-widest mb-1">CURP Oficial</p>
                            <p class="text-base sm:text-lg text-gray-800 dark:text-white font-mono font-bold tracking-widest truncate" x-text="selectedUser.curp || 'NO PROPORCIONADO'"></p>
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] uppercase text-gray-400 font-black tracking-[0.2em] mb-4 text-center">Documentación (INE)</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            
                            {{-- VISTA FRONTAL (Se cambió a x-show para evitar errores de Alpine) --}}
                            <div class="group relative" x-show="selectedUser.ine_frente">
                                <p class="text-[10px] font-black mb-2 text-smart-linea uppercase text-center tracking-widest">Vista Frontal</p>
                                {{-- aspect-[8/5] y object-contain asegura que sea tamaño credencial --}}
                                <div class="rounded-2xl border dark:border-white/5 aspect-[8/5] overflow-hidden shadow-inner bg-black/40 flex items-center justify-center">
                                    <img :src="selectedUser.ine_frente ? '/storage/' + selectedUser.ine_frente : ''" 
                                         class="w-full h-full object-contain cursor-zoom-in sm:group-hover:scale-105 transition duration-500" 
                                         @click="window.open('/storage/' + selectedUser.ine_frente)">
                                </div>
                            </div>
                            
                            {{-- VISTA REVERSO --}}
                            <div class="group relative" x-show="selectedUser.ine_reverso">
                                <p class="text-[10px] font-black mb-2 text-smart-linea uppercase text-center tracking-widest">Vista Reverso</p>
                                <div class="rounded-2xl border dark:border-white/5 aspect-[8/5] overflow-hidden shadow-inner bg-black/40 flex items-center justify-center">
                                    <img :src="selectedUser.ine_reverso ? '/storage/' + selectedUser.ine_reverso : ''" 
                                         class="w-full h-full object-contain cursor-zoom-in sm:group-hover:scale-105 transition duration-500" 
                                         @click="window.open('/storage/' + selectedUser.ine_reverso)">
                                </div>
                            </div>
                            
                            {{-- SIN FOTOS --}}
                            <div class="col-span-1 sm:col-span-2 py-8 sm:py-12 bg-gray-50 dark:bg-black/20 rounded-[2rem] border-2 border-dashed dark:border-white/5 text-center text-gray-400 font-bold uppercase tracking-widest text-[10px] sm:text-xs italic px-4" 
                                 x-show="!selectedUser.ine_frente && !selectedUser.ine_reverso">
                                El ciudadano no ha subido fotografías.
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ACCIONES FOOTER --}}
                <div class="p-4 sm:p-6 bg-gray-50 dark:bg-black/40 border-t dark:border-white/5 shrink-0">
                    <div x-show="!showCorrection && !showRejection" x-transition.opacity class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <button x-show="selectedUser.status !== 'rejected'" 
                                @click="showRejection = true" 
                                class="w-full sm:flex-1 bg-white dark:bg-black/20 text-smart-error border border-smart-error/30 hover:bg-smart-error hover:text-white font-black py-3 sm:py-4 rounded-xl sm:rounded-2xl transition-all uppercase text-[10px] sm:text-xs tracking-widest shadow-sm">
                            Bloqueo Definitivo
                        </button>

                        <button x-show="selectedUser.status !== 'approved' && selectedUser.status !== 'rejected'" 
                                @click="showCorrection = true" 
                                class="w-full sm:flex-1 bg-white dark:bg-black/20 text-orange-500 border border-orange-500/30 hover:bg-orange-500 hover:text-white font-black py-3 sm:py-4 rounded-xl sm:rounded-2xl transition-all uppercase text-[10px] sm:text-xs tracking-widest shadow-sm">
                            Pedir Corrección
                        </button>

                        <form x-show="selectedUser.status !== 'approved'" 
                              :action="'/admin/user/' + selectedUser.id + '/approve'" 
                              method="POST" class="w-full sm:flex-1">
                            @csrf 
                            <button type="submit" class="w-full bg-smart-success text-white font-black py-3 sm:py-4 rounded-xl sm:rounded-2xl transition-all uppercase text-[10px] sm:text-xs tracking-widest shadow-lg shadow-smart-success/20 hover:brightness-110 active:scale-95 flex justify-center items-center gap-2">
                                <span class="material-symbols-outlined text-base sm:text-lg">verified_user</span> Aprobar Acceso
                            </button>
                        </form>

                        <div x-show="selectedUser.status === 'approved'" class="w-full text-center py-3 sm:py-4 text-smart-success font-black text-[10px] sm:text-sm uppercase tracking-[0.2em] flex justify-center items-center gap-2">
                            <span class="material-symbols-outlined text-base sm:text-lg">check_circle</span> Ciudadano Verificado
                        </div>
                    </div>

                    {{-- FORM RECHAZO --}}
                    <div x-show="showRejection" x-transition.opacity>
                        <form :action="'/admin/user/' + selectedUser.id + '/reject'" method="POST">
                            @csrf
                            <label class="block text-[10px] font-black text-smart-error uppercase tracking-[0.2em] mb-2 sm:mb-3">Motivo del Rechazo Definitivo</label>
                            <textarea name="motivo" required rows="3" 
                                      class="w-full bg-white dark:bg-black/40 border-gray-200 dark:border-white/10 rounded-xl sm:rounded-2xl text-gray-700 dark:text-white p-3 sm:p-4 focus:ring-smart-error text-sm font-medium mb-3 sm:mb-4" 
                                      placeholder="Explica detalladamente por qué se revoca el acceso..."></textarea>
                            <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 sm:gap-3">
                                <button type="button" @click="showRejection = false" class="w-full sm:w-auto text-gray-400 font-black uppercase text-[10px] tracking-widest px-4 py-3 sm:py-2">Cancelar</button>
                                <button type="submit" class="w-full sm:w-auto bg-smart-error text-white font-black px-6 py-3 rounded-xl shadow-lg uppercase text-[10px] sm:text-xs tracking-widest hover:brightness-110">Rechazar y Notificar</button>
                            </div>
                        </form>
                    </div>

                    {{-- FORM CORRECCIÓN --}}
                    <div x-show="showCorrection" x-transition.opacity>
                        <form :action="'/admin/user/' + selectedUser.id + '/corregir'" method="POST" x-data="{ selectedFields: [] }">
                            @csrf
                            <label class="block text-[10px] font-black text-orange-400 uppercase tracking-[0.2em] mb-2 sm:mb-3">Documentos a Re-enviar</label>
                            <div class="grid grid-cols-3 gap-2 sm:gap-3 mb-4 sm:mb-5">
                                <label class="flex flex-col items-center justify-center p-2 sm:p-3 rounded-xl border dark:border-white/5 cursor-pointer transition-all" :class="selectedFields.includes('curp') ? 'bg-orange-500/20 border-orange-500' : 'bg-black/10'">
                                    <input type="checkbox" name="correction_fields[]" value="curp" x-model="selectedFields" class="hidden">
                                    <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-tighter text-center" :class="selectedFields.includes('curp') ? 'text-orange-500' : 'text-gray-500'">CURP</span>
                                </label>
                                <label class="flex flex-col items-center justify-center p-2 sm:p-3 rounded-xl border dark:border-white/5 cursor-pointer transition-all" :class="selectedFields.includes('ine_frente') ? 'bg-orange-500/20 border-orange-500' : 'bg-black/10'">
                                    <input type="checkbox" name="correction_fields[]" value="ine_frente" x-model="selectedFields" class="hidden">
                                    <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-tighter text-center" :class="selectedFields.includes('ine_frente') ? 'text-orange-500' : 'text-gray-500'">INE (F)</span>
                                </label>
                                <label class="flex flex-col items-center justify-center p-2 sm:p-3 rounded-xl border dark:border-white/5 cursor-pointer transition-all" :class="selectedFields.includes('ine_reverso') ? 'bg-orange-500/20 border-orange-500' : 'bg-black/10'">
                                    <input type="checkbox" name="correction_fields[]" value="ine_reverso" x-model="selectedFields" class="hidden">
                                    <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-tighter text-center" :class="selectedFields.includes('ine_reverso') ? 'text-orange-500' : 'text-gray-500'">INE (R)</span>
                                </label>
                            </div>
                            <textarea name="motivo" required rows="2" 
                                      class="w-full bg-white dark:bg-black/40 border-gray-200 dark:border-white/10 rounded-xl sm:rounded-2xl text-gray-700 dark:text-white p-3 sm:p-4 focus:ring-orange-500 text-sm font-medium mb-3 sm:mb-4" 
                                      placeholder="Instrucciones para el ciudadano..."></textarea>
                            <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 sm:gap-3">
                                <button type="button" @click="showCorrection = false; selectedFields = []" class="w-full sm:w-auto text-gray-400 font-black uppercase text-[10px] tracking-widest px-4 py-3 sm:py-2">Cancelar</button>
                                <button type="submit" :disabled="selectedFields.length === 0" class="w-full sm:w-auto bg-orange-500 text-white font-black px-6 py-3 rounded-xl shadow-lg uppercase text-[10px] sm:text-xs tracking-widest disabled:opacity-50">Solicitar Cambio</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>