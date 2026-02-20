<div class="flex flex-col w-full space-y-1">
    @if (isset($users) && $users->count())
        @foreach ($users as $user)
            @php
                // Lógica de estado Activo/Inactivo mejorada
                $urlId = request('user_id');
                $isActive = $urlId ? ($urlId == $user->id) : $loop->first;

                $activeClass = 'bg-indigo-50 dark:bg-indigo-900/30 border-l-4 border-indigo-600';
                $inactiveClass = 'hover:bg-gray-50 dark:hover:bg-gray-700 border-l-4 border-transparent hover:border-gray-300';
                $currentClass = $isActive ? $activeClass : $inactiveClass;
                
                // Texto de profesión como respaldo
                $profession = $user->profession ?? 'Sin profesión';
                
                // Imagen de perfil con fallback a UI Avatars
                $imageSrc = $user->imagen ? asset('perfiles/' . $user->imagen) : "https://ui-avatars.com/api/?name=".urlencode($user->name ?? $user->username)."&background=random";
            @endphp

            <button 
                onclick="showUser('{{ $user->id }}')" 
                id="btn-{{ $user->id }}" 
                class="user-btn w-full flex items-center p-3 rounded-lg transition-all group {{ $currentClass }}">
                
                {{-- Sección de Imagen --}}
                <div class="relative mr-3 flex-shrink-0">
                    <img src="{{ $imageSrc }}" 
                         alt="Avatar de {{ $user->name ?? $user->username }}" 
                         class="h-10 w-10 sm:w-12 sm:h-12 rounded-full object-cover border-2 border-transparent group-hover:border-indigo-500 transition-colors">
                    
                    {{-- Indicador de estado online (Estático por ahora) --}}
                    <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-white dark:ring-gray-800 bg-green-400"></span>
                </div>

                {{-- Sección de Texto --}}
                <div class="text-left flex-1 min-w-0">
                    <div class="flex justify-between items-center">
                        <h4 class="text-sm sm:text-base font-bold text-gray-900 dark:text-white truncate flex items-center gap-1">
                            <span class="truncate">{{ $user->name ?? $user->username }}</span>
                            
                            {{-- Insignia rescatada del diseño antiguo --}}
                            @if($user->insignia)
                                <x-user-badge :badge="$user->insignia" size="small" />
                            @endif
                        </h4>
                        
                        {{-- Tiempo opcional (ej. "Hace 2m") --}}
                        <span class="text-[10px] text-gray-400 flex-shrink-0 ml-2 hidden sm:block">
                            {{ $user->created_at ? $user->created_at->shortAbsoluteDiffForHumans() : '' }}
                        </span>
                    </div>
                    
                    <p id="text-{{ $user->id }}" 
                        class="user-role-text text-xs truncate transition-colors group-hover:text-indigo-600 
                        {{ $isActive ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500' }}">
                        {{-- Muestra carrera si existe, si no, muestra profesión --}}
                        @if($user->carrera && $user->universidad)
                            {{ $user->carrera->nombre }}
                        @else
                            {{ $profession }}
                        @endif
                    </p>
                </div>

                {{-- Icono de opciones rescatado (Aparece al hacer hover) --}}
                <div class="flex-shrink-0 ml-2 opacity-0 group-hover:opacity-100 transition-opacity hidden sm:block">
                    <i class='bx bx-dots-horizontal-rounded text-gray-400 hover:text-indigo-600 text-xl'></i>
                </div>
            </button>
        @endforeach
    @else
        {{-- Estado Vacío --}}
        <div class="p-8 text-center flex flex-col items-center justify-center">
            <div class="h-12 w-12 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-3">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400">No se encontraron perfiles.</p>
        </div>
    @endif
</div>