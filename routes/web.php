<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PublicBlogController;
use App\Http\Controllers\DashboardController;
use App\Models\Blog;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

// HOME
Route::get('/', function () {
    // Consultamos los últimos 2 blogs subidos
    $blogs = Blog::where('blog_status', 'active')
        ->latest('blog_date')
        ->take(2)
        ->get();

    return view('home', [
        'blogs' => $blogs,
        'canLogin' => Route::has('login'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

// RUTAS PÚBLICAS
Route::name('public.blogs.')->group(function () {
    Route::get('/blogs', [PublicBlogController::class, 'index'])->name('index');
    Route::get('/blog/{blog_slug}', [PublicBlogController::class, 'show'])->name('show');
});

// RUTAS BAJO AUTENTICACIÓN
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('blogs', BlogController::class)->except(['show']);
    });
});

require __DIR__.'/auth.php';
