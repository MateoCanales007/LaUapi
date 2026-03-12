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
            <button onclick="toggleModal('create-career-modal')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 md:px-4 py-2 rounded-lg text-sm font-medium shadow transition whitespace-nowrap">
                <i class="fas fa-plus mr-1"></i> <span class="hidden md:inline">Nueva Carrera (Catálogo)</span><span class="md:hidden">Nueva</span>
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
            
            <div class="flex-1 overflow-y-auto p-2 space-y-1 custom-scrollbar">
                
                @foreach($universidades as $uni)
                    @php 
                        $isActive = ($uni->id == $activeUniId); 
                        $btnClasses = $isActive ? 'bg-indigo-50 dark:bg-indigo-900/30 border-indigo-200 dark:border-indigo-800' : 'hover:bg-gray-50 dark:hover:bg-gray-700 border-transparent';
                        $textClasses = $isActive ? 'font-bold text-gray-800 dark:text-gray-200' : 'font-semibold text-gray-700 dark:text-gray-300';
                        $iconClasses = $isActive ? 'opacity-100' : 'opacity-0 group-hover:opacity-100';
                    @endphp

                    <button onclick="switchTab('{{ $uni->id }}', '{{ $uni->nombre }}')" id="tab-btn-{{ $uni->id }}" class="uni-tab-btn w-full text-left flex items-center p-3 rounded-lg transition-colors group {{ $btnClasses }}">
                        
                        <div class="h-8 w-8 rounded-md flex items-center justify-center text-white text-xs font-bold mr-3 shrink-0" style="background-color: {{ $uni->color_primario ?? '#4b5563' }};">
                            {{ substr($uni->siglas ?? $uni->nombre, 0, 3) }}
                        </div>
                        
                        <div>
                            <h4 class="text-sm {{ $textClasses }} truncate w-40" id="tab-title-{{ $uni->id }}">{{ $uni->nombre }}</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $uni->carreras->count() }} Carreras</p>
                        </div>
                        <i class="fas fa-chevron-right ml-auto text-xs text-indigo-400 transition-opacity {{ $iconClasses }}" id="tab-icon-{{ $uni->id }}"></i>
                    </button>
                @endforeach

            </div>
        </div>

        <div id="uni-backdrop" onclick="toggleUniPanel()" class="absolute inset-0 bg-gray-900/50 z-[1] hidden xl:hidden transition-opacity"></div>

        <div class="flex-1 overflow-y-auto w-full p-4 md:p-8 custom-scrollbar">
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-6 gap-4">
                <div>
                    <button onclick="toggleUniPanel()" class="xl:hidden mb-2 inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 shadow-sm text-xs font-medium rounded text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 focus:outline-none">
                        <i class="fas fa-filter mr-2 text-indigo-500"></i> Filtrar Universidad
                    </button>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white">Carreras Disponibles</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Mostrando: <span class="font-semibold text-indigo-600 dark:text-indigo-400" id="current-uni-label">{{ $universidades->where('id', $activeUniId)->first()->nombre ?? 'Seleccione una' }}</span></p>
                </div>
                <div>
                    <button onclick="openAssignCareerModal()" class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 px-3 md:px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition whitespace-nowrap">
                        <i class="fas fa-link mr-1"></i> Vincular Carrera
                    </button>
                </div>
            </div>

            @foreach($universidades as $uni)
                <div id="grid-{{ $uni->id }}" class="grid-content {{ $uni->id == $activeUniId ? '' : 'hidden' }} grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-5 animate-fade-in">
                    
                    @forelse($uni->carreras as $carrera)
                        @php
                            // Una lista de colores HEX atractivos (puedes agregar o quitar los que quieras)
                            $colores = ['#3b82f6', '#10b981', '#8b5cf6', '#f59e0b', '#ef4444', '#0ea5e9', '#f97316', '#14b8a6', '#ec4899'];
                            
                            // Seleccionamos un color usando el ID de la carrera. 
                            // Esto garantiza que se vea variado, pero no cambie al recargar la página.
                            $colorCard = $colores[$carrera->id % count($colores)];
                        @endphp

                        <div class="bg-white dark:bg-gray-800 p-6 rounded-[1.5rem] shadow-sm hover:shadow-lg transition-shadow border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                            
                            <div class="absolute left-0 top-0 bottom-0 w-2" style="background-color: {{ $colorCard }};"></div>
                            
                            <div class="flex justify-between items-start mb-4 pl-2">
                                <div class="p-2 rounded-lg" style="background-color: {{ $colorCard }}20; color: {{ $colorCard }};">
                                    <i class="fas fa-graduation-cap fa-lg"></i>
                                </div>
                            </div>
                            
                            <div class="pl-2">
                                <h4 class="text-lg font-bold text-gray-800 dark:text-white leading-tight mb-1 transition-colors">{{ $carrera->nombre }}</h4>
                                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                    <div class="text-xs text-gray-400">{{ $carrera->users_count ?? 0 }} estudiantes</div>
                                    <button class="text-gray-400 hover:text-indigo-600 dark:hover:text-white transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-10 text-center text-gray-500 dark:text-gray-400">
                            <i class="fas fa-folder-open fa-3x mb-3 opacity-20"></i>
                            <p>No hay carreras registradas en esta universidad aún.</p>
                        </div>
                    @endforelse
                </div>
            @endforeach

        </div>
    </main>
