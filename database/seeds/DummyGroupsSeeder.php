<?php
use Illuminate\Database\Seeder;

/**
 * Created by PhpStorm.
 * User: ivan.li
 * Date: 2/20/2017
 * Time: 5:21 PM
 */
class DummyGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Group::class, 123)->create();
    }
}