<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@gmail.com', // El que vayas a usar para Iniciar Sesión
            'password' => Hash::make('admin123'), // Cambia la contraseña por una segura
            'role' => 'admin', // Asegúrate de tener esta columna o manejar roles con Spatie
        ]);
    }
}