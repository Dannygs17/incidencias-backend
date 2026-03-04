<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Alumbrado</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Animación para el modal */
        .modal-active { display: flex !important; }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800">

    <div class="flex h-screen">
        <aside class="w-64 bg-white border-r border-gray-300 hidden md:flex flex-col">
            <div class="p-4 border-b border-gray-200 font-bold text-gray-600 uppercase text-xs tracking-wider">
                Categorías
            </div>
            <nav class="flex-1 overflow-y-auto">
                <a href="#" class="flex items-center px-4 py-3 bg-blue-50 text-blue-700 border-r-4 border-blue-600 font-semibold">Alumbrado</a>
                <a href="#" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 transition">Baches</a>
                <a href="#" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 transition">Limpieza de áreas</a>
            </nav>
        </aside>

        <main class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <header class="bg-gray-200 border-b border-gray-300 p-2 flex items-center gap-4">
                <div class="flex gap-1.5 ml-2">
                    <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                    <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                    <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                </div>
                <div class="flex-1 bg-white border border-gray-400 rounded px-3 py-1 text-sm text-gray-500 truncate italic">
                    https://admin.incidencias.test/alumbrado
                </div>
                <div class="pr-2"><i data-lucide="search" class="w-4 h-4 text-gray-500"></i></div>
            </header>

            <section class="p-6 overflow-y-auto">
                <div class="mb-6">
                    <button class="flex items-center gap-2 px-4 py-1.5 border border-gray-400 rounded bg-white hover:bg-gray-50 text-sm font-medium shadow-sm transition">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i> Volver al Dashboard
                    </button>
                </div>

                <div class="bg-white border border-gray-300 rounded-lg shadow-sm overflow-hidden">
                    <div class="flex border-b border-gray-300 bg-gray-50 overflow-x-auto">
                        <button class="px-6 py-3 border-r border-gray-300 bg-white font-bold border-t-4 border-t-blue-600 text-blue-600 min-w-max">[ Pendientes (12) ]</button>
                        <button class="px-6 py-3 border-r border-gray-300 hover:bg-gray-100 text-gray-500 min-w-max">[ En Proceso (3) ]</button>
                        <button class="px-6 py-3 border-r border-gray-300 hover:bg-gray-100 text-gray-500 min-w-max">[ Resueltos (200+) ]</button>
                    </div>

                    <div class="p-5">
                        <div class="border border-gray-300 rounded overflow-hidden shadow-inner">
                            <table class="w-full text-left text-sm border-collapse">
                                <thead class="bg-gray-100 text-gray-700 border-b border-gray-300 font-bold uppercase text-[11px] tracking-wider">
                                    <tr>
                                        <th class="p-3 border-r border-gray-300 w-12 text-center">ID</th>
                                        <th class="p-3 border-r border-gray-300 w-24 text-center">Imagen</th>
                                        <th class="p-3 border-r border-gray-300 text-center">Fecha</th>
                                        <th class="p-3 border-r border-gray-300">Descripción</th>
                                        <th class="p-3 border-r border-gray-300 text-center">Estado</th>
                                        <th class="p-3 text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach(range(1, 10) as $id)
                                    <tr class="hover:bg-blue-50/30 transition border-b border-gray-300 last:border-0">
                                        <td class="p-3 border-r border-gray-300 text-center font-medium">{{ $id }}</td>
                                        <td class="p-3 border-r border-gray-300">
                                            <div class="w-10 h-10 bg-gray-100 border border-gray-200 rounded flex items-center justify-center mx-auto shadow-sm">
                                                <i data-lucide="image" class="w-5 h-5 text-gray-400"></i>
                                            </div>
                                        </td>
                                        <td class="p-3 border-r border-gray-300 text-center text-gray-500">20/05/19</td>
                                        <td class="p-3 border-r border-gray-300 italic text-gray-600 truncate max-w-xs">
                                            Descripción incidencia {{ $id }}...
                                        </td>
                                        <td class="p-3 border-r border-gray-300 text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200 uppercase">
                                                Pendiente
                                            </span>
                                        </td>
                                        <td class="p-3 text-center">
                                            <button onclick="openModal('Incidencia #{{$id}}', 'Esta es la descripción detallada de la incidencia número {{$id}}. Se reportó una falla en el alumbrado público de la zona centro.')" 
                                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-bold transition shadow-sm uppercase tracking-tighter">
                                                Ver detalles
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <div id="detailModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4 backdrop-blur-sm">
        <div class="bg-white border-2 border-gray-800 w-full max-w-lg shadow-2xl rounded-xl overflow-hidden animate-in fade-in zoom-in duration-200">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                <h3 id="modalTitle" class="text-xl font-black uppercase tracking-tight">Detalle</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-red-500 transition font-bold text-2xl px-2">×</button>
            </div>
            
            <div class="p-6">
                <div class="w-full h-48 bg-gray-200 rounded-lg mb-4 flex items-center justify-center border border-gray-300 shadow-inner">
                    <i data-lucide="image" class="w-12 h-12 text-gray-400"></i>
                </div>
                <div class="space-y-3">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Descripción</p>
                    <p id="modalDesc" class="text-gray-700 leading-relaxed italic border-l-4 border-blue-500 pl-4 bg-blue-50 py-2 rounded-r">
                        Cargando información...
                    </p>
                </div>
            </div>

            <div class="p-4 bg-gray-50 border-t border-gray-200 flex gap-3">
                <button onclick="alert('Marcado En Proceso')" class="flex-1 bg-yellow-400 hover:bg-yellow-500 text-yellow-900 font-black py-3 rounded-lg border-b-4 border-yellow-600 active:border-0 transition-all uppercase text-xs shadow-md">
                    En Proceso
                </button>
                <button onclick="alert('Marcado como Resuelto')" class="flex-1 bg-green-500 hover:bg-green-600 text-white font-black py-3 rounded-lg border-b-4 border-green-700 active:border-0 transition-all uppercase text-xs shadow-md">
                    Resuelto
                </button>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function openModal(title, desc) {
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalDesc').innerText = desc;
            document.getElementById('detailModal').classList.add('modal-active');
        }

        function closeModal() {
            document.getElementById('detailModal').classList.remove('modal-active');
        }

        // Cerrar modal al hacer click fuera
        window.onclick = function(event) {
            let modal = document.getElementById('detailModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>