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
