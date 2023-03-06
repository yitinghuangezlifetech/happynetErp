<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(IdentitySeeder::class);
        $this->call(UserTypeSeeder::class);
        $this->call(ServiceTypeSeeder::class);
        $this->call(OrganizationTypeSeeder::class);
    }
}
