<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewPreviousPricesEnterprise extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            CREATE VIEW previous_prices_enterprise AS 
            (
                select
                    historical_prices.id AS id,
                    historical_prices.item_meta_id AS item_meta_id,
                    historical_prices.amount AS amount,
                    historical_prices.created_at AS created_at,
                    historical_prices.updated_at AS updated_at
                from
                    historical_prices
                join
                    previous_prices_id_enterprise previous_id
                on
                (
                    previous_id.id = historical_prices.id
                )
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
            DROP VIEW previous_prices_enterprise
        ');
    }
}
