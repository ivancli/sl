<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewRecentPricesIdEnterprise extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            CREATE VIEW recent_prices_id_enterprise
            AS (
                SELECT MAX(historical_prices.id) id
                FROM historical_prices_enterprise historical_prices
                GROUP BY historical_prices.item_meta_id
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
        DB::statement('DROP VIEW recent_prices_id_enterprise');
    }
}
