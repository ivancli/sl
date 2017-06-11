<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewPreviousPricesProfessional extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
        CREATE VIEW previous_prices_professional AS 
        (
        select
            historical_prices.id AS id,
            historical_prices.item_meta_id AS item_meta_id,
            historical_prices.amount AS amount,
            historical_prices.created_at AS created_at,
            historical_prices.updated_at AS updated_at
            from
            (
              historical_prices
              join
              (
                select
                  historical_prices.item_meta_id AS item_meta_id,
                  max(historical_prices.created_at) AS created_at
                from
                (
                  historical_prices
                  join recent_prices_professional recent_historical_prices
                  on
                  (
                    (
                      historical_prices.item_meta_id = recent_historical_prices.item_meta_id
                    )
                  )
                )
                where
                (
                  historical_prices.amount <> recent_historical_prices.amount
                )
                group by historical_prices.item_meta_id
              )
              previous_created_at
              on
              (
                (
                  (
                    historical_prices.item_meta_id = previous_created_at.item_meta_id
                  )
                  and
                  (
                    previous_created_at.created_at = historical_prices.created_at
                  )
                )
              )
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
            DROP VIEW previous_prices_professional
        ');
    }
}
