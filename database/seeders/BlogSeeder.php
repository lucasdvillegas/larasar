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
                'blog_title' => 'Introducción a Vue 3 y la Composition API',
                'blog_description' => 'Un repaso profundo sobre cómo estructurar tus componentes utilizando ref, reactive y hooks de ciclo de vida.',
                'blog_content' => "## La evolución de la arquitectura en Vue.js\n\nLa llegada de Vue 3 marcó un antes y un después en cómo construimos interfaces de usuario. La introducción de la Composition API no fue solo una mejora sintáctica, sino una respuesta necesaria a la creciente complejidad de las aplicaciones modernas. A medida que los componentes crecían, la antigua Options API sufría del problema de la 'lógica fragmentada', donde una sola funcionalidad quedaba dispersa entre data, methods, computed y watchers.\n\n### El fin del espagueti de opciones\n\nCon la Composition API, ahora tenemos la capacidad de agrupar toda la lógica relacionada con una característica específica en un solo lugar. Esto no solo mejora la legibilidad, sino que hace que nuestro código sea mucho más fácil de testear y mantener. La capacidad de encapsular estados en 'composables' permite que la lógica sea independiente del ciclo de vida del componente, facilitando su reutilización en otros archivos o incluso en otros proyectos.\n\n### ¿Cuándo usar cada herramienta?\n\nLa elección entre `ref` y `reactive` suele generar dudas, pero la regla general es clara: `ref` es nuestra herramienta principal para tipos de datos primitivos (strings, booleans, numbers) y también es sumamente útil para objetos si queremos mantener la referencia completa. `reactive`, por otro lado, se utiliza para objetos complejos donde queremos una reactividad profunda sin necesidad de acceder a la propiedad `.value`. Dominar estas dos herramientas es el primer paso para escribir componentes eficientes y predecibles en el ecosistema de Vue 3.",
            ],
            [
                'blog_title' => 'Optimización de Consultas con Eloquent en Laravel',
                'blog_description' => 'Aprendé a evitar el problema de las N+1 consultas usando eager loading con selectores específicos.',
                'blog_content' => "## Dominando Eloquent para bases de datos de alto rendimiento\n\nLaravel Eloquent es una de las ORM más potentes del mercado, pero su facilidad de uso puede llevar a trampas de rendimiento si no somos cuidadosos. El error más común y crítico es el problema de las consultas N+1. Esto ocurre cuando cargamos una colección de modelos y, al iterar sobre ella, realizamos una consulta adicional por cada registro para obtener información relacionada, como el autor de un post o los comentarios asociados.\n\n### Estrategias de Eager Loading\n\nLa solución fundamental es el 'Eager Loading' mediante el método `with()`. Al cargar las relaciones de forma anticipada, reducimos cientos de consultas individuales a solo dos o tres consultas optimizadas por Eloquent. Sin embargo, no basta con cargar todo. Si solo necesitamos el nombre del autor, usar `with('author:id,name')` es vital para evitar el `SELECT *` y reducir drásticamente el consumo de memoria en el servidor.\n\n### Más allá del with()\n\nAdemás del eager loading, existen otras técnicas clave para el rendimiento: usar `chunk()` cuando procesamos miles de registros para no agotar la RAM del servidor, implementar `cursor()` para grandes datasets, y utilizar `select()` para traer exclusivamente las columnas necesarias. La optimización de consultas no es una tarea de una sola vez, sino una mentalidad continua que asegura que tu aplicación sea rápida incluso cuando la base de datos crece a millones de registros.",
            ],
            [
                'blog_title' => 'Cómo integrar Quasar Framework en proyectos existentes',
                'blog_description' => 'Guía paso a paso para montar componentes de Quasar como QTable y QSelect de forma limpia.',
                'blog_content' => "## Quasar: Potencia visual en proyectos existentes\n\nMuchos desarrolladores piensan que Quasar solo sirve para crear proyectos desde cero, pero su versatilidad permite integrarlo en aplicaciones Laravel o Vite ya existentes con gran facilidad. La ventaja principal de Quasar radica en su conjunto de componentes UI de alto rendimiento, que ya están probados, validados y listos para producción.\n\n### Integración modular\n\nEl secreto para integrar Quasar es hacerlo de forma progresiva. No necesitas migrar toda tu UI de la noche a la mañana. Puedes comenzar instalando las dependencias y registrando los componentes que realmente necesitas, como `QTable` para listados complejos o `QSelect` para formularios avanzados. Esto mantiene tu `bundle size` bajo control y evita conflictos con el estilo CSS que ya tengas definido.\n\n### Optimizando la experiencia del usuario\n\nAl integrar `QTable`, obtienes de forma gratuita funciones como paginación del lado del servidor, filtrado, ordenamiento y selección de filas, características que normalmente requerirían días de desarrollo manual. La clave para una integración limpia es separar tu lógica de negocio del componente visual; deja que Quasar maneje la interfaz y mantén tu lógica de datos gestionada por tus composables o stores de Pinia. Esta separación de responsabilidades asegura que tu proyecto sea fácil de escalar.",
            ],
        ];

        foreach ($blogs as $blog) {
            DB::table('blogs')->insert([
                'blog_title'       => $blog['blog_title'],
                'blog_slug'        => Str::slug($blog['blog_title']),
                'blog_description' => $blog['blog_description'],
                'blog_content'     => $blog['blog_content'],
                'blog_date'        => now()->subDays(rand(1, 30))->format('Y-m-d'),
                'blog_status'      => 'active',
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }
    }
}