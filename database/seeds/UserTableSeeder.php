<?php

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        $usernames = ['JohnDoe', 'JaneDoe', 'JohnRoe', 'JaneRoe'];

        foreach($usernames as $username) {
            DB::table('users')->insert([
                'username' => $username,
                'email' => strtolower($username) . '@gmail.com',
                'password' => bcrypt('password'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'about' => $faker->optional()->paragraph($nbSentences = rand(1, 5)),
                'birthday' => $faker->optional()->dateTimeThisCentury($max = 'now'),
                'location' => $faker->optional()->city,
                'gender' => $faker->optional()->randomElement($array = array('M', 'F')),
                'notification_email' => rand(0, 1),
                'profile_visibility' => rand(0, 2),
                'list_visibility' => rand(0, 2)
            ]);
        }
    }
}
