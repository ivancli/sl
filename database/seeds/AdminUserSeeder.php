<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
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

        $auSampleUser = new \App\Models\User([
            'first_name' => "AU",
            'last_name' => 'Sample',
            'email' => 'admin@spotlite.com.au',
            'password' => bcrypt('S0lutions'),
            'set_password' => 'y',
            'set_samples' => 'y',
            'set_conversion' => 'y',
        ]);
        $auSampleUser->save();
        $auSampleUser->attachRole($tier_1);

        $usSampleUser = new \App\Models\User([
            'first_name' => "US",
            'last_name' => 'Sample',
            'email' => 'us@spotlite.com.au',
            'password' => bcrypt('S0lutions'),
            'set_password' => 'y',
            'set_samples' => 'y',
            'set_conversion' => 'y',
        ]);
        $usSampleUser->save();
        $usSampleUser->attachRole($tier_1);


    }
}
