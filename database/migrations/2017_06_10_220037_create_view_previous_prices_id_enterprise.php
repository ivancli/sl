<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewPreviousPricesIdEnterprise extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            CREATE VIEW previous_prices_id_enterprise AS 
            (
                select 
                    `historical_prices`.`item_meta_id` AS `item_meta_id`,
                    max(`historical_prices`.`id`) AS `id` 
                from 
                    `historical_prices` 
                    join `recent_prices_enterprise` `recent_historical_prices` 
                    on
                    (
                        `historical_prices`.`item_meta_id` = `recent_historical_prices`.`item_meta_id`
                    )
                where `historical_prices`.`amount` <> `recent_historical_prices`.`amount` 
                group by `historical_prices`.`item_meta_id`
            )
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('
            DROP VIEW previous_prices_id_enterprise
        ');
    }
}
