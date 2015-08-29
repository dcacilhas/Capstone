<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAkaSeriesnameTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('aka_seriesname', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('seriesid')->index('seriesid');
			$table->string('name')->index('name');
			$table->integer('languageid')->index('languageid');
			$table->boolean('search');
			$table->integer('lasteditby');
			$table->dateTime('lastedit');
			$table->unique(['seriesid','name','languageid'], 'seriesid_name_languageid');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('aka_seriesname');
	}

}
