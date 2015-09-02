<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('genders', function (Blueprint $table) {
            $table->char('gender');
            $table->primary('gender');
            $table->string('description');
        });

        DB::table('genders')->insert([
            ['gender' => 'M', 'description' => 'Male'],
            ['gender' => 'F', 'description' => 'Female']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('genders');
    }
}
