<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class PublicBlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::query()->where('blog_status', 'active');

        if ($request->filled('filter')) {
            $query->where(function($q) use ($request) {
                $q->where('blog_title', 'like', '%' . $request->filter . '%')
                  ->orWhere('blog_description', 'like', '%' . $request->filter . '%');
            });
        }

        $blogs = $query->orderBy('blog_date', 'desc')->paginate(10);

        return view('blogs', [
            'blogs' => $blogs
        ]);
    }

    public function show($slug)
    {
        $blog = Blog::where('blog_slug', $slug)
                    ->where('blog_status', 'active')
                    ->firstOrFail();

        return view('blog', [
            'blog' => $blog
        ]);
    }
}