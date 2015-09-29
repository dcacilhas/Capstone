<?php

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ListTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        // 70327 - Buffy
        // 78874 - Firefly
        // 81189 - Breaking Bad
        // 153021 - The Walking Dead
        // 110381 - Archer
        // 121361 - Game of Thrones
        // 75299 - Sopranos
        // 176941 - Sherlock
        // 262980 - House of Cards
        // 79349 - Dexter
        // 73545 - Battlestar Galactica

        $numUsers = 4;
        $seriesIds = [70327, 78874, 81189, 153021, 110381, 121361, 75299, 176941, 262980, 79349, 73545];

        for ($i = 1; $i <= $numUsers; $i++) {
            foreach ($seriesIds as $seriesId) {
                DB::table('list')->insert([
                    'series_id' => $seriesId,
                    'user_id' => $i,
                    'list_status' => rand(0, 3),
                    'rating' => $faker->optional()->numberBetween(0, 10),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }
    }
}
