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
            <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full hidden md:inline-block">Total: {{ $totalUsers }}</span>
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
            @php
                $urlId = request('user_id');
                // La misma lógica: activo si coincide con URL o si es el primero por defecto
                $isActive = $urlId ? ($urlId == $user->id) : $loop->first;
            @endphp
            <div id="detail-{{ $user->id }}" class="user-detail {{ $isActive ? '' : 'hidden' }} fade-in min-h-full">
                
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
                            <div class="">
                               <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $user->name }} 
                                @if($user->insignias->contains('slug', 'verificado'))
                                    <i title="Estudiante Verificado" class='bx bxs-badge-check text-white-600 text-xl'></i>
                                @endif
                               </h2>
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

                    <div class="hidden bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded-r mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0"><i class="fas fa-info-circle text-red-500"></i></div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700 dark:text-red-300">Este usuario ha recibido <strong>3 reportes</strong> por comportamiento inapropiado en las últimas 24 horas.</p>
                            </div>
                        </div>
                    </div>

                    <div id="loading-body-{{ $user->id }}" class="flex-1 px-6 md:px-10 pb-10 {{ $isActive ? 'hidden' : '' }}">
                        <div class="p-10 text-center text-gray-500 mt-10">
                            <i class="fas fa-user fa-3x mb-4 text-gray-300 animate-pulse"></i>
                            <p class="text-lg">Perfil del Estudiante cargando...</p>
                        </div>
                    </div>

                    <div id="content-body-{{ $user->id }}" class="fade-in {{ $isActive ? '' : 'hidden' }}">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            
                            <div class="flex flex-col h-full space-y-6">
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

                                <div class="bg-white dark:bg-gray-800 p-6 rounded-[1.5rem] shadow-sm border border-gray-100 dark:border-gray-700 flex-1">
                                    <h4 class="text-sm font-bold text-gray-400 uppercase mb-4 tracking-wider">Insignia</h4>
                                    <div class="flex gap-3 text-xl">
                                        @foreach($user->insignias as $badge)
                                            <div class="h-10 w-10 rounded-full flex items-center justify-center shadow-sm {{ $badge->bgicon }}" 
                                                 title="{{ $badge->nombre }}">
                                                {{-- Renderizamos el icono dinámico --}}
                                                <i class="{{ $badge->icono }}"></i>
                                            </div>
                                        @endforeach
                                        
                                        <button 
                                        onclick="openBadgeModal({{ $user->id }}, {{ $user->insignias->pluck('id') }})"
                                        class="h-10 w-10 flex items-center justify-center border-2 border-dashed border-gray-400 rounded-full hover:border-blue-500 transition"
                                        >
                                            <i class="bi bi-patch-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="lg:col-span-2 space-y-6">
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                                        <span class="block text-2xl font-bold text-gray-900 dark:text-white">{{ $user->posts_count }}</span>
                                        <span class="text-xs text-gray-500 uppercase">Posts</span>
                                    </div>
                                    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                                        <span class="block text-2xl font-bold text-gray-900 dark:text-white">{{ $user->followers_count }}</span>
                                        <span class="text-xs text-gray-500 uppercase">Seguidores</span>
                                    </div>
                                    <div class="bg-red-300 dark:bg-red-600 p-4 rounded-xl shadow-sm border border-red-700 dark:border-red-400 text-center">
                                        <span class="block text-2xl font-bold text-gray-900 dark:text-white">3</span>
                                        <span class="text-xs text-gray-600 dark:text-white uppercase">Reportes</span>
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

                            <div class="lg:col-span-3 space-y-6">
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-[1.5rem] shadow-sm border border-gray-100 dark:border-gray-700">
                                    <div class="flex justify-between items-center mb-6">
                                        <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Estado Disciplinario</h4>
                                        
                                        {{-- Badge de Estado General --}}
                                        <span class="px-2 py-1 rounded-md bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-xs font-bold border border-green-200 dark:border-green-800">
                                            COMPORTAMIENTO BUENO
                                        </span>
                                    </div>

                                    <div class="relative flex justify-between items-center mb-6 px-2">
                                        <div class="absolute top-1/2 left-0 w-full h-1 bg-gray-100 dark:bg-gray-700 -z-0 rounded-full"></div>
                                        
                                        <div class="relative z-10 flex flex-col items-center group cursor-pointer">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center bg-yellow-100 text-yellow-600 border-2 border-yellow-400 shadow-sm transition-transform hover:scale-110">
                                                <i class="fas fa-exclamation"></i>
                                            </div>
                                            <span class="absolute -bottom-6 text-[10px] font-semibold text-gray-500 dark:text-gray-400 whitespace-nowrap">Aviso</span>
                                        </div>

                                        <div class="relative z-10 flex flex-col items-center group cursor-pointer">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-600 text-gray-300">
                                                <span class="text-xs font-bold">1</span>
                                            </div>
                                            <span class="absolute -bottom-6 text-[10px] font-medium text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">1ª Falta</span>
                                        </div>

                                        <div class="relative z-10 flex flex-col items-center group cursor-pointer">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-600 text-gray-300">
                                                <span class="text-xs font-bold">2</span>
                                            </div>
                                             <span class="absolute -bottom-6 text-[10px] font-medium text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">2ª Falta</span>
                                        </div>

                                        <div class="relative z-10 flex flex-col items-center group cursor-pointer">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-600 text-gray-300">
                                                <span class="text-xs font-bold">3</span>
                                            </div>
                                             <span class="absolute -bottom-6 text-[10px] font-medium text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">3ª Falta</span>
                                        </div>

                                        <div class="relative z-10 flex flex-col items-center group cursor-pointer">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-600 text-gray-300">
                                                <i class="fas fa-ban"></i>
                                            </div>
                                             <span class="absolute -bottom-6 text-[10px] font-medium text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">Expulsión</span>
                                        </div>
                                    </div>

                                    <div class="mt-8 bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3 border border-gray-100 dark:border-gray-600 flex gap-3 items-start">
                                        <div class="text-[22px] mt-0.5 text-yellow-500">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-[15px] font-bold text-gray-800 dark:text-white mb-0.5">Advertencia Reciente</p>
                                            <p class="text-[14px] text-gray-500 dark:text-gray-300 leading-tight">
                                                Se detectó lenguaje inapropiado en los comentarios del post "Matemáticas I".
                                            </p>
                                            <p class="text-[12px] text-gray-400 mt-2">Hace 2 días • Por Admin</p>
                                        </div>
                                    </div>
                                    
                                    <button class="w-full mt-4 py-2 text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition">
                                        Ver historial completo
                                    </button>
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

