@extends('layouts.app-su')

@section('title', 'Anuncios / Banner')

@section('view-contenido')
<div class="flex-1 flex flex-col h-screen relative overflow-hidden">
    
    <div id="view-index" class="flex flex-col h-full view-transition">
        
        <header class="h-20 bg-white dark:bg-gray-800 shadow-sm flex items-center justify-between px-8 shrink-0">
            <div class="flex items-center gap-4">
                <button id="open-sidebar-button" class="text-gray-500 dark:text-gray-200 focus:outline-none lg:hidden mr-4"><i class="fas fa-bars fa-2x"></i></button>
                <h2 class="text-2xl font-bold text-gray-700 dark:text-white">Gestión de Anuncios</h2>
                <span class="hidden md:block bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full">{{ $activeCount }} Activos</span>
            </div>
            <button onclick="toggleView('create')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg transition transform hover:-translate-y-0.5 flex items-center">
                <i class="fas fa-plus mr-2"></i> Nuevo Anuncio
            </button>
        </header>

        <main class="flex-1 overflow-y-auto p-8 bg-gray-50 dark:bg-gray-900">
            <div class="flex gap-2 mb-6">
			    {{-- Botón TODOS --}}
			    <button onclick="filterBanners('all')" id="btn-filter-all" 
			        class="filter-btn px-4 py-1.5 rounded-full text-sm font-medium transition
			        bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 border border-transparent">
			        Todos {{ $banners->count() }}
			    </button>

			    {{-- Botón ACTIVOS --}}
			    <button onclick="filterBanners('active')" id="btn-filter-active" 
			        class="filter-btn px-4 py-1.5 rounded-full text-sm font-medium transition
			        bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
			        Activos {{ $activeCount }}
			    </button>

			    {{-- Botón BORRADORES --}}
			    <button onclick="filterBanners('draft')" id="btn-filter-draft" 
			        class="filter-btn px-4 py-1.5 rounded-full text-sm font-medium transition
			        bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
			        Borradores {{ $banners->count() - $activeCount }}
			    </button>
			</div>

            <div class="flex items-center gap-4 mb-6 block md:hidden">
                <span class=" bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full">{{ $activeCount }} Activos</span>
            </div>

			{{-- 1. CONTENEDOR DE CARGA (Visible por defecto, se oculta con JS) --}}
			<div id="loading-body" class="flex-1 px-6 md:px-10 pb-10 flex flex-col items-center justify-center min-h-[400px]">
			    <div class="text-center text-gray-500">
			        <i class="fa-solid fa-inbox fa-3x mb-4 text-gray-300 animate-pulse"></i>
			        <p class="text-lg font-medium">Cargando Banners...</p>
			    </div>
			</div>

			{{-- 2. GRILLA DE BANNERS (Oculta por defecto, se muestra con JS) --}}
			<div id="banners-grid" class="hidden grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 fade-in">
			    
			    @forelse($banners as $banner)
			        @php
			            // Configuración de Estilos según el TYPE
			            switch ($banner->type) {
			                case 'feature':
			                    $style = [
			                        'border' => 'bg-purple-500',
			                        'icon_bg' => 'bg-purple-100',
			                        'icon_text' => 'text-purple-600',
			                        'icon' => 'fas fa-star' // O fa-image
			                    ];
			                    break;
			                case 'update':
			                    $style = [
			                        'border' => 'bg-green-500',
			                        'icon_bg' => 'bg-green-100',
			                        'icon_text' => 'text-green-600',
			                        'icon' => 'fas fa-sync-alt'
			                    ];
			                    break;
			                case 'info': // Blue
			                default:
			                    $style = [
			                        'border' => 'bg-blue-500',
			                        'icon_bg' => 'bg-blue-100',
			                        'icon_text' => 'text-blue-600',
			                        'icon' => 'fas fa-info-circle'
			                    ];
			                    break;
			            }
			        @endphp

		        	<div class="banner-item bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition group h-full flex flex-col" data-status="{{ $banner->is_active ? 'active' : 'draft' }}">
			            
			            {{-- Línea de color superior --}}
			            <div class="h-2 {{ $style['border'] }}"></div>
			            
			            <div class="p-5 flex-1 flex flex-col">
			                <div class="flex justify-between items-start mb-3">
			                    {{-- Icono Dinámico --}}
			                    <div class="{{ $style['icon_bg'] }} {{ $style['icon_text'] }} h-10 w-10 rounded-full flex items-center justify-center">
			                        <i class="{{ $style['icon'] }}"></i>
			                    </div>
			                    
			                    {{-- Botones de Acción --}}
			                    <div class="flex gap-1">
			                        {{-- Puedes agregar la ruta de editar aquí --}}
			                        <a href="#" class="text-gray-400 hover:text-indigo-600 p-1 transition">
			                            <i class="fas fa-edit"></i>
			                        </a>

			                            <button onclick="confirmDelete('{{ route('su.ads.delete', $banner->id) }}')" 
										        class="text-gray-400 hover:text-red-500 p-1 transition">
										    <i class="fas fa-trash"></i>
										</button>
			                        <!-- </form> -->
			                    </div>
			                </div>

			                {{-- Título y Contenido --}}
			                <h3 class="font-bold text-gray-800 dark:text-white mb-1 truncate" title="{{ $banner->title }}">
			                    {{ $banner->title }}
			                </h3>
			                <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mb-4 flex-1">
			                    {{ $banner->content }}
			                </p>

			                {{-- Footer de la tarjeta: Estado y Fecha --}}
			                <div class="mt-auto pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center text-xs">
			                    
			                    @if($banner->is_active)
			                        <span class="text-green-500 font-bold flex items-center">
			                            <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span> Activo
			                        </span>
			                    @else
			                        <span class="text-gray-400 font-bold flex items-center">
			                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-1"></span> Borrador
			                        </span>
			                    @endif

			                    <span class="text-gray-400" title="{{ $banner->created_at }}">
			                        {{ $banner->created_at->diffForHumans() }}
			                    </span>
			                </div>
			            </div>
			        </div>

			    @empty
			        {{-- Estado Vacío si no hay banners --}}
			        <div class="col-span-full flex flex-col items-center justify-center p-10 text-gray-400 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl">
			            <i class="fas fa-folder-open fa-3x mb-4 opacity-50"></i>
			            <p>No hay banners creados aún.</p>
			        </div>
			    @endforelse

			    <div id="no-filter-results" class="hidden col-span-full text-center py-10 text-gray-500">
				    <p>No se encontraron banners con este estado.</p>
				</div>

			</div>

            
        </main>
    </div>


    <div id="view-create" class="flex-col h-full hidden view-transition">
        
        <header class="h-20 bg-white dark:bg-gray-800 shadow-sm flex items-center justify-between px-8 shrink-0 z-20 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-4">
                <button onclick="attemptGoBack()" class="text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-white transition">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </button>
                <h2 class="text-xl font-bold text-gray-700 dark:text-white">Crear Nuevo Anuncio</h2>
            </div>
            <div class="flex gap-3">
                <button onclick="resetForm()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white px-4 py-2 text-sm font-medium">Limpiar</button>
                <button onclick="submitForm(1)" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl text-sm font-bold shadow-lg transition transform hover:-translate-y-0.5">
                    <i class="fas fa-paper-plane mr-2"></i> Publicar
                </button>
            </div>
        </header>

        <div class="flex-1 flex overflow-hidden">
            
            <div class="w-full md:w-1/2 lg:w-5/12 overflow-y-auto p-6 md:p-10 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 z-10 custom-scrollbar">
                <form id="announcementForm" action="{{ route('su.ads.create') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                	@csrf
                	{{-- INPUT OCULTO CLAVE: Por defecto en 1 (Publicar) --}}
    				<input type="hidden" name="is_active" id="isActiveInput" value="1">
                    <div style="margin-top: 0px !important;">
					    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">Tipo de Aviso</label>
					    
					    <div class="grid grid-cols-4 gap-3">
					        <label class="cursor-pointer">
					            <input type="radio" id="hasimgbt" name="type" value="feature" class="peer sr-only" checked onchange="updatePreview()">
					            <div class="h-12 rounded-xl border-2 border-gray-200 dark:border-gray-700 peer-checked:border-purple-500 peer-checked:bg-purple-50 dark:peer-checked:bg-purple-900/20 flex items-center justify-center text-gray-400 peer-checked:text-purple-500 transition-all">
					                <i class="fa-solid fa-image"></i>
					            </div>
					            <span class="block text-center text-xs mt-1 text-gray-500">Feature</span>
					        </label>

					        <label class="cursor-pointer">
					             <input type="radio" id="radio-update" name="type" value="update" class="peer sr-only" onchange="updatePreview()">
					            <div class="h-12 rounded-xl border-2 border-gray-200 dark:border-gray-700 peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 flex items-center justify-center text-gray-400 peer-checked:text-green-500 transition-all">
					                <i class="fa-solid fa-arrows-rotate"></i>
					            </div>
					            <span class="block text-center text-xs mt-1 text-gray-500">Update</span>
					        </label>

					        <label class="cursor-pointer">
					            <input type="radio" id="hasinfobt" name="type" value="info" class="peer sr-only" onchange="updatePreview()">
					            <div class="h-12 rounded-xl border-2 border-gray-200 dark:border-gray-700 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 flex items-center justify-center text-gray-400 peer-checked:text-blue-500 transition-all">
					                <i class="fas fa-info-circle fa-lg"></i>
					            </div>
					            <span class="block text-center text-xs mt-1 text-gray-500">Info</span>
					        </label>

