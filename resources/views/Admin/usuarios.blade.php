<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Panel de Control de Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ openModal: false, selectedUser: {} }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-500/20 border border-green-500 text-green-100 rounded-lg">
                    {{ session('success') }}
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
                                                <div class="h-10 w-10 rounded-full bg-gray-700 flex items-center justify-center text-gray-400 font-bold">
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
                                                    'pending' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                                                    'approved' => 'bg-green-500/10 text-green-500 border-green-500/20',
                                                    'rejected' => 'bg-red-500/10 text-red-500 border-red-500/20',
                                                ];
                                            @endphp
                                            <span class="px-3 py-1 border rounded-full font-medium {{ $statusClasses[$user->status] ?? $statusClasses['pending'] }}">
                                                {{ ucfirst($user->status) }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 text-right">
                                            <div class="flex justify-end gap-3">
                                                <button @click="openModal = true; selectedUser = {{ json_encode($user) }}" 
                                                        class="p-2 text-blue-400 hover:bg-blue-500/10 rounded-lg transition" title="Ver Detalles">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </button>

                                                <form action="{{ route('admin.eliminar', $user->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="p-2 text-red-400 hover:bg-red-500/10 rounded-lg transition" title="Eliminar">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="py-10 text-center text-gray-500">No hay registros.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto" x-cloak>
            <div class="fixed inset-0 bg-black/70 transition-opacity" @click="openModal = false"></div>
            
            <div class="relative w-full max-w-2xl bg-gray-900 border border-gray-800 rounded-2xl shadow-2xl p-6" @click.stop>
                <div class="flex justify-between items-start mb-6">
                    <h2 class="text-2xl font-bold text-white">Detalles del Usuario</h2>
                    <button @click="openModal = false" class="text-gray-500 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-300">
                    <div>
                        <p class="text-xs uppercase text-gray-500 font-bold mb-1">Nombre Completo</p>
                        <p class="text-lg text-white" x-text="selectedUser.name"></p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-gray-500 font-bold mb-1">CURP</p>
                        <p class="text-lg text-white" x-text="selectedUser.curp || 'No proporcionada'"></p>
                    </div>
                </div>

                <div class="mt-8">
                    <p class="text-xs uppercase text-gray-500 font-bold mb-4">Documentación (INE)</p>
                    <div class="grid grid-cols-2 gap-4">
                        <template x-if="selectedUser.ine_frente">
                            <div>
                                <p class="text-xs mb-2 text-center">Frente</p>
                                <img :src="'/storage/' + selectedUser.ine_frente" class="rounded-lg border border-gray-700 w-full h-40 object-cover cursor-pointer hover:scale-105 transition" @click="window.open('/storage/' + selectedUser.ine_frente)">
                            </div>
                        </template>
                        <template x-if="selectedUser.ine_reverso">
                            <div>
                                <p class="text-xs mb-2 text-center">Reverso</p>
                                <img :src="'/storage/' + selectedUser.ine_reverso" class="rounded-lg border border-gray-700 w-full h-40 object-cover cursor-pointer hover:scale-105 transition" @click="window.open('/storage/' + selectedUser.ine_reverso)">
                            </div>
                        </template>
                    </div>
                </div>

                <div class="mt-10 flex justify-end gap-3">
                    <form :action="'/admin/user/' + selectedUser.id + '/reject'" method="POST">
                        @csrf <button class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-bold transition">Rechazar</button>
                    </form>
                    <form :action="'/admin/user/' + selectedUser.id + '/approve'" method="POST">
                        @csrf <button class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-bold transition">Aprobar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>