</div>

<div id="create-career-modal" class="hidden fixed inset-0 z-50 overflow-y-auto backdrop-blur-sm transition-opacity duration-300">
    <div class="flex items-end justify-center min-h-screen py-4 px-4 items-center text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75" onclick="toggleModal('create-career-modal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-[1.5rem] text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <form action="{{ route('su.ca.store') }}" method="POST">
                @csrf
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                        <div class="h-8 w-8 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mr-3"><i class="fas fa-plus"></i></div>
                        Crear Nueva Carrera
                    </h3>
                    <p class="text-sm text-gray-500 mb-4">Esta carrera se agregará al catálogo general, pero aún no estará asignada a ninguna universidad.</p>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre de la Carrera</label>
                        <input type="text" name="nombre" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm p-2 border" placeholder="Ej. Arquitectura" required>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">Guardar</button>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="toggleModal('create-career-modal')">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="assign-career-modal" class="hidden fixed inset-0 z-50 overflow-y-auto backdrop-blur-sm transition-opacity duration-300">
    <div class="flex items-end justify-center min-h-screen py-4 px-4 items-center text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75" onclick="toggleModal('assign-career-modal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-[1.5rem] text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <form action="{{ route('su.ca.assign') }}" method="POST">
                @csrf
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                        <div class="h-8 w-8 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mr-3"><i class="fas fa-link"></i></div>
                        Vincular Carrera
                    </h3>
                    
                    <div class="space-y-4">
                        <input type="hidden" name="universidad_id" id="hidden_assign_uni_id" value="">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Selecciona la Carrera del Catálogo</label>
                            <select name="carrera_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm p-2 border" required>
                                <option value="" disabled selected>Elige una carrera...</option>
                                @foreach($todasLasCarreras as $catCarrera)
                                    <option value="{{ $catCarrera->id }}">{{ $catCarrera->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 sm:ml-3 sm:w-auto sm:text-sm">Vincular</button>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="toggleModal('assign-career-modal')">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="add-career-modal" class="hidden fixed inset-0 z-50 overflow-y-auto backdrop-blur-sm transition-opacity duration-300" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen py-4 px-4 items-center text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="toggleModal('add-career-modal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-[1.5rem] text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            
            <form action="" method="POST">
                @csrf
                
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                        <div class="h-8 w-8 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        Nueva Carrera
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Agregando a la Universidad:</label>
                            
                            <input type="hidden" name="universidad_id" id="hidden_universidad_id" value="">
                            
                            <select id="display_universidad_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 shadow-sm p-2 border cursor-not-allowed" disabled>
                                @foreach($universidades as $uni)
                                    <option value="{{ $uni->id }}">{{ $uni->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mt-4">Nombre de la Carrera</label>
                            <input type="text" id="nombre_carrera_input" name="nombre" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm p-2 border focus:ring-indigo-500 focus:border-indigo-500" placeholder="Ej. Ingeniería en Sistemas" required>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm transition transform hover:-translate-y-0.5">
                        Guardar
                    </button>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="toggleModal('add-career-modal')">
                        Cancelar
                    </button>
                </div>
            </form>
            </div>
    </div>
</div>

<script>
    let currentActiveUniId = '{{ $activeUniId }}';

    function toggleModal(id) { document.getElementById(id).classList.toggle("hidden"); }

    function openAssignCareerModal() {
        // Asignamos el ID de la universidad activa al input oculto
        document.getElementById('hidden_assign_uni_id').value = currentActiveUniId;
        
        // Abrimos el modal de asignación
        toggleModal('assign-career-modal');
    }
    
    // --- LÓGICA DE CAMBIO DE PESTAÑAS Y URL ---
    function switchTab(uniId, uniName) {
        // 1. Ocultar todos los grids
        currentActiveUniId = uniId; 

        // 2. Ocultar todos los grids
        document.querySelectorAll('.grid-content').forEach(el => el.classList.add('hidden'));
        
        // 3. Mostrar el seleccionado
        const selectedGrid = document.getElementById('grid-' + uniId);
        if(selectedGrid) selectedGrid.classList.remove('hidden');

        // 3. Actualizar etiqueta de "Mostrando:"
        document.getElementById('current-uni-label').textContent = uniName;

        // 4. Resetear estilos de todos los botones del sidebar
        document.querySelectorAll('.uni-tab-btn').forEach(btn => {
            btn.classList.remove('bg-indigo-50', 'dark:bg-indigo-900/30', 'border-indigo-200', 'dark:border-indigo-800');
            btn.classList.add('hover:bg-gray-50', 'dark:hover:bg-gray-700', 'border-transparent');
            
            // Textos e iconos
            const h4 = btn.querySelector('h4');
            if(h4) { h4.classList.remove('font-bold', 'text-gray-800', 'dark:text-gray-200'); h4.classList.add('font-semibold', 'text-gray-700', 'dark:text-gray-300'); }
            
            const icon = btn.querySelector('i.fa-chevron-right');
            if(icon) { icon.classList.remove('opacity-100'); icon.classList.add('opacity-0', 'group-hover:opacity-100'); }
        });

        // 5. Aplicar estilos de "Activo" al botón seleccionado
        const activeBtn = document.getElementById('tab-btn-' + uniId);
        if(activeBtn) {
            activeBtn.classList.remove('hover:bg-gray-50', 'dark:hover:bg-gray-700', 'border-transparent');
            activeBtn.classList.add('bg-indigo-50', 'dark:bg-indigo-900/30', 'border-indigo-200', 'dark:border-indigo-800');
            
            const h4Active = activeBtn.querySelector('h4');
            if(h4Active) { h4Active.classList.remove('font-semibold', 'text-gray-700', 'dark:text-gray-300'); h4Active.classList.add('font-bold', 'text-gray-800', 'dark:text-gray-200'); }
            
            const iconActive = activeBtn.querySelector('i.fa-chevron-right');
            if(iconActive) { iconActive.classList.remove('opacity-0', 'group-hover:opacity-100'); iconActive.classList.add('opacity-100'); }
        }

        // 6. ACTUALIZAR URL SIN RECARGAR PÁGINA
        if (history.pushState) {
            const newUrl = new URL(window.location);
            newUrl.searchParams.set('uni_id', uniId);
            window.history.pushState({path: newUrl.href}, '', newUrl.href);
        }

        // 7. En móvil, cerrar panel al seleccionar
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