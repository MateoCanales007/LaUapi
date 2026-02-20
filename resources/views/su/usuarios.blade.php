@extends('layouts.app-su')

@section('title', 'Perfiles')

@section('view-contenido')
<div class="flex-1 flex flex-col overflow-hidden h-screen relative">
            
    <header class="h-20 bg-white dark:bg-gray-800 shadow-sm flex items-center justify-between px-4 md:px-8 shrink-0 transition-colors duration-300 z-20 relative">
        <div class="flex items-center">
            <button id="open-sidebar-button" class="text-gray-500 dark:text-gray-200 focus:outline-none lg:hidden mr-4"><i class="fas fa-bars fa-2x"></i></button>
            <h2 class="text-xl md:text-2xl font-bold text-gray-700 dark:text-white truncate">Directorio de Usuarios</h2>
        </div>
        <div class="flex items-center gap-2">
            <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full hidden md:inline-block">Total: 1,240</span>
        </div>
    </header>

    <main class="flex-1 overflow-hidden flex relative bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
        
        <div id="user-list-panel" class="absolute inset-y-0 left-0 z-10 w-full md:w-80 lg:w-96 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col shadow-2xl md:shadow-none transform -translate-x-full md:translate-x-0 md:relative transition-transform duration-300">
            
            <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                <div class="flex justify-between items-center mb-2 md:hidden">
                    <h3 class="font-bold text-gray-700 dark:text-gray-200">Lista de Usuarios</h3>
                    <button onclick="toggleUserList()" class="text-gray-500"><i class="fas fa-times"></i></button>
                </div>
                <div class="relative mb-3">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-sm"></i>
                    <input type="text" id="search-input" data-url="{{ route('su.user.buscar') }}" placeholder="Buscar por nombre o correo..." class="w-full bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-200 text-sm rounded-lg pl-9 pr-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 border border-transparent focus:border-indigo-500 transition-all">
                </div>
                <div class="flex gap-2 overflow-x-auto no-scrollbar pb-1">
                    <button class="px-3 py-1 text-xs font-medium bg-indigo-600 text-white rounded-full whitespace-nowrap">Todos</button>
                    <button class="px-3 py-1 text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-full whitespace-nowrap transition">Activos</button>
                    <button class="px-3 py-1 text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-full whitespace-nowrap transition">Reportados</button>
                </div>
            </div>
            
            <div id="users-list-container" class="flex-1 overflow-y-auto p-2 space-y-1 custom-scrollbar">
                {{-- Llamada al componente dinámico --}}
                
                @include('components.listar-perfiles-su', ['users' => $users])



