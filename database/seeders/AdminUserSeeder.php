<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed the admin user with the 'Administrador' role.
     *
     * @return void
     */
    public function run()
    {
        // Ensure the Administrador role exists before assigning it
        $role = Role::firstOrCreate(['name' => 'Administrador']);

        // Create the admin user only if the email is not already taken
        $user = User::firstOrCreate(
            ['email' => 'profe@gmail.com'],
            [
                'name'     => 'Administrador',
                'password' => Hash::make('profe1234'),
            ]
        );

        // Assign the role (idempotent — safe to call even if already assigned)
        $user->assignRole($role);
    }
}
