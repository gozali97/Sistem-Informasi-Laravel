<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DokterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dokter = User::create([
            'username' => 'dokter',
            'first_name' => 'dokter',
            'last_name' => 'dokter',
            'tacces_code' => 000,
            'idlab' => 'H002',
            'email' => 'doktorr@co.id',
            'password' => '$2y$10$Z/kaHGEoJ1CDr4V0J.eFMOBcItfS8U3Jqk.gI00b.wprJT1wSUugu',
        ]);

        $role_dokter = Role::create(['name' => 'Dokter']);

        $dokter->assignRole('Dokter');
    }
}
