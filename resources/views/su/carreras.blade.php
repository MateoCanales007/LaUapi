@extends('layouts.app-su')

@section('title', 'Carreras')

@section('view-contenido')

<div class="flex-1 flex flex-col overflow-hidden h-screen relative">
    
    <header class="h-20 bg-white dark:bg-gray-800 shadow-sm flex items-center justify-between px-4 md:px-8 shrink-0 transition-colors duration-300 z-20 relative">
        <div class="flex items-center">
            <button id="open-sidebar-button" class="text-gray-500 dark:text-gray-200 focus:outline-none lg:hidden mr-4"><i class="fas fa-bars fa-2x"></i></button>
            <h2 class="text-xl md:text-2xl font-bold text-gray-700 dark:text-white truncate">Carreras</h2>
        </div>
        <div>
             <button onclick="toggleModal('add-career-modal')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 md:px-4 py-2 rounded-lg text-sm font-medium shadow transition whitespace-nowrap">
                <i class="fas fa-plus mr-1"></i> <span class="hidden md:inline">Nueva Carrera</span><span class="md:hidden">Nueva</span>
            </button>
        </div>
    </header>

    <main class="flex-1 overflow-hidden flex relative bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
        
        <div id="uni-panel" class="absolute inset-y-0 left-0 z-10 w-80 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col shadow-2xl xl:shadow-none transform -translate-x-full xl:translate-x-0 xl:relative transition-transform duration-300">
            
            <div class="p-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center xl:block">
                <p class="text-xs font-bold text-gray-400 uppercase">Filtrar por Universidad</p>
                <button onclick="toggleUniPanel()" class="text-gray-500 xl:hidden"><i class="fas fa-times"></i></button>
            </div>
            
            <div class="px-4 pb-4 xl:pt-4">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
                    <input type="text" placeholder="Buscar..." class="w-full bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-200 text-sm rounded-lg pl-9 pr-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
            
            <div class="flex-1 overflow-y-auto p-2 space-y-1">
                <button onclick="switchTab('ues')" id="tab-btn-ues" class="uni-tab-btn w-full text-left flex items-center p-3 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 border-indigo-200 dark:border-indigo-800 transition-colors group">
                    <div class="h-8 w-8 bg-red-700 rounded-md flex items-center justify-center text-white text-xs font-bold mr-3 shrink-0">UES</div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200">U. de El Salvador</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">5 Carreras</p>
                    </div>
                    <i class="fas fa-chevron-right ml-auto text-xs text-indigo-400 opacity-100"></i>
                </button>

                <button onclick="switchTab('uca')" id="tab-btn-uca" class="uni-tab-btn w-full text-left flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group">
                    <div class="h-8 w-8 bg-blue-700 rounded-md flex items-center justify-center text-white text-xs font-bold mr-3 shrink-0">UCA</div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">UCA El Salvador</h4>
                        <p class="text-xs text-gray-500">4 Carreras</p>
                    </div>
                    <i class="fas fa-chevron-right ml-auto text-xs text-indigo-400 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                </button>

                <button onclick="switchTab('udb')" id="tab-btn-udb" class="uni-tab-btn w-full text-left flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group">
                    <div class="h-8 w-8 bg-orange-600 rounded-md flex items-center justify-center text-white text-xs font-bold mr-3 shrink-0">UDB</div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">U. Don Bosco</h4>
                        <p class="text-xs text-gray-500">4 Carreras</p>
                    </div>
                    <i class="fas fa-chevron-right ml-auto text-xs text-indigo-400 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                </button>
            </div>
        </div>

        <div id="uni-backdrop" onclick="toggleUniPanel()" class="absolute inset-0 bg-gray-900/50 z-[1] hidden xl:hidden transition-opacity"></div>

        <div class="flex-1 overflow-y-auto w-full p-4 md:p-8">
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-6 gap-4">
                <div>
                    <button onclick="toggleUniPanel()" class="xl:hidden mb-2 inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 shadow-sm text-xs font-medium rounded text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 focus:outline-none">
                        <i class="fas fa-filter mr-2 text-indigo-500"></i> Filtrar Universidad
                    </button>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white">Carreras Disponibles</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Mostrando: <span class="font-semibold text-indigo-600 dark:text-indigo-400" id="current-uni-label">U. de El Salvador</span></p>
                </div>
            </div>

            <div id="grid-ues" class="grid-content grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-5 animate-fade-in">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-[1.5rem] shadow-sm hover:shadow-lg transition-shadow border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                    <div class="absolute left-0 top-0 bottom-0 w-2 bg-blue-500"></div>
                    <div class="flex justify-between items-start mb-4 pl-2">
                        <div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg text-blue-600 dark:text-blue-400"><i class="fas fa-laptop-code fa-lg"></i></div>
                    </div>
                    <div class="pl-2">
                        <h4 class="text-lg font-bold text-gray-800 dark:text-white leading-tight mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Ingeniería en Sistemas</h4>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">UES - Ingeniería</p>
                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center"><div class="text-xs text-gray-400">500 estudiantes</div><button class="text-gray-400 hover:text-blue-600 dark:hover:text-white"><i class="fas fa-edit"></i></button></div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-[1.5rem] shadow-sm hover:shadow-lg transition-shadow border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                    <div class="absolute left-0 top-0 bottom-0 w-2 bg-green-500"></div>
                    <div class="flex justify-between items-start mb-4 pl-2">
                        <div class="p-2 bg-green-50 dark:bg-green-900/30 rounded-lg text-green-600 dark:text-green-400"><i class="fas fa-user-md fa-lg"></i></div>
                    </div>
                    <div class="pl-2">
                        <h4 class="text-lg font-bold text-gray-800 dark:text-white leading-tight mb-1 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">Doctorado en Medicina</h4>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">UES - Medicina</p>
                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center"><div class="text-xs text-gray-400">2,100 estudiantes</div><button class="text-gray-400 hover:text-green-600 dark:hover:text-white"><i class="fas fa-edit"></i></button></div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-[1.5rem] shadow-sm hover:shadow-lg transition-shadow border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                    <div class="absolute left-0 top-0 bottom-0 w-2 bg-purple-500"></div>
                    <div class="flex justify-between items-start mb-4 pl-2">
                        <div class="p-2 bg-purple-50 dark:bg-purple-900/30 rounded-lg text-purple-600 dark:text-purple-400"><i class="fas fa-language fa-lg"></i></div>
                    </div>
                    <div class="pl-2">
                        <h4 class="text-lg font-bold text-gray-800 dark:text-white leading-tight mb-1 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">Lic. en Idiomas</h4>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">UES - Humanidades</p>
                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center"><div class="text-xs text-gray-400">850 estudiantes</div><button class="text-gray-400 hover:text-purple-600 dark:hover:text-white"><i class="fas fa-edit"></i></button></div>
                    </div>
                </div>
            </div>


            <div id="grid-uca" class="grid-content hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-5 animate-fade-in">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-[1.5rem] shadow-sm hover:shadow-lg transition-shadow border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                    <div class="absolute left-0 top-0 bottom-0 w-2 bg-yellow-500"></div>
                    <div class="flex justify-between items-start mb-4 pl-2">
                        <div class="p-2 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg text-yellow-600 dark:text-yellow-400"><i class="fas fa-chart-line fa-lg"></i></div>
                    </div>
                    <div class="pl-2">
                        <h4 class="text-lg font-bold text-gray-800 dark:text-white leading-tight mb-1 group-hover:text-yellow-600 dark:group-hover:text-yellow-400 transition-colors">Lic. en Economía</h4>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">UCA - Economía</p>
                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center"><div class="text-xs text-gray-400">400 estudiantes</div><button class="text-gray-400 hover:text-yellow-600 dark:hover:text-white"><i class="fas fa-edit"></i></button></div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-[1.5rem] shadow-sm hover:shadow-lg transition-shadow border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                    <div class="absolute left-0 top-0 bottom-0 w-2 bg-blue-700"></div>
                    <div class="flex justify-between items-start mb-4 pl-2">
                        <div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg text-blue-700 dark:text-blue-400"><i class="fas fa-industry fa-lg"></i></div>
                    </div>
                    <div class="pl-2">
                        <h4 class="text-lg font-bold text-gray-800 dark:text-white leading-tight mb-1 group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors">Ingeniería Industrial</h4>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">UCA - Ingeniería</p>
                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center"><div class="text-xs text-gray-400">800 estudiantes</div><button class="text-gray-400 hover:text-blue-700 dark:hover:text-white"><i class="fas fa-edit"></i></button></div>
                    </div>
                </div>
                 <div class="bg-white dark:bg-gray-800 p-6 rounded-[1.5rem] shadow-sm hover:shadow-lg transition-shadow border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                    <div class="absolute left-0 top-0 bottom-0 w-2 bg-teal-500"></div>
                    <div class="flex justify-between items-start mb-4 pl-2">
                        <div class="p-2 bg-teal-50 dark:bg-teal-900/30 rounded-lg text-teal-600 dark:text-teal-400"><i class="fas fa-book-open fa-lg"></i></div>
                    </div>
                    <div class="pl-2">
                        <h4 class="text-lg font-bold text-gray-800 dark:text-white leading-tight mb-1 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">Lic. en Teología</h4>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">UCA - Teología</p>
                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center"><div class="text-xs text-gray-400">80 estudiantes</div><button class="text-gray-400 hover:text-teal-600 dark:hover:text-white"><i class="fas fa-edit"></i></button></div>
                    </div>
                </div>
            </div>


            <div id="grid-udb" class="grid-content hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-5 animate-fade-in">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-[1.5rem] shadow-sm hover:shadow-lg transition-shadow border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                    <div class="absolute left-0 top-0 bottom-0 w-2 bg-red-600"></div>
                    <div class="flex justify-between items-start mb-4 pl-2">
                        <div class="p-2 bg-red-50 dark:bg-red-900/30 rounded-lg text-red-600 dark:text-red-400"><i class="fas fa-robot fa-lg"></i></div>
                    </div>
                    <div class="pl-2">
                        <h4 class="text-lg font-bold text-gray-800 dark:text-white leading-tight mb-1 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">Ing. Mecatrónica</h4>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">UDB - Ingeniería</p>
                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center"><div class="text-xs text-gray-400">350 estudiantes</div><button class="text-gray-400 hover:text-red-600 dark:hover:text-white"><i class="fas fa-edit"></i></button></div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-[1.5rem] shadow-sm hover:shadow-lg transition-shadow border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                    <div class="absolute left-0 top-0 bottom-0 w-2 bg-sky-500"></div>
                    <div class="flex justify-between items-start mb-4 pl-2">
                        <div class="p-2 bg-sky-50 dark:bg-sky-900/30 rounded-lg text-sky-600 dark:text-sky-400"><i class="fas fa-plane fa-lg"></i></div>
                    </div>
                    <div class="pl-2">
                        <h4 class="text-lg font-bold text-gray-800 dark:text-white leading-tight mb-1 group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors">Téc. en Aeronáutica</h4>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">UDB - Aeronáutica</p>
                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center"><div class="text-xs text-gray-400">300 estudiantes</div><button class="text-gray-400 hover:text-sky-600 dark:hover:text-white"><i class="fas fa-edit"></i></button></div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-[1.5rem] shadow-sm hover:shadow-lg transition-shadow border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                    <div class="absolute left-0 top-0 bottom-0 w-2 bg-orange-500"></div>
                    <div class="flex justify-between items-start mb-4 pl-2">
                        <div class="p-2 bg-orange-50 dark:bg-orange-900/30 rounded-lg text-orange-600 dark:text-orange-400"><i class="fas fa-palette fa-lg"></i></div>
                    </div>
                    <div class="pl-2">
                        <h4 class="text-lg font-bold text-gray-800 dark:text-white leading-tight mb-1 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors">Lic. Diseño Gráfico</h4>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">UDB - Artes</p>
                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center"><div class="text-xs text-gray-400">500 estudiantes</div><button class="text-gray-400 hover:text-orange-600 dark:hover:text-white"><i class="fas fa-edit"></i></button></div>
                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

