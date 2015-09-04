<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_statuses', function (Blueprint $table) {
            $table->tinyInteger('list_status');
            $table->primary('list_status');
            $table->string('description');
        });

        DB::table('list_statuses')->insert([
            ['list_status' => 0, 'description' => 'Watching'],
            ['list_status' => 1, 'description' => 'Plan To Watch'],
            ['list_status' => 2, 'description' => 'Completed'],
            ['list_status' => 3, 'description' => 'On Hold']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('list_statuses');
    }
}
