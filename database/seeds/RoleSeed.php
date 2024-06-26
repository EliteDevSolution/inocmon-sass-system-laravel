<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'master']);
        $role->givePermissionTo('master_manage');

        $role = Role::create(['name' => 'administrator']);
        $role->givePermissionTo('users_manage');
        
        $role = Role::create(['name' => 'guest']);
        $role->givePermissionTo('guest_manage');
    }
}
