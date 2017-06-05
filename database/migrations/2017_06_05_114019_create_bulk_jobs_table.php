<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBulkJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulk_jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->string('file_name')->nullable();
            $table->integer('chunks')->nullable();
            $table->integer('completed')->nullable();
            $table->char('archived')->default('n');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `bulk_jobs` ADD `content` LONGBLOB AFTER `type`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bulk_jobs');
    }
}