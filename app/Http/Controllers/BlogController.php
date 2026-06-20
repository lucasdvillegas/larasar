<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Mews\Purifier\Facades\Purifier;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::select('id', 'blog_title', 'blog_slug', 'blog_description', 'blog_date', 'blog_status');

        if ($request->filled('filter')) {
            $query->where('blog_title', 'like', '%' . $request->filter . '%')
                  ->orWhere('blog_description', 'like', '%' . $request->filter . '%');
        }

        if ($request->filled('status')) {
            $query->where('blog_status', $request->status);
        }

        $sortBy      = $request->get('sortBy', 'id');
        $descending  = filter_var($request->get('descending', true), FILTER_VALIDATE_BOOLEAN) ? 'desc' : 'asc';
        $rowsPerPage = (int) $request->get('rowsPerPage', 10);

        $blogs = $query->orderBy($sortBy, $descending)->paginate($rowsPerPage);

        return Inertia::render('Blogs/Index', [
            'blogs' => $blogs->items(),
            'paginationData' => [
                'total'        => $blogs->total(),
                'per_page'     => $blogs->perPage(),
                'current_page' => $blogs->currentPage(),
            ],
            'filters' => $request->only(['filter', 'sortBy', 'descending', 'status']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Blogs/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'blog_title'       => 'required|string|max:255',
            'blog_description' => 'required|string|max:500',
            'blog_content'     => 'required|string|max:99999',
            'blog_status'      => 'required|in:active,inactive',
        ], [], [
            'blog_title'       => 'título',
            'blog_description' => 'descripción',
            'blog_content'     => 'contenido',
            'blog_status'      => 'estado',
        ]);

        try {
            $validated['blog_content'] = Purifier::clean($validated['blog_content']);
            $validated['blog_date']    = now()->toDateString();

            Blog::create($validated);

            return redirect()->route('admin.blogs.index')->with('success', 'Blog creado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error inesperado al intentar crear el blog.');
        }
    }

    public function edit(Blog $blog)
    {
        return Inertia::render('Blogs/Update', [
            'blog' => $blog,
        ]);
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'blog_title'       => 'required|string|max:255',
            'blog_description' => 'required|string|max:500',
            'blog_content'     => 'required|string|max:99999',
            'blog_status'      => 'required|in:active,inactive',
        ], [], [
            'blog_title'       => 'título',
            'blog_description' => 'descripción',
            'blog_content'     => 'contenido',
            'blog_status'      => 'estado',
        ]);

        try {
            $validated['blog_content'] = Purifier::clean($validated['blog_content']);
            $validated['blog_date']    = now()->toDateString();

            $blog->update($validated);

            return redirect()->route('admin.blogs.index')->with('success', 'Blog actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error inesperado al intentar actualizar el blog.');
        }
    }

    public function destroy(Blog $blog)
    {
        try {
            $blog->delete();

            return redirect()->route('admin.blogs.index')->with('success', 'Blog eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error inesperado al intentar eliminar el blog.');
        }
    }
}