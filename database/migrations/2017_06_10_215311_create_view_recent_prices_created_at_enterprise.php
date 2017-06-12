<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewRecentPricesCreatedAtEnterprise extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            CREATE VIEW recent_prices_created_at_enterprise
            AS (
                SELECT historical_prices.item_meta_id, MAX(historical_prices.created_at) created_at
                FROM historical_prices 
                JOIN historical_prices_enterprise formatted_prices ON(formatted_prices.id=historical_prices.id)
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
        DB::statement('DROP VIEW recent_prices_created_at_enterprise');
    }
}
