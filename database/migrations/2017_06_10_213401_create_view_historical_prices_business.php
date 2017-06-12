<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewHistoricalPricesBusiness extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            CREATE VIEW historical_prices_business
            AS (
                SELECT historical_prices.* 
                FROM historical_prices 
                JOIN historical_prices_id_business formatted_prices ON(formatted_prices.id=historical_prices.id)
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
            DROP VIEW historical_prices_business
        ');
    }
}
