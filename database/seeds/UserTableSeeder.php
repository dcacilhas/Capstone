<?php

use Carbon\Carbon;
use Elasticquent\ElasticquentTrait;
use Faker\Factory;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    use ElasticquentTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        $usernames = ['John Doe', 'Jane Doe', 'John Roe', 'Jane Roe'];

        foreach ($usernames as $username) {
            DB::table('users')->insert([
                'username' => $username,
                'email' => strtolower(str_replace(' ', '', $username)) . '@gmail.com',
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

        for ($i = 0; $i < 1000; $i++) {
            $username = $faker->firstName . ' ' . $faker->lastName;
            $data[] = [
                'username' => $username,
                'email' => strtolower(str_replace(' ', '', $username)) . '@gmail.com',
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
            ];
        }

        DB::table('users')->insert($data);

        User::rebuildMapping();
        User::reindex();
    }
}
