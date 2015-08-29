<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSeriesactorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('seriesactors', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('SeriesID')->index('SeriesID');
			$table->string('Name')->nullable();
			$table->string('Role')->nullable();
			$table->smallInteger('SortOrder')->default(3);
			$table->string('Image')->nullable();
			$table->integer('ImageAuthor')->nullable();
			$table->dateTime('ImageAdded')->nullable();
			$table->timestamp('LastUpdate')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->integer('LastUpdateBy');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('seriesactors');
	}

}
