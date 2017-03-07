<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AppPrefSeeder::class);
        $this->call(InitRolesSeeder::class);
        $this->call(InitPermissionsSeeder::class);
        $this->call(TestingUserSeeder::class);
    }
}
