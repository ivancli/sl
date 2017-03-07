<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDomainMetaConfsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domain_meta_confs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('domain_meta_id')->unsigned()->nullable();
            $table->foreign('domain_meta_id')->references('id')->on('domain_metas')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->text('element');
            $table->text('value');
            $table->integer('order');
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
        Schema::dropIfExists('domain_meta_confs');
    }
}
