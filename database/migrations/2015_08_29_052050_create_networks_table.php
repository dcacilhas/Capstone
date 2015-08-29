<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNetworksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('networks', function(Blueprint $table)
		{
			$table->increments('netID');
			$table->string('Network', 40);
			$table->string('Comment')->nullable();
			$table->string('Wikipedia')->nullable();
			$table->string('Logo', 100)->nullable();
			$table->char('ISO6393', 3)->nullable();
			$table->char('ISO31661', 3)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('networks');
	}

}
