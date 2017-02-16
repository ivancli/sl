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
        DB::table('users')->insert([
            'first_name' => "Ivan",
            'last_name' => 'Li',
            'email' => 'ivan.li@hotmail.com',
            'password' => '$2y$10$Ov57y/62a7T8CXGJ.mkQMOCDtOUjtKNRwHeShNkZ/ECBaIJJKIvyO',
        ]);

        DB::table('subscriptions')->insert([
            'user_id' => 1,
            'api_subscription_id' => '16298788',
        ]);

        DB::table('user_preferences')->insert([
            'user_id' => 1,
            'element' => 'DATE_FORMAT',
            'value' => 'Y-m-d',
        ]);

        DB::table('user_preferences')->insert([
            'user_id' => 1,
            'element' => 'TIME_FORMAT',
            'value' => 'g:ia',
        ]);
    }
}
