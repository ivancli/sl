<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewFailedCrawlSites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('
            CREATE VIEW failed_crawl_sites AS (
                SELECT sites.*
                FROM sites
                JOIN urls ON(sites.url_id=urls.id)
                WHERE urls.status="crawl_failed"
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
            DROP VIEW failed_crawl_sites
        ');
    }
}
