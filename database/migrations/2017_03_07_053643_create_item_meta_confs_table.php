<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemMetaConfsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_meta_confs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_meta_id')->unsigned()->nullable();
            $table->foreign('item_meta_id')->references('id')->on('item_metas')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->text('element');
            $table->text('value');
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
        Schema::dropIfExists('item_meta_confs');
    }
}
