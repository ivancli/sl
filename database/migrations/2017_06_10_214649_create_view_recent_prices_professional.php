<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewRecentPricesProfessional extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            CREATE VIEW recent_prices_professional
            AS (
                SELECT historical_prices.* 
                FROM historical_prices 
                JOIN recent_prices_id_professional latest_id 
                ON (historical_prices.id=latest_id.id)
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
        DB::statement('DROP VIEW recent_prices_professional');
    }
}