<div id="badge-modal" class="hidden fixed inset-0 z-50 overflow-y-auto backdrop-blur-sm transition-opacity duration-300" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen py-4 px-4 text-center sm:block sm:p-0 items-center">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="toggleModal('badge-modal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-[1.5rem] text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <form id="assignBadgesForm" action="{{ route('su.add.insig') }}" method="POST">
            @csrf

                <input type="hidden" name="user_id" id="modal-user-id" value="">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="bi bi-patch-plus text-indigo-600 dark:text-indigo-400"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">Administrar Insignias</h3>
                            
                            <div class="mt-4 space-y-4">
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Seleccionar Insignia</label>
                                    <div class="flex gap-2">
                                        <select id="badge-select" class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm px-3 py-2 border">
                                            <option value="" disabled selected>Selecciona una insignia</option>
                                            
                                            {{-- Iteramos sobre la variable $insignia que enviaste desde el controller --}}
                                            @foreach($insignia as $badge)
                                                {{-- Value: El ID (para guardarlo en la BD) --}}
                                                {{-- Text: El Nombre (para que el usuario lo lea) --}}
                                                <option value="{{ $badge->id }}">{{ $badge->nombre }}</option>
                                            @endforeach

                                        </select>
                                        <button type="button" onclick="addBadge()" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-3 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:text-sm">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Insignias Asignadas</label>
                                    <div id="badge-list" class="flex flex-col space-y-2 p-1 min-h-[80px]">
                                        <p id="empty-badges-msg" class="text-xs text-gray-400 w-full text-center py-4">No hay insignias asignadas aún.</p>
                                    </div>
                                </div>

                                <input type="hidden" name="badges" id="badges-input" value="">

                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2 sm:px-8">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Guardar Cambios
                    </button>
                    
                    {{-- BOTÓN CANCELAR (Tipo BUTTON) --}}
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="toggleModal('badge-modal')">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleModal(modalID) {
        document.getElementById(modalID).classList.toggle("hidden");
    }

    // Configuración visual de las insignias
    const badgeConfig = {};

    @foreach($insignia as $badge)
        // Usamos el ID como clave (ej: badgeConfig[1], badgeConfig[5])
        badgeConfig["{{ $badge->id }}"] = {
            label: "{{ $badge->nombre }}",
            desc: "{{ $badge->descripcion ?? 'Sin descripción' }}",
            icon: "{{ $badge->icono }}",
            // Usamos el campo bgicon que guardaste (ej: 'bg-blue-100 text-blue-600')
            bgIcon: "{{ $badge->bgicon }}", 
            // Estilo por defecto para la tarjeta
            bgCard: "bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-600"
        };
    @endforeach

    let selectedBadges = [];

    function openBadgeModal(userId, currentBadgesIds) {
        // Asignar el ID del usuario al input oculto
        document.getElementById('modal-user-id').value = userId;

        // Convertir los IDs que vienen de PHP a strings (para que coincidan con badgeConfig)
        // currentBadgesIds viene como array de números: [1, 2]
        if (Array.isArray(currentBadgesIds)) {
            selectedBadges = currentBadgesIds.map(id => id.toString());
        } else {
            selectedBadges = [];
        }
        // Renderizar visualmente las que ya tiene
        renderBadges();

        // Mostrar el modal
        toggleModal('badge-modal');
    }

    function addBadge() {
        const select = document.getElementById('badge-select');
        const badgeKey = select.value;

        if (!badgeKey) return; // Si no seleccionó nada
        if (selectedBadges.includes(badgeKey)) return; // Evitar duplicados

        selectedBadges.push(badgeKey);
        renderBadges();
        
        // Resetear select
        select.value = "";
    }

    function removeBadge(badgeKey) {
        selectedBadges = selectedBadges.filter(b => b !== badgeKey);
        renderBadges();
    }

    function renderBadges() {
        const container = document.getElementById('badge-list');
        const emptyMsg = document.getElementById('empty-badges-msg');
        const input = document.getElementById('badges-input');

        container.innerHTML = '';
        container.appendChild(emptyMsg); 

        if (selectedBadges.length === 0) {
            emptyMsg.classList.remove('hidden');
        } else {
            emptyMsg.classList.add('hidden');

            selectedBadges.forEach(key => {
                // Validación por si la insignia fue borrada de la BD pero el usuario la tenía
                if (!badgeConfig[key]) return; 

                const config = badgeConfig[key];
                
                const badgeEl = document.createElement('div');
                badgeEl.className = `flex items-center justify-between w-full p-3 rounded-xl border shadow-sm mb-2 transition-all animate-fade-in-up ${config.bgCard}`;
                
                badgeEl.innerHTML = `
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full flex items-center justify-center ${config.bgIcon}">
                            <i class="${config.icon} text-xl"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-bold text-gray-800 dark:text-white leading-none">${config.label}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">${config.desc}</p>
                        </div>
                    </div>
                    <button type="button" onclick="removeBadge('${key}')" class="text-gray-400 hover:text-red-500 p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                `;
                container.appendChild(badgeEl);
            });
        }
        
        // Actualizamos el input que se enviará al servidor
        input.value = JSON.stringify(selectedBadges);
    }

    // Guardar
    function saveBadges() {
        const badgesToSend = document.getElementById('badges-input').value;
        console.log("Enviando:", badgesToSend);
        toggleModal('badge-modal');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        const resultsContainer = document.getElementById('users-list-container');

        // --- TRIGGER DE CARGA INICIAL ---
        const activeDetailDiv = document.querySelector('.user-detail:not(.hidden)');
        if (activeDetailDiv) {
            const activeUserId = activeDetailDiv.id.replace('detail-', '');
            
            const loadingBody = document.getElementById('loading-body-' + activeUserId);
            const contentBody = document.getElementById('content-body-' + activeUserId);
            const actionButtons = document.getElementById('actions-' + activeUserId);

            if(loadingBody && contentBody) {
                // Mostrar "Cargando..."
                loadingBody.classList.remove('hidden');
                contentBody.classList.add('hidden');
                if(actionButtons) actionButtons.classList.add('hidden');

                // Quitar el "Cargando..." y poner el "Fade-In"
                setTimeout(() => {
                    loadingBody.classList.add('hidden');
                    contentBody.classList.remove('hidden');
                    contentBody.classList.add('fade-in');
                    if(actionButtons) {
                        actionButtons.classList.remove('hidden');
                        actionButtons.classList.add('fade-in');
                    }
                }, 600);
            }
        }

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

        if (history.pushState) {
            const newUrl = new URL(window.location);
            newUrl.searchParams.set('user_id', userId);
            window.history.pushState({path: newUrl.href}, '', newUrl.href);
        }
    }

    

    // LÓGICA DE LISTA EN MÓVIL (OFF-CANVAS)
    function toggleUserList() {
        const panel = document.getElementById('user-list-panel');
        panel.classList.toggle('-translate-x-full');
    }
</script>
@endsection