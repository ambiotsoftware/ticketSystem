<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'nombre' => 'Soporte Técnico',
                'descripcion' => 'Problemas técnicos y configuraciones',
                'color' => '#e74c3c',
                'activa' => true
            ],
            [
                'nombre' => 'Bug Report',
                'descripcion' => 'Reportes de errores en el software',
                'color' => '#f39c12',
                'activa' => true
            ],
            [
                'nombre' => 'Feature Request',
                'descripcion' => 'Solicitudes de nuevas funcionalidades',
                'color' => '#27ae60',
                'activa' => true
            ],
            [
                'nombre' => 'Consulta General',
                'descripcion' => 'Preguntas y consultas generales',
                'color' => '#3498db',
                'activa' => true
            ],
            [
                'nombre' => 'Mantenimiento',
                'descripcion' => 'Tareas de mantenimiento del sistema',
                'color' => '#9b59b6',
                'activa' => true
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
