<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PublicBlogController;
use App\Models\Blog;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    // Consultamos los últimos 2 blogs subidos
    $blogs = Blog::where('blog_status', 'active')
        ->latest('blog_date')
        ->take(2)
        ->get();

    return view('home', [
        'blogs' => $blogs,
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

// RUTAS PÚBLICAS
Route::get('/blogs', [PublicBlogController::class, 'index'])->name('public.blogs.index');
Route::get('/blog/{blog_slug}', [PublicBlogController::class, 'show'])->name('public.blogs.show');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Agregado el prefijo 'admin' para las rutas de administración de blogs
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('blogs', BlogController::class);
    });
});

require __DIR__.'/auth.php';
