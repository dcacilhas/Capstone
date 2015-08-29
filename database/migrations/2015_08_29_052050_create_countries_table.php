<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCountriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('countries', function(Blueprint $table)
		{
			$table->integer('countryid', true);
			$table->char('iso2', 2)->nullable();
			$table->string('shortname', 80)->default('');
			$table->string('longname', 80)->default('');
			$table->char('iso3', 3)->nullable();
			$table->string('numcode', 6)->nullable();
			$table->string('un_member', 12)->nullable();
			$table->string('callingcode', 8)->nullable();
			$table->string('tld', 5)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('countries');
	}

}
