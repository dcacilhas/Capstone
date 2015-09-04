<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_types', function (Blueprint $table) {
            $table->tinyInteger('notification_type');
            $table->primary('notification_type');
            $table->string('description');
        });

        DB::table('notification_types')->insert([
            ['notification_type' => 0, 'description' => 'Friend request']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('notification_types');
    }
}
