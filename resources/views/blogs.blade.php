<x-app-layout title="Estudio — Artículos">
    <section class="space-y-10">
        <h2 class="text-xl font-medium text-gray-950 dark:text-white mb-8">Artículos</h2>

        <div class="space-y-8">
            @forelse($blogs as $blog)
                <a href="{{ route('public.blogs.show', $blog->blog_slug) }}" class="block group cursor-pointer {{ !$loop->first ? 'pt-6 border-t border-gray-200 dark:border-gray-800' : '' }}">
                    <article>
                        <time class="text-xs font-mono text-gray-400 dark:text-gray-500 block mb-1">
                            {{ \Carbon\Carbon::parse($blog->blog_date)->format('d M Y') }}
                        </time>
                        <h3 class="text-base font-medium text-gray-950 dark:text-white group-hover:underline decoration-1 underline-offset-4">
                            {{ $blog->blog_title }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 leading-relaxed">
                            {{ $blog->blog_description }}
                        </p>
                    </article>
                </a>
            @empty
                <p class="text-sm text-gray-500">No hay artículos publicados por el momento.</p>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $blogs->links() }}
        </div>
    </section>
</x-app-layout>