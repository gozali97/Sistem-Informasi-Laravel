<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRolePermissonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $administrator = User::create([
            'username' => 'administrator',
            'first_name' => 'administrator',
            'last_name' => 'administrator',
            'tacces_code' => 000,
            'idlab' => 'H002',
            'email' => 'administrator@co.id',
            'password' => '$2y$10$Z/kaHGEoJ1CDr4V0J.eFMOBcItfS8U3Jqk.gI00b.wprJT1wSUugu',
        ]);
        $admin = User::create([
            'username' => 'admin',
            'first_name' => 'admin',
            'last_name' => 'admin',
            'tacces_code' => 000,
            'idlab' => 'H002',
            'email' => 'admin@co.id',
            'password' => '$2y$10$Z/kaHGEoJ1CDr4V0J.eFMOBcItfS8U3Jqk.gI00b.wprJT1wSUugu',
        ]);
        $kasir = User::create([
            'username' => 'ahmad',
            'first_name' => 'ahmad',
            'last_name' => 'ahmad',
            'tacces_code' => 000,
            'idlab' => 'H002',
            'email' => 'ahmad@gmail.com',
            'password' => '$2y$10$Z/kaHGEoJ1CDr4V0J.eFMOBcItfS8U3Jqk.gI00b.wprJT1wSUugu',
        ]);

        $role_administrator = Role::create(['name' => 'Administrator']);
        $role_admin = Role::create(['name' => 'Admin']);
        $role_kasir = Role::create(['name' => 'Kasir']);

        $permission = Permission::create(['name' => 'read pendaftaran']);
        $permission = Permission::create(['name' => 'read laboratorium']);
        $permission = Permission::create(['name' => 'read kasir']);
        $permission = Permission::create(['name' => 'read master']);
        $permission = Permission::create(['name' => 'read konfigurasi']);
        $permission = Permission::create(['name' => 'read konfigurasi/roles']);
        $permission = Permission::create(['name' => 'read konfigurasi/permission']);
        $permission = Permission::create(['name' => 'read konfigurasi/navigasi']);
        $permission = Permission::create(['name' => 'read pendaftaran/pasien']);
        $permission = Permission::create(['name' => 'read pendaftaran/lab']);
        $permission = Permission::create(['name' => 'read pendaftaran/informasi']);

        $role_administrator->givePermissionTo('read pendaftaran');
        $role_administrator->givePermissionTo('read laboratorium');
        $role_administrator->givePermissionTo('read kasir');
        $role_administrator->givePermissionTo('read master');
        $role_administrator->givePermissionTo('read konfigurasi');
        $role_administrator->givePermissionTo('read konfigurasi/roles');
        $role_administrator->givePermissionTo('read konfigurasi/permission');
        $role_administrator->givePermissionTo('read konfigurasi/navigasi');
        $role_administrator->givePermissionTo('read pendaftaran/pasien');
        $role_administrator->givePermissionTo('read pendaftaran/lab');
        $role_administrator->givePermissionTo('read pendaftaran/informasi');

        $administrator->assignRole('Administrator');
        $admin->assignRole('Admin');
        $kasir->assignRole('Kasir');
    }
}
