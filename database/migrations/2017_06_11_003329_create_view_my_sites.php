<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewMySites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            CREATE VIEW my_sites AS (
                SELECT sites.*
                FROM sites
                JOIN
                (
                    SELECT sites.product_id, MIN(sites.id) id
                    FROM sites 
                    JOIN products ON(products.id=sites.product_id)
                    JOIN users ON(users.id=products.user_id)
                    JOIN items ON(sites.item_id=items.id)
                    LEFT JOIN item_metas ebay ON(items.id=ebay.item_id AND ebay.element="SELLER_USERNAME")
                    JOIN urls ON(sites.url_id=urls.id)
                    JOIN user_metas ON(users.id=user_metas.user_id)
                    WHERE 
                    (ebay.value IS NOT NULL AND user_metas.ebay_username IS NOT NULL AND ebay.value=user_metas.ebay_username)
                    OR 
                    (user_metas.company_url IS NOT NULL AND urls.full_path LIKE CONCAT("%", user_metas.company_url, "%"))
                    GROUP BY sites.product_id
                ) filtered_sites ON(sites.id=filtered_sites.id)
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
        DB::statement('DROP VIEW my_sites');
    }
}
