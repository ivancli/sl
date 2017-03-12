<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrawlerConfsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crawler_confs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('crawler_id')->unsigned()->nullable();
            $table->foreign('crawler_id')->references('id')->on('crawlers')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->text('class')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crawler_confs');
    }
}