<!-- 					        <label class="cursor-pointer">
					             <input type="radio" id="radio-warning" name="type" value="warning" class="peer sr-only" onchange="updatePreview()">
					            <div class="h-12 rounded-xl border-2 border-gray-200 dark:border-gray-700 peer-checked:border-yellow-400 peer-checked:bg-yellow-50 dark:peer-checked:bg-yellow-900/20 flex items-center justify-center text-gray-400 peer-checked:text-yellow-400 transition-all">
					                <i class="fas fa-exclamation-triangle fa-lg"></i>
					            </div>
					            <span class="block text-center text-xs mt-1 text-gray-500">Alerta</span>
					        </label> -->
					    </div>
					</div>

                    <div id="buttonImg" class="space-y-4 hidden opacity-0 transition-opacity duration-300">
                        <div class="pb-8 border-b border-gray-100 dark:border-gray-700">
                            <label for="image_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Imagen Url</label>
                            <input type="text" name="image_url" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm" placeholder="https://lau.app/...">
                        </div>
                    </div>

                    <div class="">
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Título del Anuncio</label>
                        <input type="text" name="title" id="inputTitle" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition" placeholder="Ej. Mantenimiento Programado" oninput="updatePreview()">
                    </div>

                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mensaje / Descripción</label>
                        <textarea id="inputContent" name="content" rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition resize-none" placeholder="Escribe el detalle del aviso aquí..." oninput="updatePreview()"></textarea>
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Botón de Acción (CTA)</span>
                            <label id="toggleLabel" class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="hasButton" class="sr-only peer" onchange="toggleButtonInput()">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>
                        <div id="buttonConfig" class="space-y-4 hidden opacity-0 transition-opacity duration-300">
                            <div>
                                <label for="action_text" class="block text-xs font-medium text-gray-500 mb-1">Texto del Botón</label>
                                <input type="text" id="inputBtnText" name="action_text" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm" placeholder="Ej. Ver más detalles" value="Entendido" oninput="updatePreview()">
                            </div>
                            <div>
                                <label for="action_url" class="block text-xs font-medium text-gray-500 mb-1">URL de Destino</label>
                                <input type="text" name="action_url" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm" placeholder="https://lau.app/...">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                    	<div>
	                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha Inicio</label>
	                        <input type="date" name="start_date" id="start_date" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition" placeholder="00/00/00">
	                    </div>

	                    <div>
	                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha Inicio</label>
	                        <input type="date" name="end_date" id="end_date" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition" placeholder="00/00/00">
	                    </div>
                    </div>
                </form>
            </div>

            <!-- Vista Previas -->
            <div class="hidden md:flex w-1/2 lg:w-7/12 preview-bg flex-col items-center justify-center p-8 relative">
                <div class="absolute top-6 bg-white dark:bg-gray-800 p-1 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 flex">
                    <button onclick="switchDevice('mobile')" id="btn-mobile" class="px-4 py-2 rounded-md text-sm font-medium transition-colors bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-white"><i class="fas fa-mobile-alt mr-2"></i> Móvil</button>
                    <button onclick="switchDevice('desktop')" id="btn-desktop" class="px-4 py-2 rounded-md text-sm font-medium transition-colors text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"><i class="fas fa-desktop mr-2"></i> PC</button>
                </div>

                <div id="preview-mobile" class="relative mx-auto border-gray-800 dark:border-gray-800 bg-gray-800 border-[14px] rounded-[2.5rem] h-[600px] w-[300px] shadow-2xl flex flex-col mt-12 view-transition">
                    <div class="h-[32px] w-[3px] bg-gray-800 absolute -left-[17px] top-[72px] rounded-l-lg"></div>
                    <div class="h-[46px] w-[3px] bg-gray-800 absolute -left-[17px] top-[124px] rounded-l-lg"></div>
                    <div class="h-[46px] w-[3px] bg-gray-800 absolute -left-[17px] top-[178px] rounded-l-lg"></div>
                    <div class="h-[64px] w-[3px] bg-gray-800 absolute -right-[17px] top-[142px] rounded-r-lg"></div>
                    
                    <div class="rounded-[2rem] overflow-hidden w-full h-full bg-white dark:bg-gray-900 relative">
                        <div class="absolute inset-0 bg-gray-100 dark:bg-gray-800 p-4 opacity-50">
                            <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-full mb-4"></div><div class="h-32 bg-gray-200 dark:bg-gray-700 rounded w-full mb-4"></div><div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-1/2 mb-4"></div>
                        </div>
                        <div class="absolute inset-0 flex items-end justify-center" style="background-color: rgba(0, 0, 0, 0.6);">
                            <div class="relative bg-white dark:bg-gray-800 w-full mx-2 mb-2 rounded-2xl shadow-2xl">
                                <div class="flex justify-center pt-4 pb-2 cursor-grab"><div class="w-12 h-1 bg-gray-300 rounded-full"></div></div>
                                <div class="flex justify-center py-4" id="icon-mobile"></div>
                                <div class="px-6 pb-6 text-center">
                                    <h2 id="title-mobile" class="text-xl font-bold text-gray-900 dark:text-white mb-2 leading-tight">Título</h2>
                                    <div id="content-mobile" class="text-gray-600 dark:text-gray-300 text-sm mb-6 leading-relaxed">Texto...</div>
                                    <div id="actions-mobile" class="space-y-3">
                                        <button id="btn-main-mobile" class="w-full py-3 px-4 bg-indigo-600 text-white font-bold rounded-xl shadow-lg hidden">Entendido</button>
                                        <button id="btn-default-mobile" class="w-full py-3 px-4 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium rounded-xl hover:bg-gray-200">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="preview-desktop" class="hidden relative w-full max-w-4xl h-[500px] bg-white dark:bg-gray-900 rounded-lg shadow-2xl border border-gray-200 dark:border-gray-700 flex flex-col mt-12 overflow-hidden view-transition">
                    <div class="bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 h-10 flex items-center px-4 space-x-2">
                        <div class="flex space-x-1.5"><div class="w-3 h-3 rounded-full bg-red-400"></div><div class="w-3 h-3 rounded-full bg-yellow-400"></div><div class="w-3 h-3 rounded-full bg-green-400"></div></div>
                        <div class="flex-1 bg-white dark:bg-gray-700 h-6 rounded text-xs flex items-center px-3 text-gray-400 mx-4">lau.app/dashboard</div>
                    </div>
                    <div class="flex-1 relative bg-gray-50 dark:bg-gray-900">
                        <div class="p-8 opacity-30 grid grid-cols-3 gap-4">
                            <div class="h-32 bg-gray-200 dark:bg-gray-700 rounded-lg"></div><div class="h-32 bg-gray-200 dark:bg-gray-700 rounded-lg"></div><div class="h-32 bg-gray-200 dark:bg-gray-700 rounded-lg"></div>
                        </div>
                        <div class="absolute inset-0 flex items-center justify-center" style="background-color: rgba(0, 0, 0, 0.6);">
                            <div class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-2xl shadow-2xl p-0 overflow-hidden transform scale-100">
                                <div class="flex justify-center py-6" id="icon-desktop"></div>
                                <div class="px-8 pb-8 text-center">
                                    <h2 id="title-desktop" class="text-2xl font-bold text-gray-900 dark:text-white mb-3 leading-tight">Título</h2>
                                    <div id="content-desktop" class="text-gray-600 dark:text-gray-300 text-base mb-8 leading-relaxed">Texto...</div>
                                    <div id="actions-desktop" class="flex justify-center gap-3">
                                        <button  id="btn-default-desktop" class="px-6 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium rounded-xl hover:bg-gray-200">Cerrar</button>
                                        <button id="btn-main-desktop" class="px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-xl shadow-lg hover:bg-indigo-700 hidden">Entendido</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="absolute bottom-4 text-gray-400 text-xs font-mono" id="device-label">Vista previa móvil</p>
            </div>
            <!-- Fin Vista Previas -->
        </div>
    </div>

