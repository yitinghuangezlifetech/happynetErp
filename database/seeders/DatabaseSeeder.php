<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        $this->call(MenuSeeder::class);
        $this->call(MenuDetailSeeder::class);
        // $this->call(GroupSeeder::class);
        // $this->call(RoleSeeder::class);
        // $this->call(IdentitySeeder::class);
        // $this->call(OrganizationTypeSeeder::class);
        // $this->call(OrganizationSeeder::class);
        // $this->call(UserTypeSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(GroupPermissionSeeder::class);
        $this->call(RolePermissionSeeder::class);
        $this->call(UserSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
