<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Panel de Control de Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ openModal: false, selectedUser: {}, showCorrection: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-500/20 border border-green-500 text-green-100 rounded-lg flex items-center justify-between">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('warning'))
                <div class="mb-4 p-4 bg-orange-500/20 border border-orange-500 text-orange-100 rounded-lg">
                    {{ session('warning') }}
                </div>
            @endif
            @if(session('danger'))
                <div class="mb-4 p-4 bg-red-500/20 border border-red-500 text-red-100 rounded-lg">
                    {{ session('danger') }}
                </div>
            @endif

            <div class="bg-gray-900 overflow-hidden shadow-xl sm:rounded-lg border border-gray-800">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-200">
                            Usuarios Registrados <span class="ml-2 px-2 py-1 bg-gray-800 text-gray-400 rounded-md text-sm">{{ $users->count() }}</span>
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-gray-400 text-xs uppercase tracking-widest border-b border-gray-800">
                                    <th class="pb-4 px-4">Información Personal</th>
                                    <th class="pb-4 px-4 text-center">Estado</th>
                                    <th class="pb-4 px-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800">
                                @forelse($users as $user)
                                    <tr class="hover:bg-gray-800/50 transition duration-150">
                                        <td class="py-4 px-4">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-semibold text-gray-100">{{ $user->name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 text-center text-sm">
                                            @php
                                                $statusClasses = [
                                                    'pending'         => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                                                    'approved'        => 'bg-green-500/10 text-green-500 border-green-500/20',
                                                    'rejected'        => 'bg-red-500/10 text-red-500 border-red-500/20',
                                                    'invitado'        => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                                    'action_required' => 'bg-orange-500/10 text-orange-500 border-orange-500/20',
                                                ];
                                                
                                                $nombresEstatus = [
                                                    'approved' => 'Aprobado',
                                                    'pending' => 'Pendiente',
                                                    'action_required' => 'Con Observaciones',
                                                    'rejected' => 'Rechazado',
                                                    'invitado' => 'Invitado'
                                                ];
                                            @endphp
                                            <span class="px-3 py-1 border rounded-full font-medium {{ $statusClasses[$user->status] ?? $statusClasses['pending'] }}">
                                                {{ $nombresEstatus[$user->status] ?? ucfirst($user->status) }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 text-right">
                                            <div class="flex justify-end gap-3">
                                                <button @click="openModal = true; showCorrection = false; selectedUser = {{ json_encode($user) }}" 
                                                        class="p-2 text-blue-400 hover:bg-blue-500/10 rounded-lg transition" title="Ver Detalles">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </button>

                                                <form action="{{ route('admin.eliminar', $user->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar permanentemente a este usuario?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="p-2 text-red-400 hover:bg-red-500/10 rounded-lg transition" title="Eliminar">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="py-10 text-center text-gray-500">No hay ciudadanos registrados aún.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto" x-cloak>
            <div class="fixed inset-0 bg-black/80 transition-opacity" x-show="openModal" x-transition.opacity @click="openModal = false"></div>
            
            <div class="relative w-full max-w-2xl bg-gray-900 border border-gray-800 rounded-2xl shadow-2xl p-6" 
                 x-show="openModal" x-transition.scale.95 @click.stop>
                
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-white">Validación de Identidad</h2>
                        <p class="text-sm text-gray-500">Verifica los documentos antes de aprobar el acceso.</p>
                    </div>
                    <button @click="openModal = false" class="text-gray-500 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-300">
                    <div>
                        <p class="text-xs uppercase text-gray-500 font-bold mb-1">Nombre del Ciudadano</p>
                        <p class="text-lg text-white font-semibold" x-text="selectedUser.name"></p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-gray-500 font-bold mb-1">CURP</p>
                        <p class="text-lg text-white font-mono" x-text="selectedUser.curp || 'No proporcionada'"></p>
                    </div>
                </div>

                <div class="mt-8">
                    <p class="text-xs uppercase text-gray-500 font-bold mb-4">Documentación Oficial (INE/IFE)</p>
                    <div class="grid grid-cols-2 gap-4">
                        <template x-if="selectedUser.ine_frente">
                            <div class="group relative">
                                <p class="text-xs mb-2 text-gray-400">Vista Frontal</p>
                                <img :src="'/storage/' + selectedUser.ine_frente" 
                                     class="rounded-lg border border-gray-700 w-full h-44 object-cover cursor-zoom-in hover:border-indigo-500 transition" 
                                     @click="window.open('/storage/' + selectedUser.ine_frente)">
                            </div>
                        </template>
                        <template x-if="selectedUser.ine_reverso">
                            <div class="group relative">
                                <p class="text-xs mb-2 text-gray-400">Vista Posterior</p>
                                <img :src="'/storage/' + selectedUser.ine_reverso" 
                                     class="rounded-lg border border-gray-700 w-full h-44 object-cover cursor-zoom-in hover:border-indigo-500 transition" 
                                     @click="window.open('/storage/' + selectedUser.ine_reverso)">
                            </div>
                        </template>
                        <template x-if="!selectedUser.ine_frente && !selectedUser.ine_reverso">
                            <div class="col-span-2 py-8 bg-gray-800/50 rounded-xl text-center text-gray-500 italic">
                                El usuario no ha subido fotografías de su identificación.
                            </div>
                        </template>
                    </div>
                </div>

                <div class="mt-10 border-t border-gray-800 pt-6">
                    
                    <div x-show="!showCorrection" x-transition.opacity class="flex justify-end items-center gap-3">
                      
                        <form x-show="selectedUser.status !== 'rejected'" 
                              :action="'/admin/user/' + selectedUser.id + '/reject'" 
                              method="POST" onsubmit="return confirm('¿Estás seguro de BLOQUEAR permanentemente a este usuario?')">
                            @csrf 
                            <button type="submit" class="text-red-500 hover:bg-red-500/10 px-4 py-2 rounded-lg font-bold transition">
                                Bloqueo Definitivo
                            </button>
                        </form>

                        <button x-show="selectedUser.status !== 'approved' && selectedUser.status !== 'rejected'" 
                                @click="showCorrection = true" 
                                type="button" 
                                class="bg-orange-600/20 text-orange-500 border border-orange-600/50 hover:bg-orange-600 hover:text-white px-4 py-2 rounded-lg font-bold transition">
                            Pedir Corrección
                        </button>

                        <form x-show="selectedUser.status !== 'approved'" 
                              :action="'/admin/user/' + selectedUser.id + '/approve'" 
                              method="POST">
                            @csrf 
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-bold shadow-lg shadow-green-900/20 transition">
                                Aprobar y Notificar
                            </button>
                        </form>

                        <template x-if="selectedUser.status === 'approved'">
                            <span class="text-green-500 font-bold flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Usuario ya verificado
                            </span>
                        </template>
                    </div>

                    <div x-show="showCorrection" x-transition.opacity style="display: none;">
                        
                        <form :action="'/admin/user/' + selectedUser.id + '/corregir'" method="POST" x-data="{ selectedFields: [] }">
                            @csrf

                            <label class="block text-sm font-bold text-orange-400 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                ¿Qué documentos debe volver a enviar? (Mínimo uno)
                            </label>
                            <div class="flex flex-col gap-3 mb-5 bg-gray-800/50 p-4 rounded-xl border border-gray-700">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="correction_fields[]" value="curp" x-model="selectedFields" 
                                           class="form-checkbox h-5 w-5 text-orange-500 rounded border-gray-600 bg-gray-900 focus:ring-orange-500 focus:ring-offset-gray-900">
                                    <span class="ml-3 text-sm text-gray-300 font-medium">Clave CURP</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="correction_fields[]" value="ine_frente" x-model="selectedFields" 
                                           class="form-checkbox h-5 w-5 text-orange-500 rounded border-gray-600 bg-gray-900 focus:ring-orange-500 focus:ring-offset-gray-900">
                                    <span class="ml-3 text-sm text-gray-300 font-medium">Fotografía de INE (Frente)</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="correction_fields[]" value="ine_reverso" x-model="selectedFields" 
                                           class="form-checkbox h-5 w-5 text-orange-500 rounded border-gray-600 bg-gray-900 focus:ring-orange-500 focus:ring-offset-gray-900">
                                    <span class="ml-3 text-sm text-gray-300 font-medium">Fotografía de INE (Reverso)</span>
                                </label>
                            </div>

                            <label class="block text-sm font-bold text-orange-400 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                Motivo de la corrección
                            </label>
                            <p class="text-xs text-gray-500 mb-3">Este mensaje acompañará la solicitud en la app móvil.</p>
                            
                            <textarea name="motivo" required rows="3" 
                                      class="w-full bg-gray-900 border border-gray-700 rounded-xl text-white p-4 focus:ring-orange-500 focus:border-orange-500 mb-4 placeholder-gray-600" 
                                      placeholder="Ej: La fotografía del reverso está borrosa y no se distinguen los datos..."></textarea>
                            
                            <div class="flex justify-end gap-3">
                                <button type="button" @click="showCorrection = false; selectedFields = []" class="text-gray-400 hover:text-white px-4 py-2 transition font-medium">
                                    Cancelar
                                </button>
                                <button type="submit" 
                                        :disabled="selectedFields.length === 0"
                                        :class="selectedFields.length === 0 ? 'opacity-50 cursor-not-allowed bg-orange-500/50' : 'bg-orange-500 hover:bg-orange-600 shadow-lg shadow-orange-900/20'"
                                        class="text-white px-6 py-2 rounded-lg font-bold transition">
                                    Enviar al Ciudadano
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>