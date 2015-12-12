<?php

use App\Models\Friend;
use Fenos\Notifynder\Facades\Notifynder;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FriendsControllerTest extends TestCase
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

    public function testAddFriend()
    {
        $user = factory(\App\Models\User::class)->create();
        $friend1 = factory(\App\Models\User::class)->create();
        $friend2 = factory(\App\Models\User::class)->create();
        $friend3 = factory(\App\Models\User::class)->create();

        $this->be($user);

        $this->visit(route('profile.friends', ['username' => $user->username]))
            ->seePageIs(route('profile.friends', ['username' => $user->username]));

        $this->assertViewHas('friends');

        // Test add friend from Friends page using username
        $this->click('addFriend')
            ->type($friend1->username, 'friendUsernameOrEmail')
            ->press('Send Friend Request')
            ->see('Friend request successfully sent to ' . $friend1->username . '.')
            ->seeInDatabase('friends', ['user_id' => $user->id, 'friend_id' => $friend1->id]);

        // Test add friend from Friends page using email
        $this->visit(route('profile.friends', ['username' => $user->username]))
            ->click('addFriend')
            ->type($friend2->email, 'friendUsernameOrEmail')
            ->press('Send Friend Request')
            ->see('Friend request successfully sent to ' . $friend2->username . '.')
            ->seeInDatabase('friends', ['user_id' => $user->id, 'friend_id' => $friend2->id]);

        // Test add friend from another user's profile
        $this->post(route('profile.friends.sendRequest', ['username' => $friend3->username]), ['username' => $friend3->username])
            ->seeInDatabase('friends', ['user_id' => $user->id, 'friend_id' => $friend3->id]);
    }

    public function testAcceptFriendRequest()
    {
        $user = factory(\App\Models\User::class)->create();
        $friend = factory(\App\Models\User::class)->create();
        $username = $friend->username;
        Notifynder::category('friend.request')
            ->from($friend->id)
            ->to($user->id)
            ->url('')
            ->extra(compact('username'))
            ->send();
        $notification = $user->getLastNotification();

        $this->be($user);

        $this->post(route('profile.friends.request.accept', [
            'username' => $user->username,
            'fromId' => $friend->id,
            'notificationId' => $notification->id]
        ), ['username' => $user->username,
            'fromId' => $friend->id,
            'notificationId' => $notification->id]);

        $this->seeInDatabase('friends', ['user_id' => $user->id, 'friend_id' => $friend->id]);
    }

    public function testDeclineFriendRequest()
    {
        $user = factory(\App\Models\User::class)->create();
        $friend = factory(\App\Models\User::class)->create();
        $username = $friend->username;
        Notifynder::category('friend.decline')
            ->from($friend->id)
            ->to($user->id)
            ->url('')
            ->extra(compact('username'))
            ->send();
        $notification = $user->getLastNotification();

        $this->be($user);

        $this->post(route('profile.friends.request.decline', [
                'username' => $user->username,
                'fromId' => $friend->id,
                'notificationId' => $notification->id]
        ), ['username' => $user->username,
            'fromId' => $friend->id,
            'notificationId' => $notification->id]);

        $this->missingFromDatabase('friends', ['user_id' => $friend->id, 'friend_id' => $user->id]);
    }

    public function testRemoveFriend()
    {
        $user = factory(\App\Models\User::class)->create();
        $friend = factory(\App\Models\User::class)->create();
        Friend::create(['user_id' => $user->id, 'friend_id' => $friend->id]);
        Friend::create(['user_id' => $friend->id, 'friend_id' => $user->id]);

        $this->be($user);

        $this->post(route('profile.friends.remove', ['username' => $friend->username]), ['username' => $friend->username])
            ->missingFromDatabase('friends', ['user_id' => $user->id, 'friend_id' => $friend->id])
            ->missingFromDatabase('friends', ['user_id' => $friend->id, 'friend_id' => $user->id]);
    }

    public function testAddFriendValidation()
    {
        $user = factory(\App\Models\User::class)->create();
        $friend = factory(\App\Models\User::class)->create();
        Friend::create(['user_id' => $user->id, 'friend_id' => $friend->id]);
        Friend::create(['user_id' => $friend->id, 'friend_id' => $user->id]);

        $this->be($user);

        // Test friend request validation to a friend by username
        $this->visit(route('profile.friends', ['username' => $user->username]))
            ->click('addFriend')
            ->type($friend->username, 'friendUsernameOrEmail')
            ->press('Send Friend Request')
            ->see('You are already friends with this user or there is a friend request pending.');

        // Test friend request validation to a friend by email
        $this->visit(route('profile.friends', ['username' => $user->username]))
            ->click('addFriend')
            ->type($friend->email, 'friendUsernameOrEmail')
            ->press('Send Friend Request')
            ->see('You are already friends with this user or there is a friend request pending.');

        // Test friend request validation to self by username
        $this->visit(route('profile.friends', ['username' => $user->username]))
            ->click('addFriend')
            ->type($user->username, 'friendUsernameOrEmail')
            ->press('Send Friend Request')
            ->see('You cannot send a friend request to yourself.');

        // Test friend request validation to self by email
        $this->visit(route('profile.friends', ['username' => $user->username]))
            ->click('addFriend')
            ->type($user->email, 'friendUsernameOrEmail')
            ->press('Send Friend Request')
            ->see('You cannot send a friend request to yourself.');

        // Test friend request validation to nonexistent user
        $this->visit(route('profile.friends', ['username' => $user->username]))
            ->click('addFriend')
            ->type('Not A Real User', 'friendUsernameOrEmail')
            ->press('Send Friend Request')
            ->see('No user has been found with that username or email address.');
    }
}
