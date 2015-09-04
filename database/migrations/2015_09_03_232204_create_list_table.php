<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('series_id')->unsigned();
            $table->foreign('series_id')->references('id')->on('tvseries');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->tinyInteger('list_status')->default(0);
            $table->foreign('list_status')->references('list_status')->on('list_statuses');
            $table->tinyInteger('rating')->nullable();
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
        Schema::drop('list');
    }
}
