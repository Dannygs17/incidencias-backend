<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-smart-text tracking-wide">
            {{ __('Gestión de Categorías e Iconos') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Formulario de Agregado --}}
            <div class="bg-white dark:bg-[#212121] overflow-hidden shadow-sm rounded-2xl sm:rounded-3xl mb-6 border border-gray-100 dark:border-white/5">
                <div class="p-6 sm:p-8">
                    <h3 class="text-lg font-black mb-6 uppercase tracking-tighter text-smart-cobalto dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined">add_circle</span>
                        Agregar Nueva Categoría
                    </h3>
                    <form action="{{ route('categorias.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-smart-linea mb-1">Nombre</label>
                                <input type="text" name="nombre" class="mt-1 block w-full rounded-xl border-gray-300 dark:bg-black/20 dark:border-white/10 dark:text-white focus:ring-smart-cobalto shadow-sm p-3" placeholder="Ej: Via Pública" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-smart-linea mb-1">Nombre del Icono (Google)</label>
                                <div class="flex gap-3">
                                    <div class="relative flex-1">
                                        <input type="text" name="icono" id="icono_input" 
                                               onkeyup="updatePreview(this.value, 'icon_preview')"
                                               class="block w-full rounded-xl border-gray-300 dark:bg-black/20 dark:border-white/10 dark:text-white focus:ring-smart-cobalto shadow-sm p-3" 
                                               placeholder="Ej: lightbulb" required>
                                    </div>
                                    
                                    <div class="w-12 h-12 flex items-center justify-center bg-gray-50 dark:bg-black/40 rounded-xl border dark:border-white/10 shrink-0">
                                        <span id="icon_preview" class="material-symbols-outlined text-3xl text-smart-cobalto">help</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <a href="https://fonts.google.com/icons?icon.set=Material+Symbols" target="_blank" class="text-[10px] font-bold uppercase tracking-widest text-smart-cobalto hover:underline mt-2 inline-block">
                                        Catálogo de Iconos →
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end border-t dark:border-white/5 pt-6">
                            <button type="submit" class="w-full sm:w-auto bg-smart-cobalto hover:bg-smart-cobalto-dark text-white font-black py-3.5 px-10 rounded-xl shadow-lg transition duration-200 uppercase text-xs tracking-widest">
                                Guardar Categoría
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Listado de Categorías --}}
            <div class="bg-white dark:bg-[#212121] overflow-hidden shadow-sm rounded-2xl sm:rounded-3xl border border-gray-100 dark:border-white/5">
                <div class="p-0 sm:p-8"> {{-- P-0 en móvil para que la tabla/tarjetas toquen los bordes --}}
                    <div class="w-full">
                        <table class="w-full text-left border-collapse block md:table">
                            {{-- Encabezado: Oculto en móvil --}}
                            <thead class="hidden md:table-header-group">
                                <tr class="border-b border-gray-200 dark:border-white/5 bg-gray-50 dark:bg-black/20 text-[10px] uppercase tracking-[0.2em] font-black text-gray-400 dark:text-smart-linea">
                                    <th class="py-4 px-6 text-center w-32">Icono</th>
                                    <th class="py-4 px-6">Nombre de Categoría</th>
                                    <th class="py-4 px-6 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-white/5 block md:table-row-group">
                                @foreach($categorias as $cat)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition duration-150 block md:table-row p-5 sm:p-0">
                                    {{-- Celda Icono --}}
                                    <td class="block md:table-cell py-2 md:py-4 px-0 md:px-6 md:text-center">
                                        <div class="flex items-center md:justify-center gap-4 md:gap-0">
                                            <div class="bg-smart-cobalto/10 dark:bg-smart-cobalto/20 p-3 rounded-2xl inline-block group-hover:scale-110 transition duration-300">
                                                <span class="material-symbols-outlined text-3xl text-smart-cobalto">
                                                    {{ $cat->icono }}
                                                </span>
                                            </div>
                                            {{-- Label solo móvil --}}
                                            <span class="md:hidden font-black text-gray-800 dark:text-white uppercase text-xs tracking-widest">Previsualización</span>
                                        </div>
                                    </td>

                                    {{-- Celda Nombre --}}
                                    <td class="block md:table-cell py-2 md:py-4 px-0 md:px-6">
                                        <div class="md:hidden text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nombre:</div>
                                        <div class="font-black text-lg md:text-xl text-gray-800 dark:text-white tracking-tight">
                                            {{ $cat->nombre }}
                                        </div>
                                    </td>

                                    {{-- Celda Acciones --}}
                                    <td class="block md:table-cell py-4 md:py-4 px-0 md:px-6 md:text-right">
                                        <div class="flex justify-start md:justify-end items-center gap-3">
                                            <button type="button" onclick="abrirModalEditar({{ $cat->id }}, '{{ addslashes($cat->nombre) }}', '{{ $cat->icono }}')" class="flex-1 md:flex-none text-center bg-white dark:bg-black/20 text-smart-cobalto font-black text-[10px] uppercase tracking-widest px-6 py-3 border border-smart-cobalto/30 rounded-xl hover:bg-smart-cobalto hover:text-white transition duration-200 shadow-sm">
                                                Editar
                                            </button>

                                            <form action="{{ route('categorias.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('¿Borrar esta categoría permanentemente?')" class="flex-1 md:flex-none">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-full text-center bg-white dark:bg-black/20 text-smart-error font-black text-[10px] uppercase tracking-widest px-6 py-3 border border-smart-error/30 rounded-xl hover:bg-smart-error hover:text-white transition duration-200 shadow-sm">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal de Edición --}}
    <div id="modal_editar" class="fixed inset-0 bg-black/90 hidden items-center justify-center z-[100] p-4 backdrop-blur-md transition-all duration-300">
        <div class="bg-white dark:bg-[#212121] w-full max-w-lg shadow-2xl rounded-[2rem] overflow-hidden border dark:border-white/10 transform transition-all">
            
            <div class="p-6 border-b dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-black/20">
                <div>
                    <h3 class="text-xl font-black text-gray-800 dark:text-white flex items-center gap-2 uppercase tracking-tighter">
                        Actualizar Categoría
                    </h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Modifica el nombre o icono</p>
                </div>
                <button type="button" onclick="cerrarModalEditar()" class="text-gray-400 hover:text-smart-error transition text-4xl font-light">&times;</button>
            </div>
            
            <div class="p-8">
                <form id="form_editar" method="POST">
                    @csrf 
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-smart-linea mb-1">Nuevo Nombre</label>
                            <input type="text" name="nombre" id="edit_nombre" class="mt-1 block w-full rounded-xl border-gray-300 dark:bg-black/20 dark:border-white/10 dark:text-white focus:ring-smart-cobalto shadow-sm p-3" required>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-smart-linea mb-1">Nuevo Icono (Google)</label>
                            <div class="flex gap-3">
                                <input type="text" name="icono" id="edit_icono" 
                                       onkeyup="updatePreview(this.value, 'edit_icon_preview')"
                                       class="mt-1 block w-full rounded-xl border-gray-300 dark:bg-black/20 dark:border-white/10 dark:text-white focus:ring-smart-cobalto shadow-sm p-3" required>
                                
                                <div class="w-12 h-12 flex items-center justify-center bg-gray-50 dark:bg-black/40 rounded-xl border dark:border-white/10 shrink-0">
                                    <span id="edit_icon_preview" class="material-symbols-outlined text-3xl text-smart-cobalto">help</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 flex flex-col sm:flex-row justify-end gap-3 border-t dark:border-white/5 pt-6">
                        <button type="button" onclick="cerrarModalEditar()" class="bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 text-gray-500 font-black py-4 px-8 rounded-xl transition duration-200 text-[10px] uppercase tracking-widest">
                            Cancelar
                        </button>
                        <button type="submit" class="bg-smart-cobalto hover:bg-smart-cobalto-dark text-white font-black py-4 px-10 rounded-xl shadow-lg shadow-smart-cobalto/20 transition duration-200 text-[10px] uppercase tracking-widest">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updatePreview(val, targetElementId) {
            const preview = document.getElementById(targetElementId);
            preview.innerText = val.trim() !== "" ? val.trim() : "help";
        }

        function abrirModalEditar(id, nombre, icono) {
            const modal = document.getElementById('modal_editar');
            const form = document.getElementById('form_editar');
            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('edit_icono').value = icono;
            updatePreview(icono, 'edit_icon_preview');
            let urlBase = "{{ route('categorias.update', ':id') }}";
            form.action = urlBase.replace(':id', id);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function cerrarModalEditar() {
            const modal = document.getElementById('modal_editar');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</x-app-layout>