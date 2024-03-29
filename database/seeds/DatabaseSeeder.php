<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(UserTableSeeder::class);
        $this->call(ListTableSeeder::class);
        $this->call(ListEpisodesWatchedTableSeeder::class);
        $this->call(FavouritesTableSeeder::class);
        $this->call(FriendsTableSeeder::class);
        $this->call(NotificationsTableSeeder::class);

        Model::reguard();
    }
}
