<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="theme-color" content="">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/icon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <title>Lau - @yield('title', 'Panel')</title>

    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @livewireStyles()
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-300">

    <div class="flex h-screen overflow-hidden relative">

        <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-indigo-900 text-white flex flex-col shadow-xl transform -translate-x-full transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0 lg:z-auto">
            <div class="h-20 flex items-center justify-center border-b border-indigo-800 px-4 relative">

                <img src="https://res.cloudinary.com/dtmemrt1j/image/upload/v1764889353/Frame_1_3_hz7gdd.png" 
                     alt="Lau Logo" 
                     class="h-14 w-auto object-contain transition-transform hover:scale-105">
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto no-scrollbar">
                <a href="{{ route('su.dash') }}" class="flex items-center px-4 py-3 rounded-lg 
                @if(Route::is('su.dash')) bg-indigo-800 text-white @else py-2 text-indigo-100 hover:bg-indigo-800 hover:text-white transition @endif
                ">
                    <i class="fas fa-chart-line w-6"></i> <span class="font-medium">Dashboard</span>
                </a>
                
                <p class="px-4 text-xs font-semibold text-indigo-400 uppercase mt-4">Académico</p>
                <a href="{{ route('su.uni') }}" class="flex items-center px-4 py-2 rounded-lg 
                 @if(Route::is('su.uni')) bg-indigo-800 text-white @else py-2 text-indigo-100 hover:bg-indigo-800 hover:text-white transition @endif
                 ">
                    <i class="fas fa-university w-6"></i> <span>Universidades</span>
                </a>
                <a href="{{ route('su.uni.ca') }}" class="flex items-center px-4 py-2 rounded-lg 
                 @if(Route::is('su.uni.ca')) bg-indigo-800 text-white @else py-2 text-indigo-100 hover:bg-indigo-800 hover:text-white transition @endif
                 ">
                    <i class="fas fa-book w-6"></i> <span>Carreras</span>
                </a>

                <p class="px-4 text-xs font-semibold text-indigo-400 uppercase mt-4">Comunidad</p>
                <a href="#" class="flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-800 hover:text-white rounded-lg transition">
                    <i class="fas fa-medal w-6"></i> <span>Insignias</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-800 hover:text-white rounded-lg transition">
                    <i class="fas fa-bullhorn w-6"></i> <span>Anuncios</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-800 hover:text-white rounded-lg transition">
                    <i class="fas fa-users w-6"></i> <span>Usuarios</span>
                </a>

                <p class="px-4 text-xs font-semibold text-indigo-400 uppercase mt-4">Gestión</p>
                <a href="#" class="flex items-center px-4 py-2 text-indigo-100 hover:bg-indigo-800 hover:text-white rounded-lg transition">
                    <i class="fas fa-file-alt w-6"></i> <span>Reportes</span>
                    <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">3</span>
                </a>
            </nav>

            <div class="px-4 pb-2">
                <button id="theme-toggle" class="flex items-center justify-center w-full px-4 py-2 text-sm text-indigo-200 bg-indigo-800/50 border border-indigo-700 rounded-lg hover:bg-indigo-700 hover:text-white transition-colors">
                    <i id="theme-icon" class="fas fa-moon mr-2"></i>
                    <span id="theme-text">Modo Oscuro</span>
                </button>
            </div>

            <div class="p-4 border-t border-indigo-800 flex items-center bg-indigo-900 relative group cursor-pointer transition-colors hover:bg-indigo-800">
                
                <div class="flex items-center flex-1 min-w-0">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" class="h-10 w-10 rounded-full shrink-0">
                    <div class="ml-3 overflow-hidden">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-indigo-300 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <form action="{{ route('logoutus') }}" method="POST">
                    @csrf
                    <button 
                            class="ml-2 p-2 text-red-400 hover:text-white hover:bg-red-500 rounded-lg transition-all duration-300 opacity-100 lg:opacity-0 lg:group-hover:opacity-100" 
                            title="Cerrar Sesión">
                        <i class="fas fa-sign-out-alt fa-lg"></i>
                    </button>
                </form>
            </div>
        </aside>

        <div id="sidebar-backdrop" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-30 hidden lg:hidden transition-opacity duration-300"></div>

        @yield('view-contenido') 
            
    </div>

    <script>
        const html = document.documentElement;
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        const themeText = document.getElementById('theme-text');
        
        const sidebar = document.getElementById('sidebar');
        const openSidebarButton = document.getElementById('open-sidebar-button');
        const closeSidebarButton = document.getElementById('close-sidebar-button');
        const sidebarBackdrop = document.getElementById('sidebar-backdrop');

        function updateThemeUI(isDark) {
            if (isDark) {
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
                themeText.textContent = "Modo Claro";
            } else {
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
                themeText.textContent = "Modo Oscuro";
            }
        }

        if (html.classList.contains('dark')) {
            updateThemeUI(true);
        } else {
            updateThemeUI(false);
        }

        // Evento Click
        themeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            const isDark = html.classList.contains('dark');
            
            // Guardamos la preferencia en LocalStorage
            if (isDark) {
                localStorage.setItem('theme', 'dark');
            } else {
                localStorage.setItem('theme', 'light');
            }

            updateThemeUI(isDark);
        });


        // --- FUNCIONES DE SIDEBAR (MÓVIL) ---
        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebarBackdrop.classList.remove('hidden');
        }
        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            sidebarBackdrop.classList.add('hidden');
        }

        openSidebarButton.addEventListener('click', openSidebar);
        sidebarBackdrop.addEventListener('click', closeSidebar);

    </script>
</body>

</html>