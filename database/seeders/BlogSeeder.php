<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $blogs = [
            [
                'title' => 'Introducción a Vue 3 y la Composition API',
                'description' => 'Un repaso profundo sobre cómo estructurar tus componentes utilizando ref, reactive y hooks de ciclo de vida.',
                'content' => 'Acá iría todo el contenido extenso del post sobre Vue 3...',
            ],
            [
                'title' => 'Optimización de Consultas con Eloquent en Laravel',
                'description' => 'Aprendé a evitar el problema de las N+1 consultas usando eager loading con selectores específicos.',
                'content' => 'Acá iría todo el contenido extenso del post sobre Eloquent...',
            ],
            [
                'title' => 'Cómo integrar Quasar Framework en proyectos existentes',
                'description' => 'Guía paso a paso para montar componentes de Quasar como QTable y QSelect de forma limpia.',
                'content' => 'Acá iría todo el contenido extenso del post sobre Quasar...',
            ],
            [
                'title' => 'Patrones de Arquitectura Modular en Monolitos',
                'description' => 'Por qué separar tu lógica por módulos dentro de Laravel puede salvar la mantenibilidad de tu software.',
                'content' => 'Acá iría todo el contenido extenso del post sobre módulos...',
            ],
            [
                'title' => 'Manejo Eficiente de Grandes Volúmenes de Datos en Tablas',
                'description' => 'Estrategias del lado del servidor para paginar y filtrar registros enviando únicamente lo necesario.',
                'content' => 'Acá iría todo el contenido extenso del post sobre tablas...',
            ],
        ];

        foreach ($blogs as $blog) {
            DB::table('blogs')->insert([
                'blog_title'       => $blog['title'],
                'blog_slug'        => Str::slug($blog['title']),
                'blog_description' => $blog['description'],
                'blog_content'     => $blog['content'],
                'blog_date'        => now()->subDays(rand(1, 30))->format('Y-m-d'),
                'blog_status'      => 'active',
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }
    }
}