@extends('layouts.app-su')

@section('title', 'Universidades')

@section('view-contenido')

<div class="flex-1 flex flex-col overflow-hidden">
    
    <header class="h-20 bg-white dark:bg-gray-800 shadow-sm flex items-center justify-between px-4 md:px-8 transition-colors duration-300">
        <div class="flex items-center">
            <button id="open-sidebar-button" class="text-gray-500 dark:text-gray-200 focus:outline-none lg:hidden mr-4"><i class="fas fa-bars fa-2x"></i></button>
            <h2 class="text-xl md:text-2xl font-bold text-gray-700 dark:text-white">Universidades</h2>
        </div>
        <div class="flex items-center space-x-4">
            <button class="text-gray-400 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-200"><i class="fas fa-bell fa-lg"></i></button>
        </div>
    </header>

    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 dark:bg-gray-900 p-4 md:p-8 transition-colors duration-300">
        
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div class="relative w-full md:w-96">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" placeholder="Buscar universidad..." class="pl-10 pr-4 py-2 w-full border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
            </div>
            
            <button onclick="toggleModal('add-university-modal')" class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium shadow-lg transition transform hover:-translate-y-0.5 flex items-center justify-center">
                <i class="fas fa-plus mr-2"></i> Agregar Universidad
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-shadow border border-gray-100 dark:border-gray-700 overflow-hidden group">
                <div class="h-24 bg-gradient-to-r from-red-800 to-red-600 relative">
                    <div class="absolute -bottom-10 left-6">
                        <div class="h-20 w-20 bg-white dark:bg-gray-700 rounded-lg shadow-lg flex items-center justify-center p-2 border-4 border-white dark:border-gray-800">
                            <span class="text-2xl font-bold text-red-700">UES</span> </div>
                    </div>
                    <div class="absolute top-4 right-4 bg-green-500/90 text-white text-xs px-2 py-1 rounded-full backdrop-blur-sm">
                        Activa
                    </div>
                </div>
                
                <div class="pt-12 px-6 pb-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-white transition-colors">Universidad de El Salvador</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">San Salvador, El Salvador</p>
                    
                    <div class="flex items-center justify-between mt-4 py-4 border-t border-gray-100 dark:border-gray-700">
                        <div class="text-center">
                            <span class="block text-lg font-bold text-gray-800 dark:text-gray-200">124</span>
                            <span class="text-xs text-gray-500">Carreras</span>
                        </div>
                        <div class="text-center border-l border-gray-200 dark:border-gray-700 pl-4">
                            <span class="block text-lg font-bold text-gray-800 dark:text-gray-200">5.2k</span>
                            <span class="text-xs text-gray-500">Alumnos</span>
                        </div>
                    </div>

                    <div class="flex gap-2 mt-2">
                        <button class="flex-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 py-2 rounded-lg text-sm font-medium hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition">
                            Ver Carreras
                        </button>
                        <button class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-white transition">
                            <i class="fas fa-cog"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-shadow border border-gray-100 dark:border-gray-700 overflow-hidden group">
                <div class="h-24 bg-gradient-to-r from-blue-800 to-blue-600 relative">
                    <div class="absolute -bottom-10 left-6">
                        <div class="h-20 w-20 bg-white dark:bg-gray-700 rounded-lg shadow-lg flex items-center justify-center p-2 border-4 border-white dark:border-gray-800">
                            <span class="text-2xl font-bold text-blue-700">UCA</span>
                        </div>
                    </div>
                    <div class="absolute top-4 right-4 bg-green-500/90 text-white text-xs px-2 py-1 rounded-full backdrop-blur-sm">Activa</div>
                </div>
                <div class="pt-12 px-6 pb-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-white transition-colors">UCA El Salvador</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Antiguo Cuscatlán</p>
                    
                    <div class="flex items-center justify-between mt-4 py-4 border-t border-gray-100 dark:border-gray-700">
                        <div class="text-center"><span class="block text-lg font-bold text-gray-800 dark:text-gray-200">45</span><span class="text-xs text-gray-500">Carreras</span></div>
                        <div class="text-center border-l border-gray-200 dark:border-gray-700 pl-4"><span class="block text-lg font-bold text-gray-800 dark:text-gray-200">2.1k</span><span class="text-xs text-gray-500">Alumnos</span></div>
                    </div>
                    <div class="flex gap-2 mt-2">
                        <button class="flex-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 py-2 rounded-lg text-sm font-medium hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition">Ver Carreras</button>
                        <button class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-white transition"><i class="fas fa-cog"></i></button>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-shadow border border-gray-100 dark:border-gray-700 overflow-hidden group">
                <div class="h-24 bg-gradient-to-r from-yellow-500 to-orange-500 relative">
                    <div class="absolute -bottom-10 left-6">
                        <div class="h-20 w-20 bg-white dark:bg-gray-700 rounded-lg shadow-lg flex items-center justify-center p-2 border-4 border-white dark:border-gray-800">
                            <span class="text-xl font-bold text-orange-600">UDB</span>
                        </div>
                    </div>
                    <div class="absolute top-4 right-4 bg-green-500/90 text-white text-xs px-2 py-1 rounded-full backdrop-blur-sm">Activa</div>
                </div>
                <div class="pt-12 px-6 pb-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-white transition-colors">Universidad Don Bosco</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Soyapango</p>
                    
                    <div class="flex items-center justify-between mt-4 py-4 border-t border-gray-100 dark:border-gray-700">
                        <div class="text-center"><span class="block text-lg font-bold text-gray-800 dark:text-gray-200">58</span><span class="text-xs text-gray-500">Carreras</span></div>
                        <div class="text-center border-l border-gray-200 dark:border-gray-700 pl-4"><span class="block text-lg font-bold text-gray-800 dark:text-gray-200">3.4k</span><span class="text-xs text-gray-500">Alumnos</span></div>
                    </div>
                    <div class="flex gap-2 mt-2">
                        <button class="flex-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 py-2 rounded-lg text-sm font-medium hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition">Ver Carreras</button>
                        <button class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-white transition"><i class="fas fa-cog"></i></button>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

<div id="add-university-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen py-4 px-4 text-center sm:block sm:p-0 items-center">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="toggleModal('add-university-modal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-[1.5rem] text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-university text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">Agregar Nueva Universidad</h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre de la Institución</label>
                                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm px-3 py-2 border" placeholder="Ej. Universidad de El Salvador">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Departamento de la Institución</label>
                                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm px-3 py-2 border" placeholder="Ej. San Salvador, El Salvador">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Siglas / Acrónimo</label>
                                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm px-3 py-2 border" placeholder="Ej. UES">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dominio</label>
                                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm px-3 py-2 border" placeholder="Ej. ues.edu.sv">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Color Primario (Hex)</label>
                                <div class="flex items-center mt-1">
                                    <input type="color" class="h-8 w-8 rounded cursor-pointer border-0 p-0 mr-2" value="#4F46E5">
                                    <input type="text" class="flex-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm px-3 py-2 border" value="#4F46E5">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm" onclick="toggleModal('add-university-modal')">
                    Guardar
                </button>
                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="toggleModal('add-university-modal')">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
	// --- LOGICA MODAL ---
    function toggleModal(modalID) {
        document.getElementById(modalID).classList.toggle("hidden");
    }
</script>
@endsection