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
            ['user_id' => 3, 'notification_type' => 0, 'status' => 'unread', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['user_id' => 1, 'notification_type' => 0, 'status' => 'unread', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['user_id' => 4, 'notification_type' => 0, 'status' => 'unread', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['user_id' => 3, 'notification_type' => 0, 'status' => 'unread', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
        ]);
    }
}
