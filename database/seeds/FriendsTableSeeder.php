<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FriendsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // id   - userame
        // 1    - John Doe
        // 2    - Jane Doe
        // 3    - John Roe
        // 4    - Jane Roe

        DB::table('friends')->insert([
            ['user_id' => 1, 'friend_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['user_id' => 2, 'friend_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['user_id' => 1, 'friend_id' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['user_id' => 4, 'friend_id' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['user_id' => 2, 'friend_id' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['user_id' => 3, 'friend_id' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['user_id' => 2, 'friend_id' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['user_id' => 4, 'friend_id' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);

        // Add notifications for seeded unaccepted friend requests
        DB::table('notifications')->insert([
            ['from_id' => 1, 'from_type' => null, 'to_id' => 3, 'to_type' => null, 'category_id' => 1, 'extra' => '{"username":"John Doe"}', 'read' => 0],
            ['from_id' => 4, 'from_type' => null, 'to_id' => 1, 'to_type' => null, 'category_id' => 1, 'extra' => '{"username":"Jane Roe"}', 'read' => 0],
            ['from_id' => 2, 'from_type' => null, 'to_id' => 4, 'to_type' => null, 'category_id' => 1, 'extra' => '{"username":"Jane Doe"}', 'read' => 0],
            ['from_id' => 4, 'from_type' => null, 'to_id' => 3, 'to_type' => null, 'category_id' => 1, 'extra' => '{"username":"Jane Roe"}', 'read' => 0],
        ]);
    }
}