</div>

<div id="globalBanner" class="block md:hidden fixed inset-0 z-50 flex items-end md:items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4 transition-opacity duration-300">
    
    <div id="modalCard" class="relative w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl ring-1 ring-gray-900/5 transform transition-transform duration-300 ease-out scale-100" style="touch-action: none;">
        
        <div id="dragHandle" class="flex justify-center pt-3 pb-1 md:hidden cursor-grab active:cursor-grabbing p-2">
            <div class="w-12 h-1.5 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
        </div>

        <div class="flex justify-center pt-4 pb-2">
            <div class="h-16 w-16 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center text-3xl shadow-sm animate-bounce-slow">
                <i class="fas fa-hand"></i> </div>
        </div>

        <div class="px-6 pb-6 text-center">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 leading-tight">
                Alto 🤚
            </h3>
            
            <div class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed mb-6">
                Esta interfaz no está diseñada para móviles. Para poder ver la vista previa de crear un anuncio, por favor ingresa desde un ordenador.
            </div>

            <div class="space-y-3"> 
                <button onclick="closeGlobalBanner()" class="w-full py-3 px-4 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-xl transition">
                    Cerrar
                </button>
            </div>
        </div>

    </div>
</div>

<div id="unsaved-modal" class="hidden fixed inset-0 z-50 relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <div id="unsaved-backdrop" class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity opacity-0"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center text-center sm:items-center p-4 sm:p-4">
            
            <div id="unsaved-card" class="relative transform overflow-hidden bg-white dark:bg-gray-800 text-left shadow-2xl rounded-2xl transition-all w-full sm:max-w-lg rounded-t-[2rem] sm:rounded-[1.5rem] translate-y-full sm:translate-y-0 sm:scale-95 opacity-0 sm:opacity-100" style="touch-action: none;">
                
                <div id="unsaved-drag-handle" class="flex justify-center pt-3 pb-1 sm:hidden cursor-grab active:cursor-grabbing w-full">
                    <div class="w-12 h-1.5 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
                </div>

                <div class="bg-white dark:bg-gray-800 px-6 pt-5 pb-4 sm:p-8">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-xl font-bold leading-6 text-gray-900 dark:text-white" id="modal-title">¿Quieres salir sin publicar?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Tienes información escrita en el formulario. Si sales ahora, perderás los cambios no guardados.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 sm:flex sm:flex-row-reverse gap-2 sm:px-8">
                    <button type="button" onclick="handleExitAction('draft')" class="w-full inline-flex justify-center rounded-xl border border-transparent bg-indigo-600 px-4 py-3 text-base font-bold text-white shadow-sm hover:bg-indigo-700 focus:outline-none sm:w-auto sm:text-sm transition-transform active:scale-95">
                        Guardar Borrador
                    </button>

                    <button type="button" onclick="handleExitAction('discard')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-transparent bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 px-4 py-3 text-base font-bold hover:bg-red-200 dark:hover:bg-red-900/50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm transition-transform active:scale-95">
                        Descartar
                    </button>
                    
                    <button type="button" onclick="closeModal('unsaved')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-3 text-base font-medium text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm sm:mr-auto">
                        Cancelar
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<div id="delete-modal" class="hidden fixed inset-0 z-50 relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <div id="delete-backdrop" class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity opacity-0"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center text-center sm:items-center p-4 sm:p-4">
            
            <div id="delete-card" class="relative transform overflow-hidden bg-white dark:bg-gray-800 text-left shadow-2xl rounded-2xl transition-all w-full sm:max-w-lg rounded-t-[2rem] sm:rounded-[1.5rem] translate-y-full sm:translate-y-0 sm:scale-95 opacity-0 sm:opacity-100" style="touch-action: none;">
                
                <div id="delete-drag-handle" class="flex justify-center pt-3 pb-1 sm:hidden cursor-grab active:cursor-grabbing w-full">
                    <div class="w-12 h-1.5 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
                </div>

                <div class="bg-white dark:bg-gray-800 px-6 pt-5 pb-4 sm:p-8">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-xl font-bold leading-6 text-gray-900 dark:text-white" id="modal-title">¿Quieres Borrar este banner?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Esta acción no se podra deshacer.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 sm:flex sm:flex-row-reverse gap-2 sm:px-8">

                    <form id="delete-form" action="" method="POST" class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        
                        <button type="submit" class="mt-3 w-full inline-flex justify-center rounded-xl border border-transparent bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 px-4 py-3 text-base font-bold hover:bg-red-200 dark:hover:bg-red-900/50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm transition-transform active:scale-95">
                        Eliminar
                    </button>
                    </form>
                    
                    <button type="button" onclick="closeModal('delete')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-3 text-base font-medium text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm sm:mr-auto">
                        Cancelar
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
	document.addEventListener("DOMContentLoaded", function() {
        const loadingBody = document.getElementById('loading-body');
        const bannersGrid = document.getElementById('banners-grid');

        // Simulamos un pequeño retraso de red (ej. 800ms) para que se vea la animación
        // Si hay muchos datos o imágenes pesadas, se puede quitar el timeout 
        // y usar el evento window.onload
        setTimeout(() => {
            if(loadingBody) {
                loadingBody.classList.add('hidden'); // Ocultar carga
            }
            
            if(bannersGrid) {
                bannersGrid.classList.remove('hidden'); // Mostrar grilla
                bannersGrid.classList.add('grid');      // Asegurar display grid
                
                // Pequeña animación de entrada (Opcional)
                bannersGrid.animate([
                    { opacity: 0, transform: 'translateY(10px)' },
                    { opacity: 1, transform: 'translateY(0)' }
                ], {
                    duration: 400,
                    easing: 'ease-out'
                });
            }
        }, 800);
    });

    function filterBanners(status) {
        const cards = document.querySelectorAll('.banner-item');
        let visibleCount = 0;

        cards.forEach(card => {
            // Si el status es 'all' O el data-status coincide, mostramos
            if (status === 'all' || card.dataset.status === status) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        // Mostrar mensaje si no hay resultados en el filtro
        const noResultsMsg = document.getElementById('no-filter-results');
        if(noResultsMsg) {
            if(visibleCount === 0 && cards.length > 0) noResultsMsg.classList.remove('hidden');
            else noResultsMsg.classList.add('hidden');
        }

        // Definimos las clases de estado "Activo" (Morado/Azul) y "Inactivo" (Gris/Blanco)
        const activeClasses = ['bg-indigo-50', 'dark:bg-indigo-900/30', 'text-indigo-600', 'dark:text-indigo-400', 'border-transparent'];
        const inactiveClasses = ['bg-white', 'dark:bg-gray-800', 'text-gray-700', 'dark:text-gray-200', 'border-gray-300', 'dark:border-gray-600', 'hover:bg-gray-50'];

        // Reseteamos todos los botones a "Inactivo"
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove(...activeClasses);
            btn.classList.add(...inactiveClasses);
        });

        // Aplicamos estilo "Activo" al botón presionado
        const currentBtn = document.getElementById('btn-filter-' + status);
        if (currentBtn) {
            currentBtn.classList.remove(...inactiveClasses);
            currentBtn.classList.add(...activeClasses);
        }
    }
    // ==========================================
    // 1. LÓGICA REUTILIZABLE DE ARRASTRE (SWIPE)
    // ==========================================
    /**
     * Activa el arrastre hacia abajo para cualquier modal.
     * @param {string} cardId - ID de la tarjeta blanca (lo que se mueve).
     * @param {string} handleId - ID de la barrita gris (donde se toca).
     * @param {function} closeCallback - Función que se ejecuta al cerrar.
     */
    function enableSwipeDown(cardId, handleId, closeCallback) {
        const card = document.getElementById(cardId);
        const handle = document.getElementById(handleId);
        
        // Variables locales para ESTE modal específico
        let startY = 0;
        let currentY = 0;
        let isDragging = false;

        if (!handle || !card) return; // Si no existen, no hace nada

        // Inicio del toque
        handle.addEventListener('touchstart', (e) => {
            startY = e.touches[0].clientY;
            isDragging = true;
            card.style.transition = 'none'; // Quitar transición para respuesta inmediata
        }, { passive: true });

        // Moviendo
        handle.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            currentY = e.touches[0].clientY;
            const diff = currentY - startY;

            // Solo arrastrar hacia abajo
            if (diff > 0) {
                card.style.transform = `translateY(${diff}px)`;
            }
        }, { passive: true });

        // Soltar
        handle.addEventListener('touchend', () => {
            isDragging = false;
            const diff = currentY - startY;
            
            // Restaurar animación suave
            card.style.transition = 'transform 0.3s ease-out, opacity 0.3s ease-out';

            if (diff > 100) {
                // Animar hacia abajo 
                card.style.transform = `translateY(100%)`;
                setTimeout(() => {
                    closeCallback(); 
                    // Resetear posición después de cerrar para la próxima vez
                    setTimeout(() => { card.style.transform = ''; }, 50);
                }, 300);
            } else {
                // Regresar a posición original (Snap back)
                card.style.transform = '';
            }
            
            startY = 0;
            currentY = 0;
        });
    }

    // ==========================================
    // Aviso Global 
    // ==========================================
    const globalBanner = document.getElementById('globalBanner');
    const globalCard = document.getElementById('modalCard'); 

    function closeGlobalBanner() {
        globalBanner.classList.add('opacity-0');

        globalCard.classList.add('translate-y-full');

        setTimeout(() => {
            globalBanner.classList.add('hidden');

            globalCard.style.transform = ''; 
        }, 1000);
    }

    // Activamos el swipe para el banner global
    enableSwipeDown('modalCard', 'dragHandle', closeGlobalBanner);


    // ==========================================
    // Guardar Cambio
    // ==========================================
    // Función genérica para abrir cualquier modal
	function openModal(idName) {
	    // Buscamos los elementos dinámicamente usando el idName
	    const modal = document.getElementById(`${idName}-modal`);
	    const backdrop = document.getElementById(`${idName}-backdrop`);
	    const card = document.getElementById(`${idName}-card`);

	    if (!modal || !backdrop || !card) return; // Seguridad por si no existen

	    modal.classList.remove('hidden');
	    
	    // Pequeño timeout para permitir que el navegador renderice antes de animar
	    setTimeout(() => {
	        backdrop.classList.remove('opacity-0');
	        
	        // Animaciones del card
	        card.classList.remove('translate-y-full'); // Móvil entra
	        card.classList.remove('opacity-0', 'sm:scale-95'); // PC entra
	        card.classList.add('sm:scale-100');
	    }, 10);
	}

	// Función genérica para cerrar cualquier modal
	function closeModal(idName) {
	    const modal = document.getElementById(`${idName}-modal`);
	    const backdrop = document.getElementById(`${idName}-backdrop`);
	    const card = document.getElementById(`${idName}-card`);

	    if (!modal || !backdrop || !card) return;

	    backdrop.classList.add('opacity-0');
	    
	    // Animaciones de salida
	    card.classList.add('translate-y-full'); // Móvil
	    card.classList.add('opacity-0', 'sm:scale-95'); // PC
	    card.classList.remove('sm:scale-100');

	    setTimeout(() => {
	        modal.classList.add('hidden');
	        card.style.transform = ''; 
	    }, 300);
	}

	function confirmDelete(deleteUrl) {
	    // Buscamos el formulario dentro del modal
	    const form = document.getElementById('delete-form');
	    
	    // Le asignamos la URL que recibimos del botón
	    form.action = deleteUrl;
	    
	    // Abrimos el modal usando tu función genérica existente
	    openModal('delete');
	}

    // Activamos el swipe para el modal de cambios
    enableSwipeDown('unsaved-card', 'unsaved-drag-handle', () => closeModal('unsaved'));


    // ==========================================
    // Logica para formulario
    // ==========================================
    
    // Función que llama el botón "Atrás"
    function attemptGoBack() {
	    const title = document.getElementById('inputTitle').value.trim();
	    const content = document.getElementById('inputContent').value.trim();
	    
	    // Si hay texto, pedimos confirmación
	    if (title !== '' || content !== '') {
	        // CAMBIO AQUÍ: Llamamos a la genérica y le decimos qué modal abrir
	        openModal('unsaved'); 
	    } else {
	        // Si está vacío, salimos directo
	        toggleView('index'); 
	    }
	}

    // Acciones de los botones del modal nuevo
    function handleExitAction(action) {
        closeModal('unsaved');
        setTimeout(() => {
            if (action === 'discard') {
                resetForm(); 
                toggleView('index');
            } else if (action === 'draft') {
                submitForm(0);
            }
        }, 300);
    }

    // --- Referencias ---
    const inputTitle = document.getElementById('inputTitle');
    const inputContent = document.getElementById('inputContent');
    const inputBtnText = document.getElementById('inputBtnText');

    // Config Tipos
    const types = {
        'feature': { icon: 'image', color: 'purple' },
        'update':  { icon: 'arrows-rotate', color: 'green' }, 
        'warning': { icon: 'exclamation-triangle', color: 'yellow' }, 
        'info':    { icon: 'info-circle', color: 'blue' }
    };

    // --- LÓGICA DE NAVEGACIÓN ENTRE VISTAS ---
    function toggleView(viewName) {
        const viewIndex = document.getElementById('view-index');
        const viewCreate = document.getElementById('view-create');

        if (viewName === 'create') {
            // Ir a Crear
            viewIndex.classList.add('hidden');
            viewIndex.classList.remove('flex');
            
            viewCreate.classList.remove('hidden');
            viewCreate.classList.add('flex');
            
            // Resetear al abrir
            resetForm();
        } else {
            // Ir al Index (Lista)
            viewCreate.classList.add('hidden');
            viewCreate.classList.remove('flex');
            
            viewIndex.classList.remove('hidden');
            viewIndex.classList.add('flex');
        }
    }

    // --- Actualizar Preview ---
    function updatePreview() {
        const hasimgbt = document.getElementById('hasimgbt').checked;
        const buttonImg = document.getElementById('buttonImg');
        const hasinfobt = document.getElementById('hasinfobt').checked;
        const btdefadesk = document.getElementById('btn-default-desktop');
        const btdefamobi = document.getElementById('btn-default-mobile');
        const hasButtonadd = document.getElementById('hasButton');
        const toggleLabel = document.getElementById('toggleLabel');
        const titleVal = inputTitle.value || 'Título del Anuncio';
        const contentVal = inputContent.value || 'Aquí aparecerá la descripción de tu anuncio tal cual lo verán los usuarios.';
        const btnVal = inputBtnText.value || 'Entendido';
        
        const selectedType = document.querySelector('input[name="type"]:checked').value;
        const config = types[selectedType];
        
        let colorClasses = '';
        if(selectedType === 'info') colorClasses = 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300';
        else if(selectedType === 'update') colorClasses = 'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300';
        else if(selectedType === 'warning') colorClasses = 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-300';
        else if(selectedType === 'feature') colorClasses = 'bg-purple-100 text-purple-600 dark:bg-purple-900 dark:text-purple-300';

        if(hasimgbt) {
            buttonImg.classList.remove('hidden');
            setTimeout(() => buttonImg.classList.remove('opacity-0'), 10);
        } else {
            buttonImg.classList.add('opacity-0');
            setTimeout(() => buttonImg.classList.add('hidden'), 300);
        }

        if(hasinfobt) {
        	btdefadesk.classList.add('hidden');
        	btdefamobi.classList.add('hidden');
            document.getElementById('hasButton').checked = true;
            toggleButtonInput();
            hasButtonadd.disabled = true;
            toggleLabel.classList.remove('cursor-pointer');
            toggleLabel.classList.add('cursor-not-allowed');
        } else {
        	btdefadesk.classList.remove('hidden');
        	btdefamobi.classList.remove('hidden');
            hasButtonadd.disabled = false;
            toggleLabel.classList.add('cursor-pointer');
            toggleLabel.classList.remove('cursor-not-allowed');
        }

        const iconHTML = `<div class="${colorClasses} h-16 w-16 rounded-full flex items-center justify-center text-3xl shadow-sm transition-colors duration-300">
                            <i class="fa-solid fa-${config.icon}"></i>
                          </div>`;

        // Móvil
        document.getElementById('title-mobile').textContent = titleVal;
        document.getElementById('content-mobile').textContent = contentVal;
        document.getElementById('btn-main-mobile').textContent = btnVal;
        document.getElementById('icon-mobile').innerHTML = iconHTML;

        // Desktop
        document.getElementById('title-desktop').textContent = titleVal;
        document.getElementById('content-desktop').textContent = contentVal;
        document.getElementById('btn-main-desktop').textContent = btnVal;
        document.getElementById('icon-desktop').innerHTML = iconHTML;
    }

    function toggleButtonInput() {
        const hasButton = document.getElementById('hasButton').checked;
        const configPanel = document.getElementById('buttonConfig');
        const btnMobile = document.getElementById('btn-main-mobile');
        const btnDesktop = document.getElementById('btn-main-desktop');

        if(hasButton) {
            configPanel.classList.remove('hidden');
            setTimeout(() => configPanel.classList.remove('opacity-0'), 10);
            btnMobile.classList.remove('hidden');
            btnDesktop.classList.remove('hidden');
        } else {
            configPanel.classList.add('opacity-0');
            setTimeout(() => configPanel.classList.add('hidden'), 300);
            btnMobile.classList.add('hidden');
            btnDesktop.classList.add('hidden');
        }
    }

    function switchDevice(device) {
        const mobileView = document.getElementById('preview-mobile');
        const desktopView = document.getElementById('preview-desktop');
        const btnMobile = document.getElementById('btn-mobile');
        const btnDesktop = document.getElementById('btn-desktop');
        const label = document.getElementById('device-label');

        if (device === 'mobile') {
            mobileView.classList.remove('hidden');
            desktopView.classList.add('hidden');
            btnMobile.classList.add('bg-indigo-100', 'text-indigo-700', 'dark:bg-indigo-900', 'dark:text-white');
            btnMobile.classList.remove('text-gray-500', 'dark:text-gray-400');
            btnDesktop.classList.remove('bg-indigo-100', 'text-indigo-700', 'dark:bg-indigo-900', 'dark:text-white');
            btnDesktop.classList.add('text-gray-500', 'dark:text-gray-400');
            label.textContent = "Vista previa móvil";
        } else {
            mobileView.classList.add('hidden');
            desktopView.classList.remove('hidden');
            btnDesktop.classList.add('bg-indigo-100', 'text-indigo-700', 'dark:bg-indigo-900', 'dark:text-white');
            btnDesktop.classList.remove('text-gray-500', 'dark:text-gray-400');
            btnMobile.classList.remove('bg-indigo-100', 'text-indigo-700', 'dark:bg-indigo-900', 'dark:text-white');
            btnMobile.classList.add('text-gray-500', 'dark:text-gray-400');
            label.textContent = "Vista previa escritorio";
        }
    }

    function resetForm() {
        document.getElementById('announcementForm').reset();
        updatePreview();
        document.getElementById('hasButton').checked = false;
        toggleButtonInput();
    }

    updatePreview();

    // --- LOGICA MODAL ---
    function toggleModal(modalID) {
        document.getElementById(modalID).classList.toggle("hidden");
    }

    function submitForm(isActiveValue) {
        const form = document.getElementById('announcementForm');
        const activeInput = document.getElementById('isActiveInput');
        
        if(form && activeInput) {
            // 1. Asignamos el valor (1 = Publicar, 0 = Borrador)
            activeInput.value = isActiveValue;
            
            // 2. Enviamos el formulario al servidor
            form.submit();
        }
    }

</script>
@endsection