<!--                 <button onclick="showUser('user3')" id="btn-user3" class="user-btn w-full flex items-center p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 border-l-4 border-transparent hover:border-gray-300 transition-all group">
                    <div class="relative mr-3">
                        <img src="https://ui-avatars.com/api/?name=Carlos+R&background=random" class="h-10 w-10 rounded-full object-cover">
                        <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-white dark:ring-gray-800 bg-red-500"></span>
                    </div>
                    <div class="text-left flex-1 min-w-0">
                        <div class="flex justify-between items-center">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-200 group-hover:text-gray-900 dark:group-hover:text-white truncate">Carlos Ruiz</h4>
                            <span class="bg-red-100 text-red-600 text-[9px] px-1.5 py-0.5 rounded font-bold">REPORTADO</span>
                        </div>
                        <p class="text-xs text-gray-500 truncate">Diseño Gráfico - UDB</p>
                    </div>
                </button> -->
            </div>
        </div>

        <div class="flex-1 overflow-y-auto w-full relative bg-gray-50 dark:bg-gray-900 custom-scrollbar">
            
            <button onclick="toggleUserList()" class="md:hidden absolute top-4 left-4 z-[5] bg-white dark:bg-gray-800 p-2 rounded-full shadow-lg text-indigo-600 border border-gray-200 dark:border-gray-700">
                <i class="fas fa-list"></i>
            </button>

            @foreach($users as $user)
            {{-- Solo mostramos el primero, los demas hidden --}}
            <div id="detail-{{ $user->id }}" class="user-detail {{ $loop->first ? '' : 'hidden' }} fade-in min-h-full">
                
                <div class="h-32 md:h-48 w-full bg-gradient-to-r from-indigo-600 to-blue-500 relative">
                     <div class="absolute top-4 right-4 bg-green-500/90 text-white text-xs font-bold px-3 py-1 rounded-full backdrop-blur-sm shadow-sm flex items-center">
                        Activo
                    </div>
                </div>
                
                <div class="px-6 md:px-10 pb-10">
                    <div class="relative flex flex-col md:flex-row items-start md:items-end -mt-12 mb-6">
                        <img src="{{ $user->imagen ? asset('perfiles/' . $user->imagen) : "https://ui-avatars.com/api/?name=".urlencode($user->name)."&size=128" }}" 
                             class="h-24 w-24 md:h-32 md:w-32 rounded-full border-4 border-white dark:border-gray-900 shadow-md">
                        
                        <div class="mt-4 md:mt-0 md:ml-4 flex-1">
                            <div class="flex items-center">
                               <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }} 
                               </h2>
                               <img alt="Verificado" title="Estudiante Verificado" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAC20lEQVR4AWxUi6HaMAw85S0Cm8AkLZMAk8Am0EnIIo16d1KCea/C1v9rO0wQhBBgYoSCkS9N+TQ/Eru+0WaarLPYXFoYUAxJY9A3a1XAYUatbzKNupG33YrE38xdLnnJXF7kX5l5y1x29mnUdSg5iFQrUBNYZ4TvoEQMvnGMM5BKqv2bfo+Fhat1SlopFHQlDW4a6w7EDzsZyGB2nC8guHGgL1ChaNgxx4O+nGi5LYwpc5ariwFDgbCdAerwFcCZicSTcFFBbB9RFLdDgj7piXiM5NsRBf+7AyVmXDlg5VKykRhvS85npGI3G4SswjiB3ZXvIBRyGvcPRRu3MMucoBUWgbrkFvp46BSlsW/z1ow86pBGVWCX6+tyLFBHFEAb3iOCQD3xsDqqNDOle7EjDl08myzdOMGZAYdqi1zZiUeeolZiZkunKeIEgEXaxySV/IyGmsBCHMJUqL9MsYh7AkyIFcSfIr6efpqAXhAc24j+3Sj6DqgBkxQhxxXcXNdgl+SP5JVY+xRf8ewjfX08MjqBCvprCkrRdwDDH+OxSuKXErGIEqsIO2fyJZVA3wqG1pUboCJ9bCAkhg8Nd1aeaccGfBVA+C9BRSImdp5Ojm/AWMAoZ99NwrAVUALq1KUNA1JCv4zMPNBHfx00Jw+DZFw0Rkz7tyowuagRKMTMqLlnRUEiABcB8CBPohXSi3nvKNbEKPsOsgyNWaA5E3uqpopY84Ecu01zl80qMWyhJrDQKKC3rY+oFU22OpTfGSjUonnmMSu2FPZZJyiVcUyhCY4BXOEPSs3bRJSgHoVQEKB/XKeIPX4Yge2SsUICEZO6ubDYHsA++I2wGNmPJZ8g7COmC8M+jKswrQwQ/AGFmgRphI5Lr0vn+2QidoxrfLwWFNBYDDFjgficwHYjFDTP8WdOw49sOpJnx3HZDi7kmkxF2jy5zVwTtMHE1s1OadRS1BpVKUXv5t/mxD8AAAD//6EwuX0AAAAGSURBVAMAbbYFRlw11vIAAAAASUVORK5CYII="/>
                            </div>
                            
                            <p class="text-indigo-600 dark:text-indigo-400 font-medium">{{ '@' . $user->username }}</p>
                            <!-- <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><i class="fas fa-map-marker-alt mr-1"></i> San Salvador, El Salvador • Se unió en Ene 2024</p> -->
                        </div>
                        <div id="actions-{{ $user->id }}" class="mt-4 md:mt-0 flex gap-2 fade-in {{ $loop->first ? '' : 'hidden' }}">
                            <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 shadow-sm transition">
                                <i class="fas fa-edit mr-1"></i> Editar
                            </button>
                            <button class="px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-400 hover:text-red-500 rounded-lg shadow-sm transition">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>
                    </div>

                    <div id="loading-body-{{ $user->id }}" class="flex-1 px-6 md:px-10 pb-10 {{ $loop->first ? 'hidden' : '' }}">
                        <div class="p-10 text-center text-gray-500 mt-10">
                            <i class="fas fa-user fa-3x mb-4 text-gray-300 animate-pulse"></i>
                            <p class="text-lg">Perfil del Estudiante cargando...</p>
                        </div>
                    </div>

                    <div id="content-body-{{ $user->id }}" class="fade-in {{ $loop->first ? '' : 'hidden' }}">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            
                            <div class="space-y-6">
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-[1.5rem] shadow-sm border border-gray-100 dark:border-gray-700">
                                    <h4 class="text-sm font-bold text-gray-400 uppercase mb-4 tracking-wider">Información Académica</h4>
                                    <div class="space-y-4">
                                        <div>
                                            <p class="text-xs text-gray-500">Universidad</p>
                                            <div class="flex items-center mt-1">
                                                <!-- <div class="h-6 w-6 bg-red-700 text-white text-[9px] flex items-center justify-center rounded font-bold mr-2">UES</div> -->
                                                <p class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                                {{ $user->universidad?->nombre ?? 'No registrada' }}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Carrera</p>
                                            <p class="text-sm font-medium text-gray-800 dark:text-gray-200 mt-1">{{ $user->carrera?->nombre ?? 'No registrada' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500">Correo Institucional</p>
                                            <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400 mt-1 cursor-pointer hover:underline">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white dark:bg-gray-800 p-6 rounded-[1.5rem] shadow-sm border border-gray-100 dark:border-gray-700">
                                    <h4 class="text-sm font-bold text-gray-400 uppercase mb-4 tracking-wider">Insignia</h4>
                                    <div class="flex gap-3">
                                        <div class="h-10 w-10 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center shadow-sm" title="Comunidad"><i class="fas fa-comment"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="lg:col-span-2 space-y-6">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                                        <span class="block text-2xl font-bold text-gray-900 dark:text-white">{{ $user->posts_count }}</span>
                                        <span class="text-xs text-gray-500 uppercase">Posts</span>
                                    </div>
                                    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                                        <span class="block text-2xl font-bold text-gray-900 dark:text-white">{{ $user->followers_count }}</span>
                                        <span class="text-xs text-gray-500 uppercase">Seguidores</span>
                                    </div>
                                </div>

                                <div class="bg-white dark:bg-gray-800 p-6 rounded-[1.5rem] shadow-sm border border-gray-100 dark:border-gray-700">
                                    <h4 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Actividad Reciente</h4>
                                    <div class="space-y-6 border-l-2 border-gray-100 dark:border-gray-700 ml-2 pl-6 relative">
                                        
                                        <div class="relative">
                                            <span class="absolute -left-[31px] top-1 h-4 w-4 rounded-full bg-indigo-500 border-2 border-white dark:border-gray-800"></span>
                                            <p class="text-sm text-gray-500 mb-1">Hace 2 horas</p>
                                            <p class="text-gray-800 dark:text-gray-200 font-medium">Publicó un nuevo material de estudio: <span class="text-indigo-600">"Guía de Cálculo II"</span></p>
                                        </div>

                                        <div class="relative">
                                            <span class="absolute -left-[31px] top-1 h-4 w-4 rounded-full bg-green-500 border-2 border-white dark:border-gray-800"></span>
                                            <p class="text-sm text-gray-500 mb-1">Ayer</p>
                                            <p class="text-gray-800 dark:text-gray-200 font-medium">Se le Otorgo la Insignia de: <span class="font-bold text-gray-700 dark:text-white">"Comunidad"</span></p>
                                        </div>

                                        <div class="relative">
                                            <span class="absolute -left-[31px] top-1 h-4 w-4 rounded-full bg-gray-300 border-2 border-white dark:border-gray-800"></span>
                                            <p class="text-sm text-gray-500 mb-1">20 Oct, 2025</p>
                                            <p class="text-gray-800 dark:text-gray-200 font-medium">Actualizó su foto de perfil.</p>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @endforeach

<!--             <div id="detail-user3" class="user-detail hidden fade-in min-h-full">
                <div class="h-32 md:h-48 w-full bg-gradient-to-r from-red-800 to-red-600 relative">
                     <div class="absolute top-4 right-4 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm flex items-center">
                        <i class="fas fa-exclamation-triangle mr-1"></i> REPORTADO
                    </div>
                </div>
                <div class="px-6 md:px-10 pb-10">
                    <div class="relative flex flex-col md:flex-row items-start md:items-end -mt-12 mb-6">
                        <img src="https://ui-avatars.com/api/?name=Carlos+R&background=random&size=128" class="h-24 w-24 md:h-32 md:w-32 rounded-full border-4 border-white dark:border-gray-900 shadow-md grayscale">
                        <div class="mt-4 md:mt-0 md:ml-4 flex-1">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Carlos Ruiz</h2>
                            <p class="text-gray-500 font-medium">@carlos_design</p>
                        </div>
                        <div class="mt-4 md:mt-0 flex gap-2">
                            <button class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-bold hover:bg-red-700 shadow-sm transition">
                                BLOQUEAR CUENTA
                            </button>
                        </div>
                    </div>
                    
                    <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded-r mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0"><i class="fas fa-info-circle text-red-500"></i></div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700 dark:text-red-300">Este usuario ha recibido <strong>3 reportes</strong> por comportamiento inapropiado en las últimas 24 horas.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div> -->

        </div>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        const resultsContainer = document.getElementById('users-list-container');

        if (searchInput && resultsContainer) {
            const urlDestino = searchInput.dataset.url;
            
            // Variables para controlar la búsqueda
            let debounceTimer; 
            let currentController = null; // Para cancelar peticiones viejas

            searchInput.addEventListener('keyup', function() {
                const query = this.value;

                // 1. Limpiar el temporizador anterior (Debounce)
                clearTimeout(debounceTimer);

                // 2. Cancelar la petición anterior si todavía se está cargando
                if (currentController) {
                    currentController.abort();
                }

                // 3. Crear un nuevo temporizador (esperamos 300ms)
                debounceTimer = setTimeout(() => {
                    
                    // Preparamos el controlador de cancelación para esta nueva petición
                    currentController = new AbortController();
                    const signal = currentController.signal;

                    // Mostramos un estado de carga opcional (opacidad, spinner, etc)
                    resultsContainer.style.opacity = '0.5';

                    fetch(`${urlDestino}?buscar=${encodeURIComponent(query)}`, { signal: signal })
                        .then(response => response.text())
                        .then(html => {
                            resultsContainer.innerHTML = html;
                            resultsContainer.style.opacity = '1';
                        })
                        .catch(error => {
                            // Si el error es por "abort", no hacemos nada (es intencional)
                            if (error.name === 'AbortError') {
                                console.log('Búsqueda anterior cancelada');
                            } else {
                                console.error('Error:', error);
                                resultsContainer.style.opacity = '1';
                            }
                        });

                }, 300); // 300ms de espera antes de buscar
            });
        }
    });

    let loadTimeout;

    function showUser(userId) {
        // 
        if(loadTimeout) clearTimeout(loadTimeout);

        // 2. Actualizar estilos del BOTÓN (Tu código actual)
        document.querySelectorAll('.user-btn').forEach(btn => {
            btn.classList.remove('bg-indigo-50', 'dark:bg-indigo-900/30', 'border-indigo-600', 'text-indigo-600');
            btn.classList.add('border-transparent', 'hover:border-gray-300');
        });

        const activeBtn = document.getElementById('btn-' + userId);
        if(activeBtn) {
            activeBtn.classList.remove('border-transparent', 'hover:border-gray-300', 'text-gray-500');
            activeBtn.classList.add('bg-indigo-50', 'dark:bg-indigo-900/30', 'border-indigo-600');
        }

        // ponemos TODOS los textos en gris
        document.querySelectorAll('.user-role-text').forEach(p => {
            p.classList.remove('text-indigo-600', 'dark:text-indigo-400');
            p.classList.add('text-gray-500');
        });

        // ponemos SOLO el texto del usuario seleccionado en índigo
        const activeText = document.getElementById('text-' + userId);
        if(activeText) {
            activeText.classList.remove('text-gray-500');
            activeText.classList.add('text-indigo-600', 'dark:text-indigo-400');
        }

        // --- LÓGICA DE CARGA ESPECÍFICA ---

        // Ocultar todos los paneles principales
        document.querySelectorAll('.user-detail').forEach(el => el.classList.add('hidden'));

        // Mostrar el panel principal del usuario seleccionado
        const selectedDetail = document.getElementById('detail-' + userId);
        if(selectedDetail) {
            selectedDetail.classList.remove('hidden');

            // RESETEAR ESTADO: Mostrar "Loading" y Ocultar "Content"
            const loadingBody = document.getElementById('loading-body-' + userId);
            const contentBody = document.getElementById('content-body-' + userId);
            const actionButtons = document.getElementById('actions-' + userId);

            if(loadingBody) loadingBody.classList.remove('hidden');
            if(contentBody) contentBody.classList.add('hidden');
            if(actionButtons) actionButtons.classList.add('hidden');

            // SIMULAR CARGA
            loadTimeout = setTimeout(() => {
                // Ocultar carga
                if(loadingBody) loadingBody.classList.add('hidden');
                
                // Mostrar contenido real con efecto fade
                if(contentBody) {
                    contentBody.classList.remove('hidden');
                    contentBody.classList.remove('fade-in');
                    void contentBody.offsetWidth; 
                    contentBody.classList.add('fade-in');
                }

                if(actionButtons) {
                    actionButtons.classList.remove('hidden');
                    actionButtons.classList.remove('fade-in');
                    void actionButtons.offsetWidth; 
                    actionButtons.classList.add('fade-in');
                }
                
            }, 600);
        }

        // cerrar la lista después de seleccionar
        if(window.innerWidth < 768) { 
            document.getElementById('user-list-panel').classList.add('-translate-x-full');
        }
    }

    

    // LÓGICA DE LISTA EN MÓVIL (OFF-CANVAS)
    function toggleUserList() {
        const panel = document.getElementById('user-list-panel');
        panel.classList.toggle('-translate-x-full');
    }
</script>
@endsection