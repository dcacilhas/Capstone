<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FavouritesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
        $seriesIds = [70327, 78874, 81189];

        // Generate favourites for first 4 users
        for ($i = 1; $i <= $numUsers; $i++) {
            $sortOrder = 1;
            foreach ($seriesIds as $seriesId) {
                DB::table('favourites')->insert([
                    'user_id' => $i,
                    'series_id' => $seriesId,
                    'sort_order' => $sortOrder++,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }
    }
}
