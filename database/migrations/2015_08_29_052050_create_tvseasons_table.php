<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTvseasonsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tvseasons', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('seriesid')->unsigned()->index('seriesid');
			$table->integer('season')->unsigned();
			$table->integer('bannerrequest')->unsigned()->nullable()->default(0);
			$table->string('locked', 3)->default('no');
			$table->timestamp('mirrorupdate')->default(DB::raw('CURRENT_TIMESTAMP'))->index('mirrorupdate');
			$table->integer('lockedby');
			$table->boolean('tms_wanted')->default(0);
			$table->unique(['seriesid','season'], 'uniqueseason');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tvseasons');
	}

}
