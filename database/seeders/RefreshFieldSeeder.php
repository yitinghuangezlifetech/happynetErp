<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RefreshFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        $this->call(MenuSeeder::class);
        $this->call(MenuDetailSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(GroupPermissionSeeder::class);
        $this->call(RolePermissionSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
