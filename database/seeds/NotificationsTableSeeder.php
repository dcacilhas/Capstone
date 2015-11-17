<?php

use Illuminate\Database\Seeder;

class NotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Add notification category for friend requests
        DB::table('notification_categories')->insert([
            ['id' => 1, 'name' => 'friend.request', 'text' => '{extra.username} has sent you a friend request']
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
