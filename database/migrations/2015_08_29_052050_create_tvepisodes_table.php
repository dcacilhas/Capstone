<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTvepisodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tvepisodes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('seasonid')->unsigned()->default(0)->index('seasonid');
			$table->integer('EpisodeNumber')->unsigned()->default(0);
			$table->string('EpisodeName')->nullable()->default('Unknown');
			$table->string('FirstAired', 45)->nullable()->index('FirstAired');
			$table->text('GuestStars', 65535)->nullable();
			$table->text('Director', 65535)->nullable();
			$table->text('Writer', 65535)->nullable();
			$table->text('Overview', 65535)->nullable();
			$table->string('ProductionCode', 45)->nullable();
			$table->string('ShowURL')->nullable();
			$table->integer('lastupdated')->unsigned()->nullable()->index('lastupdated');
			$table->integer('flagged')->unsigned()->nullable()->default(0);
			$table->string('DVD_discid', 45)->nullable();
			$table->integer('DVD_season')->unsigned()->nullable();
			$table->decimal('DVD_episodenumber', 10, 1)->unsigned()->nullable();
			$table->integer('DVD_chapter')->unsigned()->nullable();
			$table->string('locked', 3)->default('no');
			$table->integer('absolute_number')->nullable();
			$table->string('filename')->nullable()->index('filename');
			$table->integer('seriesid')->unsigned()->index('seriesid');
			$table->integer('lastupdatedby')->unsigned()->default(0);
			$table->string('airsafter_season', 10)->nullable();
			$table->integer('airsbefore_season')->nullable();
			$table->integer('airsbefore_episode')->nullable();
			$table->integer('thumb_author')->default(1);
			$table->dateTime('thumb_added')->nullable();
			$table->smallInteger('thumb_width')->unsigned()->nullable();
			$table->smallInteger('thumb_height')->unsigned()->nullable();
			$table->bigInteger('tms_export')->nullable()->index('tms_export_3');
			$table->timestamp('mirrorupdate')->default(DB::raw('CURRENT_TIMESTAMP'))->index('mirrorupdate');
			$table->integer('lockedby');
			$table->string('IMDB_ID', 25)->nullable()->unique('IMDB_ID');
			$table->boolean('EpImgFlag')->nullable();
			$table->integer('tms_review_by')->nullable();
			$table->date('tms_review_date')->nullable();
			$table->boolean('tms_review_blurry')->default(0);
			$table->boolean('tms_review_dark')->default(0);
			$table->boolean('tms_review_logo')->default(0);
			$table->boolean('tms_review_other')->default(0);
			$table->boolean('tms_review_unsure')->default(0);
			$table->boolean('tms_priority', 1)->default('b'0'')->index('tms_priority');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tvepisodes');
	}

}
