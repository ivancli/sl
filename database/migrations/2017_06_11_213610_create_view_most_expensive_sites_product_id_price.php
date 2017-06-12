<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewMostExpensiveSitesProductIdPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            CREATE VIEW most_expensive_sites_product_id_price AS (
                SELECT product_id, MAX(CAST(item_metas.value AS DECIMAL(10, 4))) price
                FROM sites 
                JOIN items ON(sites.item_id=items.id)
                JOIN item_metas ON(items.id=item_metas.item_id AND item_metas.element="PRICE")
                GROUP BY sites.product_id
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
            DROP VIEW most_expensive_sites_product_id_price
        ');
    }
}