<div id="add-career-modal" class="hidden fixed inset-0 z-50 overflow-y-auto backdrop-blur-sm transition-opacity duration-300" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen py-4 px-4 items-center text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="toggleModal('add-career-modal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-[1.5rem] text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Nueva Carrera</h3>
                <form class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Universidad</label>
                        <select class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm p-2 border">
                            <option>Universidad de El Salvador</option>
                            <option>UCA</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre de la Carrera</label>
                        <input type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm p-2 border">
                    </div>
                </form>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:flex sm:flex-row-reverse">
                <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm" onclick="toggleModal('add-career-modal')">Guardar</button>
                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="toggleModal('add-career-modal')">Cancelar</button>
            </div>
        </div>
    </div>
</div>

    <script>
        function toggleModal(id) { document.getElementById(id).classList.toggle("hidden"); }
        
        // --- LOGICA DE CAMBIO DE PESTAÑAS (SWITCH TAB) ---
        // Esta función "enciende" el div correspondiente y apaga los otros
        function switchTab(uniId) {
            // 1. Ocultar todos los grids
            document.querySelectorAll('.grid-content').forEach(el => el.classList.add('hidden'));
            
            // 2. Mostrar el seleccionado
            const selectedGrid = document.getElementById('grid-' + uniId);
            if(selectedGrid) selectedGrid.classList.remove('hidden');

            // 3. Actualizar título
            const labelMap = { 'ues': 'U. de El Salvador', 'uca': 'UCA El Salvador', 'udb': 'U. Don Bosco' };
            document.getElementById('current-uni-label').textContent = labelMap[uniId];

            // 4. Actualizar botones del sidebar (Estilos Activo/Inactivo)
            document.querySelectorAll('.uni-tab-btn').forEach(btn => {
                // Quitar estilos activos
                btn.classList.remove('bg-indigo-50', 'dark:bg-indigo-900/30', 'border-indigo-200', 'dark:border-indigo-800');
                btn.querySelector('h4').classList.remove('font-bold', 'text-gray-800');
                btn.querySelector('i.fa-chevron-right').classList.remove('opacity-100');
                btn.querySelector('i.fa-chevron-right').classList.add('opacity-0', 'group-hover:opacity-100');
            });

            // Poner estilos al activo
            const activeBtn = document.getElementById('tab-btn-' + uniId);
            if(activeBtn) {
                activeBtn.classList.add('bg-indigo-50', 'dark:bg-indigo-900/30', 'border-indigo-200', 'dark:border-indigo-800');
                activeBtn.querySelector('h4').classList.add('font-bold', 'text-gray-800');
                activeBtn.querySelector('i.fa-chevron-right').classList.add('opacity-100');
                activeBtn.querySelector('i.fa-chevron-right').classList.remove('opacity-0', 'group-hover:opacity-100');
            }

            // En movil, cerrar panel al seleccionar
            if(window.innerWidth < 1280) toggleUniPanel();
        }

        function toggleUniPanel() {
            const panel = document.getElementById('uni-panel');
            const backdrop = document.getElementById('uni-backdrop');
            
            if (panel.classList.contains('-translate-x-full')) {
                panel.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                panel.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }
    </script>

@endsection