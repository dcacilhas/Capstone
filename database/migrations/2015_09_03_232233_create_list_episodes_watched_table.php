<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListEpisodesWatchedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_episodes_watched', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('episode_id')->unsigned();
            $table->foreign('episode_id')->references('id')->on('tvepisodes');
            $table->integer('list_id')->unsigned();
            $table->foreign('list_id')->references('id')->on('list');
            $table->timestamps();
            $table->unique(['list_id', 'episode_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('list_episodes_watched');
    }
}
