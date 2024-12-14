<?php

namespace Modules\InventoryManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'view products',
            'edit products',
            'delete products',
            'create products',
        ];
        $role = Role::create(['name' => 'Inventory']);

        foreach ($permissions as $permission){
            $permissionOpject = Permission::create(['name' => $permission]);
            $role->givePermissionTo($permissionOpject);
        }
    }
}
