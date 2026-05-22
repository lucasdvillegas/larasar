<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_title',
        'blog_slug',
        'blog_description',
        'blog_content',
        'blog_date',
        'blog_status',
    ];

    protected $casts = [
        'blog_date' => 'date',
    ];

    /**
     * Indica a Laravel que busque por slug en lugar de ID en las rutas con Binding automático.
     */
    public function getRouteKeyName(): string
    {
        return 'blog_slug';
    }

    /**
     * Eventos del modelo para automatizar el slug basándose en la descripción.
     */
    protected static function booted()
    {
        static::saving(function ($blog) {
            if ($blog->isDirty('blog_title')) {
                // Genera el slug base
                $slug = Str::slug($blog->blog_title);
                $originalSlug = $slug;
                $count = 1;

                // Evita colisiones si dos descripciones generan el mismo slug corto
                while (static::where('blog_slug', $slug)->where('id', '!=', $blog->blog_slug)->exists()) {
                    $slug = "{$originalSlug}-{$count}";
                    $count++;
                }

                $blog->blog_slug = $slug;
            }
        });
    }
}