<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewCheapestSites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            CREATE VIEW cheapest_sites AS (
                SELECT sites.*
                FROM sites
                JOIN items ON(sites.item_id=items.id)
                JOIN item_metas ON(items.id=item_metas.item_id AND item_metas.element=\'PRICE\')
                JOIN
                (
                    SELECT product_id, MIN(CAST(item_metas.value AS DECIMAL(10, 4))) price
                    FROM sites 
                    JOIN items ON(sites.item_id=items.id)
                    JOIN item_metas ON(items.id=item_metas.item_id AND item_metas.element=\'PRICE\')
                    GROUP BY sites.product_id
                ) cheapest_prices ON(sites.product_id=cheapest_prices.product_id AND item_metas.value=cheapest_prices.price)
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
            DROP VIEW cheapest_sites
        ');
    }
}
