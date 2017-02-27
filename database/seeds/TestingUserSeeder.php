<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestingUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminUser = new \App\Models\User([
            'first_name' => "Ivan",
            'last_name' => 'Li',
            'email' => 'ivan.li@hotmail.com',
            'password' => bcrypt('secret'),
        ]);
        $adminUser->save();
        $tier_1 = \App\Models\Role::where('name', 'tier_1')->first();
        $adminUser->attachRole($tier_1);
    }
}
