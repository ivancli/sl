<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreviousPriceChangesEnterprise extends Migration
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
                JOIN (
                    SELECT hp.item_meta_id, MIN(hp.id) id
                    FROM historical_prices_enterprise hp 
                    JOIN previous_prices_enterprise pp ON(pp.item_meta_id=hp.item_meta_id)
                    WHERE hp.created_at > pp.created_at
                    GROUP BY hp.item_meta_id  
                ) price_changes ON (price_changes.id=historical_prices_enterprise.id)         
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
