@props(['title' => 'Estudio'])

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    @vite(['resources/css/tailwind.css'])

    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

<body class="bg-gray-50 text-gray-900 dark:bg-gray-950 dark:text-gray-100 font-sans antialiased selection:bg-gray-200 dark:selection:bg-gray-800 flex flex-col min-h-screen">

    <x-app-header />

    <main class="w-full max-w-3xl mx-auto px-6 py-12 flex-1">
        {{ $slot }}
    </main>

    <footer class="w-full max-w-3xl mx-auto px-6 pt-16 pb-8 text-xs font-mono text-gray-400 dark:text-gray-500 flex justify-between border-t border-gray-200 dark:border-gray-800 mt-auto">
        <div>© 2026</div>
        <div class="space-x-4">
            <a 
                href="https://github.com/lucasdvillegas" 
                target="_blank" 
                rel="noopener noreferrer" 
                class="hover:text-gray-600 dark:hover:text-gray-300">Github</a>
            <a 
                href="https://www.linkedin.com/in/lucasdavillegas/" 
                target="_blank" 
                rel="noopener noreferrer" 
                class="hover:text-gray-600 dark:hover:text-gray-300">LinkedIn</a>
        </div>
    </footer>
</body>

</html>