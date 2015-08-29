<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTvseriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tvseries', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('SeriesName')->index('SeriesName');
			$table->string('SeriesID', 45)->nullable()->unique('SeriesID');
			$table->string('Status', 100)->nullable();
			$table->string('FirstAired', 100)->nullable();
			$table->string('Network', 100)->nullable()->index('Network');
			$table->integer('NetworkID')->unsigned()->nullable();
			$table->string('Runtime', 100)->nullable();
			$table->string('Genre', 100)->nullable();
			$table->text('Actors', 65535)->nullable();
			$table->text('Overview', 65535)->nullable();
			$table->integer('bannerrequest')->unsigned()->nullable()->default(0);
			$table->integer('lastupdated')->unsigned()->nullable()->index('lastupdated');
			$table->string('Airs_DayOfWeek', 45)->nullable();
			$table->string('Airs_Time', 45)->nullable();
			$table->string('Rating', 45)->nullable();
			$table->integer('flagged')->unsigned()->nullable()->default(0);
			$table->integer('forceupdate')->unsigned()->nullable()->default(0);
			$table->integer('hits')->unsigned()->nullable()->default(0);
			$table->integer('updateID')->default(0);
			$table->string('requestcomment')->default('');
			$table->string('locked', 3)->default('no');
			$table->timestamp('mirrorupdate')->default(DB::raw('CURRENT_TIMESTAMP'))->index('mirrorupdate');
			$table->integer('lockedby');
			$table->string('autoimport', 16)->nullable();
			$table->string('disabled', 3)->default('No')->index('disabled');
			$table->string('IMDB_ID', 25)->nullable()->unique('IMDB_ID');
			$table->string('zap2it_id', 12)->nullable()->unique('zap2it_id');
			$table->dateTime('added')->nullable();
			$table->integer('addedBy')->nullable();
			$table->boolean('tms_wanted_old')->default(0);
			$table->integer('tms_priority')->default(0)->index('tms_priority');
			$table->boolean('tms_wanted')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tvseries');
	}

}
