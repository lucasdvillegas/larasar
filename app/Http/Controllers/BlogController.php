<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::query();

        if ($request->filled('filter')) {
            $query->where('blog_title', 'like', '%' . $request->filter . '%')
                  ->orWhere('blog_description', 'like', '%' . $request->filter . '%');
        }

        $sortBy = $request->get('sortBy', 'id');
        $descending = $request->get('descending', 'true') === 'true' ? 'desc' : 'asc';
        $rowsPerPage = $request->get('rowsPerPage', 10);

        $blogs = $query->orderBy($sortBy, $descending)->paginate($rowsPerPage);

        return Inertia::render('Blogs/Index', [
            'blogs' => $blogs->items(),
            'paginationData' => [
                'total' => $blogs->total(),
                'per_page' => $blogs->perPage(),
                'current_page' => $blogs->currentPage(),
            ],
            'filters' => $request->only(['filter', 'sortBy', 'descending'])
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
            'blog_description' => 'required|string',
            'blog_content'     => 'required|string',
            'blog_status'      => 'required|in:active,inactive',
        ]);

        $validated['blog_date'] = now()->toDateString();

        Blog::create($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog creado correctamente.');
    }

    public function edit(Blog $blog)
    {
        return Inertia::render('Blogs/Update', [
            'blog' => $blog
        ]);
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'blog_title'       => 'required|string|max:255',
            'blog_description' => 'required|string',
            'blog_content'     => 'required|string',
            'blog_status'      => 'required|in:active,inactive',
        ]);

        $validated['blog_date'] = now()->toDateString();

        $blog->update($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog actualizado correctamente.');
    }

    public function show(Blog $blog)
    {   
        return view('blog', [
            'blog' => $blog
        ]);
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog eliminado correctamente.');
    }
}