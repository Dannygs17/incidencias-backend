<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Categorías e Iconos') }}
        </h2>
    </x-slot>

    <div class="py-12 relative">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-4">Agregar Nueva Categoría</h3>
                    <form action="{{ route('categorias.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                                <input type="text" name="nombre" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-blue-500 shadow-sm" placeholder="Ej: Via Pública" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre del Icono (Google)</label>
                                <div class="flex gap-2">
                                    <input type="text" name="icono" id="icono_input" 
                                           onkeyup="updatePreview(this.value, 'icon_preview')"
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-blue-500 shadow-sm" 
                                           placeholder="Ej: delete, lightbulb, eco..." required>
                                    
                                    <div class="w-12 h-12 mt-1 flex items-center justify-center bg-gray-50 dark:bg-gray-900 rounded-md border dark:border-gray-700">
                                        <span id="icon_preview" class="material-symbols-outlined text-3xl text-blue-600">help</span>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 italic text-right">
                                    <a href="https://fonts.google.com/icons?icon.set=Material+Symbols" target="_blank" class="hover:underline text-blue-500">Explorar catálogo de Google</a>
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex justify-end border-t dark:border-gray-700 pt-4">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-8 rounded-lg shadow-lg transition duration-200">
                                + Guardar Categoría
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-gray-900 dark:text-gray-100">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-sm uppercase tracking-wider font-bold">
                                    <th class="py-3 px-4">Icono</th>
                                    <th class="py-3 px-4">Nombre de Categoría</th>
                                    <th class="py-3 px-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categorias as $cat)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150"></tr>
                                        <td class="py-4 px-4 text-center w-24">
                                            <div class="bg-blue-50 dark:bg-blue-900/30 p-2 rounded-xl inline-block">
                                                <span class="material-symbols-outlined text-3xl text-blue-600 dark:text-blue-400">
                                                    {{ $cat->icono }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 font-semibold text-lg dark:text-white">
                                            {{ $cat->nombre }}
                                        </td>
                                        <td class="py-4 px-4 text-right">
                                            <div class="flex justify-end items-center gap-2">
                                                <button type="button" onclick="abrirModalEditar({{ $cat->id }}, '{{ addslashes($cat->nombre) }}', '{{ $cat->icono }}')" class="text-blue-500 hover:text-blue-700 font-bold px-4 py-1.5 border border-blue-200 dark:border-blue-900 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/50 transition duration-200">
                                                    Editar
                                                </button>

                                                <form action="{{ route('categorias.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('¿Borrar esta categoría permanentemente?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 font-bold px-4 py-1.5 border border-red-200 dark:border-red-900 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/50 transition duration-200">
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

    <div id="modal_editar" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50 p-4 backdrop-blur-sm transition-opacity">
        <div class="bg-white dark:bg-gray-800 w-full max-w-lg shadow-2xl rounded-xl overflow-hidden">
            
            <div class="p-4 border-b dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900">
                <h3 class="text-xl font-black text-gray-800 dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-500">edit_square</span> Editar Categoría
                </h3>
                <button type="button" onclick="cerrarModalEditar()" class="text-gray-500 hover:text-red-500 transition font-bold text-2xl px-2">&times;</button>
            </div>
            
            <div class="p-6">
                <form id="form_editar" method="POST">
                    @csrf 
                    @method('PUT')
                    
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nuevo Nombre</label>
                            <input type="text" name="nombre" id="edit_nombre" class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-blue-500 shadow-sm" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nuevo Icono (Google)</label>
                            <div class="flex gap-2">
                                <input type="text" name="icono" id="edit_icono" 
                                       onkeyup="updatePreview(this.value, 'edit_icon_preview')"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-blue-500 shadow-sm" required>
                                
                                <div class="w-12 h-12 mt-1 flex items-center justify-center bg-gray-50 dark:bg-gray-900 rounded-md border dark:border-gray-700">
                                    <span id="edit_icon_preview" class="material-symbols-outlined text-3xl text-blue-600">help</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3 border-t dark:border-gray-700 pt-4">
                        <button type="button" onclick="cerrarModalEditar()" class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-bold py-2.5 px-6 rounded-lg transition duration-200">
                            Cancelar
                        </button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-8 rounded-lg shadow-lg transition duration-200">
                            Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Actualiza la vista previa del icono (se usa en ambos formularios)
        function updatePreview(val, targetElementId) {
            const preview = document.getElementById(targetElementId);
            preview.innerText = val.trim() !== "" ? val.trim() : "help";
        }

        // Abrir Modal de Edición
        function abrirModalEditar(id, nombre, icono) {
            const modal = document.getElementById('modal_editar');
            const form = document.getElementById('form_editar');
            
            // Llenar datos
            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('edit_icono').value = icono;
            updatePreview(icono, 'edit_icon_preview');
            
            // Reconstruir la URL de actualización para Laravel
            let urlBase = "{{ route('categorias.update', ':id') }}";
            form.action = urlBase.replace(':id', id);

            // Mostrar Modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // Cerrar Modal
        function cerrarModalEditar() {
            const modal = document.getElementById('modal_editar');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</x-app-layout>