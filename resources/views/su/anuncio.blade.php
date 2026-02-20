@extends('layouts.app-su')

@section('view-contenido')
<div class="flex-1 flex flex-col h-screen relative overflow-hidden bg-gray-50 dark:bg-gray-900">
    
    {{-- ==========================================
         VISTA 1: LISTA DE ANUNCIOS (INDEX)
         ========================================== --}}
    <div id="view-index" class="flex flex-col h-full view-transition">
        <header class="h-20 bg-white dark:bg-gray-800 shadow-sm flex items-center justify-between px-8 shrink-0">
            <div class="flex items-center gap-4">
                <button id="open-sidebar-button" class="text-gray-500 dark:text-gray-200 focus:outline-none lg:hidden mr-4">
                    <i class="fas fa-bars fa-2x"></i>
                </button>
                <h2 class="text-2xl font-bold text-gray-700 dark:text-white">Gestión de Anuncios</h2>
                <span class="hidden md:block bg-indigo-100 text-indigo-700 text-xs font-bold px-3 py-1 rounded-full">
                    {{ $activeCount ?? 0 }} Activos
                </span>
            </div>
            <button onclick="openCreateMode()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg transition transform hover:-translate-y-0.5 flex items-center">
                <i class="fas fa-plus mr-2"></i> Nuevo Anuncio
            </button>
        </header>

        <div class="flex-1 overflow-y-auto p-6 md:p-8 custom-scrollbar">
            @if (session('success'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="mb-6 px-4 py-3 text-green-700 bg-green-100 border border-green-400 rounded-lg flex items-center shadow-sm fade-in">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif

            {{-- GRID DE BANNERS --}}
            <div id="banners-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 fade-in">
                @forelse($banners as $banner)
                    @php
                        switch ($banner->type) {
                            case 'feature':
                                $style = ['border' => 'bg-purple-500', 'icon_bg' => 'bg-purple-100 dark:bg-purple-900/30', 'icon_text' => 'text-purple-600 dark:text-purple-400', 'icon' => 'fas fa-image'];
                                break;
                            case 'update':
                                $style = ['border' => 'bg-green-500', 'icon_bg' => 'bg-green-100 dark:bg-green-900/30', 'icon_text' => 'text-green-600 dark:text-green-400', 'icon' => 'fas fa-sync-alt'];
                                break;
                            case 'warning':
                                $style = ['border' => 'bg-yellow-500', 'icon_bg' => 'bg-yellow-100 dark:bg-yellow-900/30', 'icon_text' => 'text-yellow-600 dark:text-yellow-400', 'icon' => 'fas fa-exclamation-triangle'];
                                break;
                            case 'info':
                            default:
                                $style = ['border' => 'bg-blue-500', 'icon_bg' => 'bg-blue-100 dark:bg-blue-900/30', 'icon_text' => 'text-blue-600 dark:text-blue-400', 'icon' => 'fas fa-info-circle'];
                                break;
                        }
                    @endphp

                    <div class="banner-item bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition group h-full flex flex-col" data-status="{{ $banner->is_active ? 'active' : 'draft' }}">
                        <div class="h-2 {{ $style['border'] }}"></div>
                        <div class="p-5 flex-1 flex flex-col">
                            <div class="flex justify-between items-start mb-3">
                                <div class="{{ $style['icon_bg'] }} {{ $style['icon_text'] }} h-10 w-10 rounded-full flex items-center justify-center">
                                    <i class="{{ $style['icon'] }}"></i>
                                </div>
                                <div class="flex gap-1">
                                    <button onclick='openEditMode(@json($banner))' class="text-gray-400 hover:text-indigo-600 p-1 transition" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="confirmDelete('{{ route('su.ads.delete', $banner->id) }}')" class="text-gray-400 hover:text-red-500 p-1 transition" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <h3 class="font-bold text-gray-800 dark:text-white mb-1 truncate" title="{{ $banner->title }}">{{ $banner->title }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mb-4 flex-1">{{ $banner->content }}</p>
                            <div class="mt-auto pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center text-xs">
                                @if($banner->is_active)
                                    <span class="text-green-500 font-bold flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span> Activo</span>
                                @else
                                    <span class="text-gray-400 font-bold flex items-center"><span class="w-2 h-2 bg-gray-400 rounded-full mr-1"></span> Borrador</span>
                                @endif
                                <span class="text-gray-400" title="{{ $banner->created_at }}">{{ $banner->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center p-12 text-center">
                        <div class="h-24 w-24 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-bullhorn text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">No hay anuncios</h3>
                        <p class="text-gray-500 dark:text-gray-400 max-w-sm">Aún no has creado ningún anuncio. Crea el primero para informar a tus usuarios.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ==========================================
         VISTA 2: CREAR / EDITAR ANUNCIO (CREATE)
         ========================================== --}}
    <div id="view-create" class="flex-col h-full hidden view-transition">
        <header class="h-20 bg-white dark:bg-gray-800 shadow-sm flex items-center justify-between px-4 md:px-8 shrink-0 z-20 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-4">
                <button onclick="attemptGoBack()" class="text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-white transition">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </button>
                <h2 id="formTitle" class="text-xl font-bold text-gray-700 dark:text-white">Crear Nuevo Anuncio</h2>
            </div>
            <div class="flex gap-2 md:gap-3">
                <button onclick="resetForm()" class="hidden md:block text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white px-4 py-2 text-sm font-medium">Limpiar</button>
                <button onclick="submitForm(1)" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 md:px-6 py-2 rounded-xl text-sm font-bold shadow-lg transition transform hover:-translate-y-0.5">
                    <i class="fas fa-paper-plane mr-2"></i> <span class="hidden md:inline">Publicar</span>
                </button>
            </div>
        </header>

        <div class="flex-1 flex overflow-hidden">
            {{-- Columna Izquierda: Formulario --}}
            <div class="w-full md:w-1/2 lg:w-5/12 overflow-y-auto p-6 md:p-10 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 z-10 custom-scrollbar">
                <form id="announcementForm" action="{{ route('su.ads.create') }}" data-create-url="{{ route('su.ads.create') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div id="method-spoof"></div>
                    <input type="hidden" name="_method" id="methodInput" value="PUT" disabled>
                    <input type="hidden" name="is_active" id="isActiveInput" value="1">

                    {{-- Tipos --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">Tipo de Aviso</label>
                        <div class="grid grid-cols-4 gap-2 md:gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" id="hasimgbt" name="type" value="feature" class="peer sr-only" checked onchange="updatePreview()">
                                <div class="h-10 md:h-12 rounded-xl border-2 border-gray-200 dark:border-gray-700 peer-checked:border-purple-500 peer-checked:bg-purple-50 dark:peer-checked:bg-purple-900/20 flex items-center justify-center text-gray-400 peer-checked:text-purple-500 transition-all">
                                    <i class="fa-solid fa-image"></i>
                                </div>
                                <span class="block text-center text-[10px] md:text-xs mt-1 text-gray-500">Feature</span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="update" class="peer sr-only" onchange="updatePreview()">
                                <div class="h-10 md:h-12 rounded-xl border-2 border-gray-200 dark:border-gray-700 peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 flex items-center justify-center text-gray-400 peer-checked:text-green-500 transition-all">
                                    <i class="fas fa-sync-alt"></i>
                                </div>
                                <span class="block text-center text-[10px] md:text-xs mt-1 text-gray-500">Update</span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="warning" class="peer sr-only" onchange="updatePreview()">
                                <div class="h-10 md:h-12 rounded-xl border-2 border-gray-200 dark:border-gray-700 peer-checked:border-yellow-500 peer-checked:bg-yellow-50 dark:peer-checked:bg-yellow-900/20 flex items-center justify-center text-gray-400 peer-checked:text-yellow-500 transition-all">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <span class="block text-center text-[10px] md:text-xs mt-1 text-gray-500">Warning</span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" id="hasinfobt" name="type" value="info" class="peer sr-only" onchange="updatePreview()">
                                <div class="h-10 md:h-12 rounded-xl border-2 border-gray-200 dark:border-gray-700 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 flex items-center justify-center text-gray-400 peer-checked:text-blue-500 transition-all">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <span class="block text-center text-[10px] md:text-xs mt-1 text-gray-500">Info</span>
                            </label>
                        </div>
                    </div>

                    {{-- Imagen (URL) --}}
                    <div id="buttonImg" class="transition-all duration-300">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Imagen (URL)</label>
                        <input type="text" name="image_url" placeholder="https://ejemplo.com/imagen.png" oninput="updatePreview()" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>

                    {{-- Contenidos --}}
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Título</label>
                            <input type="text" id="inputTitle" name="title" oninput="updatePreview()" placeholder="Ej. Nueva actualización" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descripción</label>
                            <textarea id="inputContent" name="content" oninput="updatePreview()" rows="3" placeholder="Detalles del anuncio..." class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition resize-none"></textarea>
                        </div>
                    </div>

                    {{-- Botón CTA --}}
                    <div class="pt-2 border-t border-gray-100 dark:border-gray-700">
                        <label id="toggleLabel" class="flex items-center justify-between cursor-pointer group">
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white">Añadir Botón de Acción</h4>
                                <p class="text-xs text-gray-500">Permite redirigir a los usuarios a un link.</p>
                            </div>
                            <div class="relative">
                                <input type="checkbox" id="hasButton" class="sr-only peer" onchange="toggleButtonInput(); updatePreview();">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                            </div>
                        </label>
                        
                        <div id="buttonConfig" class="mt-4 space-y-4 hidden opacity-0 transition-all duration-300">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Texto del botón</label>
                                <input type="text" id="inputBtnText" name="action_text" value="Entendido" oninput="updatePreview()" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Enlace (URL)</label>
                                <input type="text" name="action_url" placeholder="https://..." class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition">
                            </div>
                        </div>
                    </div>

                    {{-- Fechas --}}
                    <div class="pt-4 border-t border-gray-100 dark:border-gray-700 grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha Inicio</label>
                            <input type="date" name="start_date" id="start_date" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha Fin</label>
                            <input type="date" name="end_date" id="end_date" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                        </div>
                    </div>
                </form>
            </div>

            {{-- Columna Derecha: Vista Previa en Vivo --}}
            <div class="hidden md:flex w-1/2 lg:w-7/12 preview-bg flex-col items-center justify-center p-8 relative">
                <div class="absolute top-6 bg-white dark:bg-gray-800 p-1 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 flex">
                    <button onclick="switchDevice('mobile')" id="btn-mobile" class="px-4 py-2 rounded-md text-sm font-medium transition-colors bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-white"><i class="fas fa-mobile-alt mr-2"></i> Móvil</button>
                    <button onclick="switchDevice('desktop')" id="btn-desktop" class="px-4 py-2 rounded-md text-sm font-medium transition-colors text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"><i class="fas fa-desktop mr-2"></i> PC</button>
                </div>

                {{-- Preview Móvil --}}
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

                {{-- Preview Desktop --}}
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
                                        <button id="btn-default-desktop" class="px-6 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium rounded-xl hover:bg-gray-200">Cerrar</button>
                                        <button id="btn-main-desktop" class="px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-xl shadow-lg hover:bg-indigo-700 hidden">Entendido</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="absolute bottom-4 text-gray-400 text-xs font-mono" id="device-label">Vista previa móvil</p>
            </div>
        </div>
    </div>
</div>

{{-- ==========================================
     MODALES GLOBALES
     ========================================== --}}

{{-- Modal para móviles: Avisa que no se puede editar en móvil --}}
<div id="globalBanner" class="block md:hidden fixed inset-0 z-50 flex items-end md:items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4 transition-opacity duration-300">
    <div class="relative w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl ring-1 ring-gray-900/5 transform transition-transform duration-300 ease-out">
        <div class="flex justify-center pt-3 pb-1 md:hidden cursor-grab active:cursor-grabbing p-2">
            <div class="w-12 h-1.5 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
        </div>
        <div class="flex justify-center pt-4 pb-2">
            <div class="h-16 w-16 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full flex items-center justify-center text-3xl shadow-sm animate-bounce-slow">
                <i class="fas fa-hand"></i>
            </div>
        </div>
        <div class="px-6 pb-6 text-center">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Alto 🤚</h3>
            <div class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed mb-6">
                Esta interfaz no está diseñada para móviles. Para poder crear o editar anuncios y ver la vista previa, por favor ingresa desde un ordenador.
            </div>
            <button onclick="document.getElementById('globalBanner').classList.add('hidden')" class="w-full py-3 px-4 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-xl transition">
                Entendido
            </button>
        </div>
    </div>
</div>

{{-- Modal Cambios Sin Guardar --}}
<div id="unsaved-modal" class="hidden fixed inset-0 z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div id="unsaved-backdrop" onclick="closeModal('unsaved')" class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity opacity-0"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center text-center sm:items-center p-4">
            <div id="unsaved-card" class="relative transform overflow-hidden bg-white dark:bg-gray-800 text-left shadow-2xl rounded-2xl transition-all w-full sm:max-w-lg translate-y-full sm:translate-y-0 sm:scale-95 opacity-0 sm:opacity-100">
                <div class="bg-white dark:bg-gray-800 px-6 pt-5 pb-4 sm:p-8">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">¿Quieres salir sin publicar?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Tienes información en el formulario. Si sales, perderás los cambios no guardados.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 sm:flex sm:flex-row-reverse gap-2 sm:px-8">
                    <button type="button" onclick="handleExitAction('draft')" class="w-full sm:w-auto inline-flex justify-center rounded-xl bg-indigo-600 px-4 py-3 text-sm font-bold text-white shadow-sm hover:bg-indigo-700 focus:outline-none transition-transform active:scale-95">Guardar Borrador</button>
                    <button type="button" onclick="handleExitAction('discard')" class="mt-3 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-xl bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 px-4 py-3 text-sm font-bold hover:bg-red-200 dark:hover:bg-red-900/50 focus:outline-none transition-transform active:scale-95">Descartar</button>
                    <button type="button" onclick="closeModal('unsaved')" class="mt-3 sm:mt-0 sm:mr-auto w-full sm:w-auto inline-flex justify-center rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Eliminar Banner --}}
<div id="delete-modal" class="hidden fixed inset-0 z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div id="delete-backdrop" onclick="closeModal('delete')" class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity opacity-0"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center text-center sm:items-center p-4">
            <div id="delete-card" class="relative transform overflow-hidden bg-white dark:bg-gray-800 text-left shadow-2xl rounded-2xl transition-all w-full sm:max-w-lg translate-y-full sm:translate-y-0 sm:scale-95 opacity-0 sm:opacity-100">
                <div class="bg-white dark:bg-gray-800 px-6 pt-5 pb-4 sm:p-8">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-trash text-red-600 dark:text-red-400"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">¿Quieres Borrar este banner?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Esta acción no se podrá deshacer.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 sm:flex sm:flex-row-reverse gap-2 sm:px-8">
                    <form id="delete-form" action="" method="POST" class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent bg-red-600 text-white px-4 py-3 text-sm font-bold hover:bg-red-700 focus:outline-none transition-transform active:scale-95">Eliminar</button>
                    </form>
                    <button type="button" onclick="closeModal('delete')" class="mt-3 sm:mt-0 sm:mr-auto w-full sm:w-auto inline-flex justify-center rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ==========================================
     SCRIPTS
     ========================================== --}}
<script>
    // Referencias a inputs
    const inputTitle = document.getElementById('inputTitle');
    const inputContent = document.getElementById('inputContent');
    const inputBtnText = document.getElementById('inputBtnText');
    let currentBannerId = null;

    // Config Tipos
    const types = {
        'feature': { icon: 'image', color: 'purple' },
        'update':  { icon: 'sync-alt', color: 'green' }, 
        'warning': { icon: 'exclamation-triangle', color: 'yellow' }, 
        'info':    { icon: 'info-circle', color: 'blue' }
    };

    // --- MODALES DINÁMICOS ---
    function openModal(idName) {
        const modal = document.getElementById(`${idName}-modal`);
        const backdrop = document.getElementById(`${idName}-backdrop`);
        const card = document.getElementById(`${idName}-card`);
        if (!modal || !backdrop || !card) return;
        modal.classList.remove('hidden');
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            card.classList.remove('translate-y-full', 'opacity-0', 'sm:scale-95');
            card.classList.add('sm:scale-100');
        }, 10);
    }

    function closeModal(idName) {
        const modal = document.getElementById(`${idName}-modal`);
        const backdrop = document.getElementById(`${idName}-backdrop`);
        const card = document.getElementById(`${idName}-card`);
        if (!modal || !backdrop || !card) return;
        backdrop.classList.add('opacity-0');
        card.classList.add('translate-y-full', 'opacity-0', 'sm:scale-95');
        card.classList.remove('sm:scale-100');
        setTimeout(() => {
            modal.classList.add('hidden');
            card.style.transform = ''; 
        }, 300);
    }

    function confirmDelete(deleteUrl) {
        document.getElementById('delete-form').action = deleteUrl;
        openModal('delete');
    }

    // --- NAVEGACIÓN ENTRE VISTAS ---
    function toggleView(viewName) {
        const viewIndex = document.getElementById('view-index');
        const viewCreate = document.getElementById('view-create');

        if (viewName === 'create') {
            viewIndex.classList.add('hidden'); viewIndex.classList.remove('flex');
            viewCreate.classList.remove('hidden'); viewCreate.classList.add('flex');
        } else {
            viewCreate.classList.add('hidden'); viewCreate.classList.remove('flex');
            viewIndex.classList.remove('hidden'); viewIndex.classList.add('flex');
        }
    }

    function openCreateMode() {
        resetForm();
        toggleView('create');
    }

    function openEditMode(banner) {
        const form = document.getElementById('announcementForm');
        form.reset();
        currentBannerId = banner.id;
        document.getElementById('formTitle').innerText = "Editar Anuncio";
        form.action = `/us/su/lau/ads/update/${banner.id}`; // Asegúrate que esta ruta exista
        
        const methodInput = document.getElementById('methodInput');
        if (methodInput) methodInput.disabled = false; // Modo PUT
        
        inputTitle.value = banner.title || '';
        inputContent.value = banner.content || '';
        document.getElementById('isActiveInput').value = banner.is_active ? "1" : "0";

        if (banner.start_date) document.getElementById('start_date').value = banner.start_date.split('T')[0];
        if (banner.end_date) document.getElementById('end_date').value = banner.end_date.split('T')[0];

        if (banner.type) {
            const radio = document.querySelector(`input[name="type"][value="${banner.type}"]`);
            if (radio) radio.checked = true;
        }

        const imgInput = document.querySelector('input[name="image_url"]');
        if (banner.image_url) {
            if(banner.type === 'feature') document.getElementById('hasimgbt').checked = true;
            imgInput.value = banner.image_url;
        } else {
            imgInput.value = '';
        }

        const hasBtnCheckbox = document.getElementById('hasButton');
        if (banner.action_url) {
            hasBtnCheckbox.checked = true;
            document.querySelector('input[name="action_url"]').value = banner.action_url;
            inputBtnText.value = banner.action_text || 'Entendido';
        } else {
            hasBtnCheckbox.checked = false;
            document.querySelector('input[name="action_url"]').value = '';
            inputBtnText.value = 'Entendido';
        }

        updatePreview();
        toggleButtonInput();
        toggleView('create'); 
    }

    function resetForm() {
        const form = document.getElementById('announcementForm');
        form.reset();
        currentBannerId = null;
        document.getElementById('formTitle').innerText = "Crear Nuevo Anuncio";
        
        if (form.dataset.createUrl) form.action = form.dataset.createUrl;
        
        const methodInput = document.getElementById('methodInput');
        if (methodInput) methodInput.disabled = true; // Modo POST
        
        document.getElementById('isActiveInput').value = "1";
        document.getElementById('hasimgbt').checked = true;
        
        document.getElementById('buttonConfig').classList.add('hidden', 'opacity-0');
        document.getElementById('hasButton').checked = false;
        
        updatePreview();
        toggleButtonInput();
    }

    function attemptGoBack() {
        if (inputTitle.value.trim() !== '' || inputContent.value.trim() !== '') {
            openModal('unsaved');
        } else {
            toggleView('index'); 
        }
    }

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

    function submitForm(isActiveValue) {
        const form = document.getElementById('announcementForm');
        if(form) {
            document.getElementById('isActiveInput').value = isActiveValue;
            form.submit();
        }
    }

    // --- PREVISUALIZACIÓN ---
    function updatePreview() {
        const hasimgbt = document.getElementById('hasimgbt').checked;
        const buttonImg = document.getElementById('buttonImg');
        const hasinfobt = document.getElementById('hasinfobt').checked;
        const btdefadesk = document.getElementById('btn-default-desktop');
        const btdefamobi = document.getElementById('btn-default-mobile');
        const hasButtonadd = document.getElementById('hasButton');
        const toggleLabel = document.getElementById('toggleLabel');
        const imgUrlVal = document.querySelector('input[name="image_url"]').value;
        
        const titleVal = inputTitle.value || 'Título del Anuncio';
        const contentVal = inputContent.value || 'Aquí aparecerá la descripción de tu anuncio tal cual lo verán los usuarios.';
        const btnVal = inputBtnText.value || 'Entendido';
        
        const selectedType = document.querySelector('input[name="type"]:checked').value;
        const config = types[selectedType];
        
        let colorClasses = '';
        if(selectedType === 'info') colorClasses = 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400';
        else if(selectedType === 'update') colorClasses = 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400';
        else if(selectedType === 'warning') colorClasses = 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400';
        else if(selectedType === 'feature') colorClasses = 'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400';

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
            hasButtonadd.checked = true;
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

        let iconHTML = '';
        if (hasimgbt && imgUrlVal) {
            iconHTML = `<img src="${imgUrlVal}" class="h-20 w-20 object-cover rounded-xl shadow-sm" alt="Preview" onerror="this.outerHTML='<div class=\\'${colorClasses} h-16 w-16 rounded-full flex items-center justify-center text-3xl shadow-sm\\'><i class=\\'fa-solid fa-${config.icon}\\'></i></div>'">`;
        } else {
            iconHTML = `<div class="${colorClasses} h-16 w-16 rounded-full flex items-center justify-center text-3xl shadow-sm transition-colors duration-300">
                            <i class="fa-solid fa-${config.icon}"></i>
                        </div>`;
        }

        document.getElementById('title-mobile').textContent = titleVal;
        document.getElementById('content-mobile').textContent = contentVal;
        document.getElementById('btn-main-mobile').textContent = btnVal;
        document.getElementById('icon-mobile').innerHTML = iconHTML;

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
            mobileView.classList.remove('hidden'); desktopView.classList.add('hidden');
            btnMobile.classList.add('bg-indigo-100', 'text-indigo-700', 'dark:bg-indigo-900', 'dark:text-white');
            btnMobile.classList.remove('text-gray-500', 'dark:text-gray-400');
            btnDesktop.classList.remove('bg-indigo-100', 'text-indigo-700', 'dark:bg-indigo-900', 'dark:text-white');
            btnDesktop.classList.add('text-gray-500', 'dark:text-gray-400');
            label.textContent = "Vista previa móvil";
        } else {
            mobileView.classList.add('hidden'); desktopView.classList.remove('hidden');
            btnDesktop.classList.add('bg-indigo-100', 'text-indigo-700', 'dark:bg-indigo-900', 'dark:text-white');
            btnDesktop.classList.remove('text-gray-500', 'dark:text-gray-400');
            btnMobile.classList.remove('bg-indigo-100', 'text-indigo-700', 'dark:bg-indigo-900', 'dark:text-white');
            btnMobile.classList.add('text-gray-500', 'dark:text-gray-400');
            label.textContent = "Vista previa escritorio";
        }
    }

    // Inicializar
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('view') === 'create') {
            toggleView('create');
            window.history.replaceState({}, document.title, window.location.pathname);
        }
        updatePreview();
    });
</script>
@endsection