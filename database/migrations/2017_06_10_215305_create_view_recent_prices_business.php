<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewRecentPricesBusiness extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            CREATE VIEW recent_prices_business
            AS (
                SELECT historical_prices.* 
                FROM historical_prices 
                JOIN historical_prices_business formatted_prices ON(formatted_prices.id=historical_prices.id)
                JOIN recent_prices_created_at_business latest_created_at 
                ON (latest_created_at.item_meta_id=historical_prices.item_meta_id 
                AND latest_created_at.created_at=historical_prices.created_at)
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
        DB::statement('DROP VIEW recent_prices_business');
    }
}
