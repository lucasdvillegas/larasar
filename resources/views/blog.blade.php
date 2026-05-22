<x-app-layout :title="'Estudio — ' . $blog->blog_title">
    <article id="article-content" class="space-y-6">
        <div class="space-y-2">
            <time id="post-date" class="text-xs font-mono text-gray-400 dark:text-gray-500">
                {{ \Carbon\Carbon::parse($blog->blog_date)->format('d M Y') }}
            </time>
            <h1 id="post-title" class="text-2xl font-normal tracking-tight text-gray-950 dark:text-white sm:text-3xl">
                {{ $blog->blog_title }}
            </h1>
        </div>

        <div id="post-body" class="text-sm md:text-base text-gray-600 dark:text-gray-400 leading-relaxed space-y-4 pt-4 border-t border-gray-200 dark:border-gray-900">
            {!! nl2br(e($blog->blog_content)) !!}
        </div>

        <div class="pt-12">
            <a href="{{ route('public.blogs.index') }}" class="text-xs font-mono text-gray-400 hover:text-gray-950 dark:hover:text-white transition-colors flex items-center gap-1">
                ← Volver al blog
            </a>
        </div>
    </article>
</x-app-layout>