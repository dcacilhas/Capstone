<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use Carbon\Carbon;

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'username' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'about' => $faker->optional()->paragraph($nbSentences = rand(1, 5)),
        'birthday' => $faker->optional()->dateTimeThisCentury($max = 'now'),
        'location' => $faker->optional()->city,
        'gender' => $faker->optional()->randomElement($array = array('M', 'F')),
        'notification_email' => rand(0, 1),
        'profile_visibility' => rand(0, 2),
        'list_visibility' => rand(0, 2)
    ];
});

$factory->define(App\Models\Lists::class, function (Faker\Generator $faker) {
    return [
        'series_id' => 70327,
        'user_id' => 1,
        'list_status' => rand(0, 3),
        'rating' => $faker->optional()->numberBetween(1, 10),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
    ];
});

$factory->define(App\Models\ListEpisodesWatched::class, function (Faker\Generator $faker) {
    return [
        'episode_id' => 2,
        'list_id' => 1,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});

$factory->define(App\Models\Favourite::class, function (Faker\Generator $faker) {
    return [
        'user_id' => 1,
        'series_id' => 70327,
        'sort_order' => 1,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});
