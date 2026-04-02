<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permiso
        Permission::firstOrCreate(['name' => 'acceso-admin-dashboard']);

        // Crear roles
        $admin = Role::firstOrCreate(['name' => 'Administrador']);
        $user  = Role::firstOrCreate(['name' => 'Usuario Básico']);

        // Asignar permiso solo al admin
        $admin->givePermissionTo('acceso-admin-dashboard');
    }
}
