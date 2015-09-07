<?php

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        DB::table('users')->insert([
            [
                'username' => 'JohnDoe',
                'email' => 'johndoe@gmail.com',
                'password' => bcrypt('password'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'about' => $faker->optional()->paragraph($nbSentences = rand(1, 5)),
                'birthday' => $faker->optional()->dateTimeThisCentury($max = 'now'),
                'location' => $faker->optional()->city,
                'gender' => $faker->optional()->randomElement($array = array('M', 'F')),
                'notification_email' => rand(0, 1),
                'profile_visibility' => 0,
                'list_visibility' => 0
            ],
            [
                'username' => 'JaneDoe',
                'email' => 'janedoe@gmail.com',
                'password' => bcrypt('password'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'about' => $faker->optional()->paragraph($nbSentences = rand(1, 5)),
                'birthday' => $faker->optional()->dateTimeThisCentury($max = 'now'),
                'location' => $faker->optional()->city,
                'gender' => $faker->optional()->randomElement($array = array('M', 'F')),
                'notification_email' => rand(0, 1),
                'profile_visibility' => 0,
                'list_visibility' => 1
            ],
            [
                'username' => 'JohnRoe',
                'email' => 'johnroe@gmail.com',
                'password' => bcrypt('password'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'about' => $faker->optional()->paragraph($nbSentences = rand(1, 5)),
                'birthday' => $faker->optional()->dateTimeThisCentury($max = 'now'),
                'location' => $faker->optional()->city,
                'gender' => $faker->optional()->randomElement($array = array('M', 'F')),
                'notification_email' => rand(0, 1),
                'profile_visibility' => 1,
                'list_visibility' => 0
            ],
            [
                'username' => 'JaneRoe',
                'email' => 'janeroe@gmail.com',
                'password' => bcrypt('password'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'about' => $faker->optional()->paragraph($nbSentences = rand(1, 5)),
                'birthday' => $faker->optional()->dateTimeThisCentury($max = 'now'),
                'location' => $faker->optional()->city,
                'gender' => $faker->optional()->randomElement($array = array('M', 'F')),
                'notification_email' => rand(0, 1),
                'profile_visibility' => 1,
                'list_visibility' => 1
            ]
        ]);
    }
}
