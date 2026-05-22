<x-app-layout title="Estudio — Inicio">
    <section class="space-y-16">
        <div class="space-y-4">
            <h1 class="text-3xl font-normal tracking-tight text-gray-950 dark:text-white sm:text-4xl font-mono">
                bitacora_de_junior
            </h1>
            <p class="text-base text-gray-600 dark:text-gray-400 max-w-xl leading-relaxed">
                Un espacio pensado para documentar los
                <span
                    class="text-xs font-mono bg-gray-50 dark:bg-gray-800 text-gray-800 dark:text-gray-300 px-1.5 py-0.5 rounded border border-gray-200 dark:border-gray-800">problemas</span>
                y
                <span
                    class="text-xs font-mono bg-gray-50 dark:bg-gray-800 text-gray-800 dark:text-gray-300 px-1.5 py-0.5 rounded border border-gray-200 dark:border-gray-800">soluciones</span>
                que voy encontrando en el camino. Así también como tecnologías o momentos de offtopic. Ojalá te sirva para algo.
            </p>
        </div>

        <div class="pt-8 border-t border-gray-200 dark:border-gray-800 space-y-6">
            <div class="flex justify-between items-baseline">
                <h2 class="text-xs font-mono tracking-wider text-gray-400 dark:text-gray-500 uppercase">
                    Líneas de interés
                </h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="space-y-1">
                    <span class="text-xs text-gray-400 dark:text-gray-500 font-mono">01 // BACKEND_LOGIC</span>
                    <h3 class="text-sm font-medium text-gray-950 dark:text-white">Arquitectura Modular</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                        Estructuración de monolitos mantenibles, desacoplamiento por módulos y diseño de APIs REST sólidas.
                    </p>
                </div>
                
                <div class="space-y-1">
                    <span class="text-xs text-gray-400 dark:text-gray-500 font-mono">02 // FRONTEND_DESIGN</span>
                    <h3 class="text-sm font-medium text-gray-950 dark:text-white">Componentes Limpios</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                        Soluciones de UI centradas en rendimiento, optimización y experiencia de usuario.
                    </p>
                </div>
            </div>
        </div>

        <div class="pt-8 border-t border-gray-200 dark:border-gray-800 space-y-6">
            <div class="flex justify-between items-baseline">
                <h2 class="text-xs font-mono tracking-wider text-gray-400 dark:text-gray-500 uppercase">Publicaciones
                    recientes</h2>
                <a href="{{ route('public.blogs.index') }}"
                    class="text-xs font-mono text-gray-500 dark:text-gray-400 hover:text-gray-950 dark:hover:text-white transition-colors group">
                    Ver todas <span class="inline-block transition-transform group-hover:translate-x-1">→</span>
                </a>
            </div>

            <div class="divide-y divide-gray-200 dark:divide-gray-900/50">
                @forelse($blogs as $blog)
                    <article class="py-4 first:pt-0 last:pb-0 group">
                        <a href="{{ route('public.blogs.show', $blog->blog_slug) }}"
                            class="flex flex-col sm:flex-row sm:items-baseline justify-between gap-2 sm:gap-6">
                            <div class="space-y-1 max-w-xl">
                                <h3
                                    class="text-base font-normal text-gray-950 dark:text-white group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-colors">
                                    {{ $blog->blog_title }}
                                </h3>
                                @if ($blog->blog_description)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                                        {{ $blog->blog_description }}
                                    </p>
                                @endif
                            </div>

                            @if ($blog->blog_date)
                                <time datetime="{{ $blog->blog_date->toW3cString() }}"
                                    class="text-xs font-mono text-gray-400 dark:text-gray-500 whitespace-nowrap pt-1 sm:pt-0">
                                    {{ $blog->blog_date->format('d M, Y') }}
                                </time>
                            @endif
                        </a>
                    </article>
                @empty
                    <p class="text-sm text-gray-400 dark:text-gray-500 italic py-2">No hay publicaciones recientes por
                        el momento.</p>
                @endforelse
            </div>
        </div>
    </section>
</x-app-layout>
