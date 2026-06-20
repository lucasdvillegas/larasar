<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Item principal: Dashboard
        Menu::create([
            'label' => 'Dashboard',
            'icon' => 'dashboard',
            'route_name' => 'dashboard',
            'route_pattern' => 'dashboard',
            'order' => 1,
        ]);

        // Item padre desplegable: Blogs
        $blogsMenu = Menu::create([
            'label' => 'Blogs',
            'icon' => 'newspaper',
            'route_name' => null,
            'route_pattern' => 'admin.blogs.*',
            'order' => 2,
        ]);

        // Submenús de Blogs
        Menu::create([
            'parent_id' => $blogsMenu->id,
            'label' => 'Listado',
            'icon' => 'list',
            'route_name' => 'admin.blogs.index',
            'route_pattern' => 'admin.blogs.index',
            'order' => 1,
        ]);

        Menu::create([
            'parent_id' => $blogsMenu->id,
            'label' => 'Crear blog',
            'icon' => 'add_circle',
            'route_name' => 'admin.blogs.create',
            'route_pattern' => 'admin.blogs.create',
            'order' => 2,
        ]);
    }
}