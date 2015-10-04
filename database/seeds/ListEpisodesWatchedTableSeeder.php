<?php

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ListEpisodesWatchedTableSeeder extends Seeder
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

        $buffyIds = [1, 12, 23, 34];
        $fireflyIds = [2, 13, 24, 35];
        $breakingBadIds = [3, 14, 25, 36];

        // Seed Buffy (70327) episodes up to S02E01
        foreach ($buffyIds as $id) {
            DB::table('list_episodes_watched')->insert([
                ['episode_id' => 2, 'list_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['episode_id' => 3, 'list_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['episode_id' => 4, 'list_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['episode_id' => 5, 'list_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['episode_id' => 6, 'list_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['episode_id' => 7, 'list_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['episode_id' => 8, 'list_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['episode_id' => 9, 'list_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['episode_id' => 10, 'list_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['episode_id' => 11, 'list_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['episode_id' => 12, 'list_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['episode_id' => 13, 'list_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['episode_id' => 14, 'list_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ]);
        }

        // Seed Firefly (78874) episodes up to S01E06
        foreach ($fireflyIds as $id) {
            DB::table('list_episodes_watched')->insert([
                ['episode_id' => 297989, 'list_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['episode_id' => 297990, 'list_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['episode_id' => 297991, 'list_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['episode_id' => 297992, 'list_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['episode_id' => 297993, 'list_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['episode_id' => 297994, 'list_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ]);
        }

        // Seed Breaking Bad (81189) episodes up to S01E07
        foreach ($breakingBadIds as $id) {
            DB::table('list_episodes_watched')->insert([
                ['episode_id' => 349232, 'list_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['episode_id' => 349233, 'list_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['episode_id' => 349235, 'list_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['episode_id' => 349236, 'list_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['episode_id' => 349238, 'list_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['episode_id' => 355100, 'list_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['episode_id' => 352534, 'list_id' => $id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ]);
        }
    }
}
