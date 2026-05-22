<header class="w-full max-w-3xl mx-auto px-6 py-8">
    <div class="flex justify-between items-center">
        <a href="{{ route('home') }}" class="text-lg font-medium tracking-tight text-gray-950 dark:text-white relative z-50">LvBlog.</a>

        <div class="flex items-center space-x-2 sm:space-x-6 relative z-50">
            <button onclick="toggleDarkMode()"
                class="p-2 text-gray-400 hover:text-gray-950 dark:hover:text-white transition-colors focus:outline-none"
                aria-label="Cambiar modo de color">
                <svg id="sun-icon" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z">
                    </path>
                </svg>
                <svg id="moon-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                    </path>
                </svg>
            </button>

            <nav class="hidden sm:flex items-center space-x-6 text-sm font-medium">
                <a href="{{ route('home') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-950 dark:hover:text-white transition-colors">Inicio</a>
                <a href="{{ route('public.blogs.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-950 dark:hover:text-white transition-colors">Blog</a>

                @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-950 dark:hover:text-white transition-colors">Ir al Dashboard</a>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-950 dark:hover:text-white transition-colors">Log in</a>
                @endguest
            </nav>

            <button onclick="toggleMenu()" 
                class="sm:hidden p-2 text-gray-500 dark:text-gray-400 hover:text-gray-950 dark:hover:text-white focus:outline-none w-8 h-8 flex items-center justify-center relative" 
                aria-label="Abrir menú de navegación">
                <div class="w-5 h-4 relative flex items-center justify-center">
                    <span id="line-top" class="absolute w-full h-0.5 bg-current transform transition-all duration-300 -translate-y-1.5 origin-center"></span>
                    <span id="line-mid" class="absolute w-full h-0.5 bg-current transition-all duration-300"></span>
                    <span id="line-bot" class="absolute w-full h-0.5 bg-current transform transition-all duration-300 translate-y-1.5 origin-center"></span>
                </div>
            </button>
        </div>
    </div>

    <div id="mobile-menu" class="hidden opacity-0 pointer-events-none transition-all duration-300 fixed inset-0 h-screen w-screen bg-gray-50/95 dark:bg-gray-950/95 backdrop-blur-md z-40 flex flex-col justify-center items-center">
        <nav class="flex flex-col space-y-8 text-center text-2xl font-normal tracking-tight">
            <a href="{{ route('home') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-950 dark:hover:text-white transition-colors py-2 block">Inicio</a>
            <a href="{{ route('public.blogs.index') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-950 dark:hover:text-white transition-colors py-2 block">Blog</a>

            @auth
                <span class="w-12 h-px bg-gray-200 dark:bg-gray-800 mx-auto my-2"></span>
                <a href="{{ route('dashboard') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-950 dark:hover:text-white transition-colors py-2 block">Ir al Dashboard</a>
            @endauth

            @guest
                <span class="w-12 h-px bg-gray-200 dark:bg-gray-800 mx-auto my-2"></span>
                <a href="{{ route('login') }}" class="text-gray-600 dark:text-gray-300 hover:text-gray-950 dark:hover:text-white transition-colors py-2 block">Log in</a>
            @endguest
        </nav>
    </div>
</header>

<script>
    // Inicialización del Modo Oscuro
    (function() {
        const sunIcon = document.getElementById('sun-icon');
        const moonIcon = document.getElementById('moon-icon');

        if (document.documentElement.classList.contains('dark')) {
            sunIcon?.classList.remove('hidden');
            moonIcon?.classList.add('hidden');
        } else {
            sunIcon?.classList.add('hidden');
            moonIcon?.classList.remove('hidden');
        }
    })();

    function toggleDarkMode() {
        const sunIcon = document.getElementById('sun-icon');
        const moonIcon = document.getElementById('moon-icon');

        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
            sunIcon?.classList.add('hidden');
            moonIcon?.classList.remove('hidden');
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
            sunIcon?.classList.remove('hidden');
            moonIcon?.classList.add('hidden');
        }
    }

    function toggleMenu() {
        const menu = document.getElementById('mobile-menu');
        const lineTop = document.getElementById('line-top');
        const lineMid = document.getElementById('line-mid');
        const lineBot = document.getElementById('line-bot');

        if (menu.classList.contains('hidden')) {
            // Prevenir scroll en el fondo mientras el menú está abierto
            document.body.classList.add('overflow-hidden');
            
            menu.classList.remove('hidden');
            setTimeout(() => {
                menu.classList.remove('opacity-0', 'pointer-events-none');
            }, 10);

            lineTop.classList.remove('-translate-y-1.5');
            lineTop.classList.add('rotate-45');
            
            lineMid.classList.add('opacity-0', 'scale-0');
            
            lineBot.classList.remove('translate-y-1.5');
            lineBot.classList.add('-rotate-45');
        } else {
            // Permitir scroll de nuevo
            document.body.classList.remove('overflow-hidden');
            
            menu.classList.add('opacity-0', 'pointer-events-none');
            setTimeout(() => {
                menu.classList.add('hidden');
            }, 300);

            lineTop.classList.remove('rotate-45');
            lineTop.classList.add('-translate-y-1.5');
            
            lineMid.classList.remove('opacity-0', 'scale-0');
            
            lineBot.classList.remove('-rotate-45');
            lineBot.classList.add('translate-y-1.5');
        }
    }
</script>