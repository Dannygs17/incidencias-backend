<tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition duration-150 group">
    {{-- Celda 1: Info --}}
    <td class="py-4 px-4 align-middle">
        <div class="flex items-center">
            <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-2xl bg-smart-cobalto/10 dark:bg-smart-cobalto/20 flex items-center justify-center text-smart-cobalto font-black text-lg sm:text-xl shadow-inner group-hover:scale-110 transition duration-300 shrink-0">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div class="ml-4 overflow-hidden">
                <div class="text-sm font-black text-gray-800 dark:text-white tracking-tight truncate">{{ $user->name }}</div>
                <div class="text-xs text-gray-500 font-medium truncate">{{ $user->email }}</div>
            </div>
        </div>
    </td>
    
    {{-- Celda 2: Estado --}}
    <td class="py-4 px-4 text-center align-middle whitespace-nowrap">
        @php
            $statusClasses = [
                'pending'         => 'bg-smart-warning/10 text-smart-warning border-smart-warning/20',
                'approved'        => 'bg-smart-success/10 text-smart-success border-smart-success/20',
                'rejected'        => 'bg-smart-error/10 text-smart-error border-smart-error/20',
                'invitado'        => 'bg-smart-action/10 text-smart-action border-smart-action/20',
                'action_required' => 'bg-orange-500/10 text-orange-500 border-orange-500/20',
            ];
            
            $nombresEstatus = [
                'approved' => 'Aprobado',
                'pending' => 'Pendiente',
                'action_required' => 'Observaciones',
                'rejected' => 'Rechazado',
                'invitado' => 'Invitado'
            ];
        @endphp
        <span class="inline-block px-3 py-1 border rounded-full text-[10px] font-black uppercase tracking-widest {{ $statusClasses[$user->status] ?? $statusClasses['pending'] }}">
            {{ $nombresEstatus[$user->status] ?? ucfirst($user->status) }}
        </span>
    </td>
    
    {{-- Celda 3: Acciones --}}
    <td class="py-4 px-4 text-right align-middle whitespace-nowrap">
        <div class="flex justify-end gap-2">
            <button @click="openModal = true; showCorrection = false; showRejection = false; selectedUser = {{ json_encode($user) }}" 
                    class="flex justify-center items-center p-2 text-smart-action bg-smart-action/10 hover:bg-smart-action hover:text-white rounded-xl transition duration-300 shadow-sm" title="Ver Detalles">
                <span class="material-symbols-outlined text-xl">visibility</span>
            </button>

            <form action="{{ route('admin.eliminar', $user->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar permanentemente a este usuario?')" class="inline-block">
                @csrf @method('DELETE')
                <button type="submit" class="flex justify-center items-center p-2 text-smart-error bg-smart-error/10 hover:bg-smart-error hover:text-white rounded-xl transition duration-300 shadow-sm" title="Eliminar">
                    <span class="material-symbols-outlined text-xl">delete_sweep</span>
                </button>
            </form>
        </div>
    </td>
</tr>