<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateListVisibilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_visibilities', function (Blueprint $table) {
            $table->tinyInteger('list_visibility');
            $table->primary('list_visibility');
            $table->string('description');
        });

        DB::table('list_visibilities')->insert([
            ['list_visibility' => 0, 'description' => 'Public'],
            ['list_visibility' => 1, 'description' => 'Private'],
            ['list_visibility' => 2, 'description' => 'Friends Only']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('list_visibilities');
    }
}
