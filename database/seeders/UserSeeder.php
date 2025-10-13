<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario administrador
        User::create([
            'name' => 'Hans Higueros',
            'email' => 'admin@avenir-support.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'first_name' => 'Hans',
            'last_name' => 'Higueros',
            'active' => true
        ]);

        // Usuario de soporte técnico
        User::create([
            'name' => 'Técnico Soporte',
            'email' => 'tecnico@avenir-support.com',
            'password' => Hash::make('password123'),
            'role' => 'technician',
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'active' => true
        ]);

        // Usuario cliente de prueba - Empresa ACME
        User::create([
            'name' => 'Carlos Rodriguez',
            'email' => 'crodriguez@acme.com',
            'password' => Hash::make('password123'),
            'role' => 'client',
            'first_name' => 'Carlos',
            'last_name' => 'Rodriguez',
            'company_name' => 'ACME Corp',
            'supervisor_email' => 'supervisor@acme.com',
            'active' => true
        ]);

        // Segundo técnico para pruebas de asignación
        User::create([
            'name' => 'María García',
            'email' => 'mgarcia@avenir-support.com',
            'password' => Hash::make('password123'),
            'role' => 'technician',
            'first_name' => 'María',
            'last_name' => 'García',
            'active' => true
        ]);
    }
}
