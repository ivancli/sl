<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewPreviousPriceChangesEnterprise extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            CREATE VIEW previous_price_changes_enterprise AS (
                SELECT historical_prices_enterprise.* 
                FROM historical_prices_enterprise
                JOIN previous_price_changes_id_enterprise price_changes ON (price_changes.id=historical_prices_enterprise.id)         
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
        DB::statement('DROP VIEW previous_price_changes_enterprise');
    }
}
