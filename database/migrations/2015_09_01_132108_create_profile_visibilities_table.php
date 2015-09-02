<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProfileVisibilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_visibilities', function (Blueprint $table) {
            $table->tinyInteger('profile_visibility');
            $table->primary('profile_visibility');
            $table->string('description');
        });

        DB::table('profile_visibilities')->insert([
            ['profile_visibility' => 0, 'description' => 'Public'],
            ['profile_visibility' => 1, 'description' => 'Private'],
            ['profile_visibility' => 2, 'description' => 'Friends Only']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('profile_visibilities');
    }
}
