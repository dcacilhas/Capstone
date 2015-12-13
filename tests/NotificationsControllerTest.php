<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotificationsControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testDismissNotification()
    {
        $user = factory(\App\Models\User::class)->create();
        $friend = factory(\App\Models\User::class)->create();
        $username = $friend->username;

        $this->be($user);

        // Test notification page loads
        $this->visit(route('profile.notifications', ['username' => $user->username]))
            ->seePageIs(route('profile.notifications', ['username' => $user->username]));

        // Test dismissing an accepted friend request notification
        Notifynder::category('friend.accept')
            ->from($friend->id)
            ->to($user->id)
            ->url('')
            ->extra(compact('username'))
            ->send();
        $notification = $user->getLastNotification();

        $this->post(route('profile.notifications.dismiss',
            ['username' => $user->username, 'notificationId' => $notification->id]),
            ['username' => $user->username, 'notificationId' => $notification->id]);

        $this->missingFromDatabase('notifications', ['id' => $notification->id]);

        // Test dismissing a declined friend request notification
        Notifynder::category('friend.decline')
            ->from($friend->id)
            ->to($user->id)
            ->url('')
            ->extra(compact('username'))
            ->send();
        $notification = $user->getLastNotification();

        $this->post(route('profile.notifications.dismiss',
            ['username' => $user->username, 'notificationId' => $notification->id]),
            ['username' => $user->username, 'notificationId' => $notification->id]);

        $this->missingFromDatabase('notifications', ['id' => $notification->id]);
    }
}
