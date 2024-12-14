<?php

namespace Modules\InventoryManagement\Database\Seeders;

use Illuminate\Database\Seeder;

class InventoryManagementDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(PermissionSeeder::class);
        // $this->call([]);
    }
}
