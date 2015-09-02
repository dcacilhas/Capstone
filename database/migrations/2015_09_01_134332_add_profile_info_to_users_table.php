<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddProfileInfoToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_login')->nullable();
            $table->timestamp('notifications_last_checked')->nullable();
            $table->text('about')->nullable();
            $table->date('birthday')->nullable();
            $table->string('location', 50)->nullable();
            $table->char('gender', 1)->nullable();
            $table->foreign('gender')->references('gender')->on('genders');
            $table->string('avatar_path')->nullable();
            $table->boolean('notification_email')->default(1);
            $table->tinyInteger('profile_visibility')->default(0);
            $table->foreign('profile_visibility')->references('profile_visibility')->on('profile_visibilities');
            $table->tinyInteger('list_visibility')->default(0);
            $table->foreign('list_visibility')->references('list_visibility')->on('list_visibilities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_gender_foreign');
            $table->dropForeign('users_profile_visibility_foreign');
            $table->dropForeign('users_list_visibility_foreign');
            $table->dropColumn([
                'last_login',
                'notifications_last_checked',
                'about',
                'birthday',
                'location',
                'gender',
                'avatar_path',
                'notification_email',
                'profile_visibility',
                'list_visibility'
            ]);
        });
    }
}
