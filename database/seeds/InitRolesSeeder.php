<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class InitRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tier_1 = new Role;
        $tier_1->name = 'tier_1';
        $tier_1->display_name = 'Tier 1';
        $tier_1->description = 'Super administrators';
        $tier_1->save();

        $tier_2 = new Role;
        $tier_2->name = 'tier_2';
        $tier_2->display_name = 'Tier 2';
        $tier_2->description = 'Second level administrators';
        $tier_2->save();

        $tier_3 = new Role;
        $tier_3->name = 'tier_3';
        $tier_3->display_name = 'Tier 3';
        $tier_3->description = 'Third level administrators';
        $tier_3->save();

        $tier_4 = new Role;
        $tier_4->name = 'tier_4';
        $tier_4->display_name = 'Tier 4';
        $tier_4->description = 'Fourth level administrators';
        $tier_4->save();

        $client = new Role;
        $client->name = 'client';
        $client->display_name = 'Client';
        $client->description = 'Paid client users';
        $client->save();

        $unlimited_client = new Role;
        $unlimited_client->name = 'unlimited_client';
        $unlimited_client->display_name = 'Unlimited Client';
        $unlimited_client->description = 'Unpaid client users';
        $unlimited_client->save();
    }
}
