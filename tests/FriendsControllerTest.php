<?php

use App\Models\Friend;
use Fenos\Notifynder\Facades\Notifynder;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FriendsControllerTest extends TestCase
{
    use DatabaseTransactions;

    private $user;

    public function setUp()
    {
        parent::setUp();

        DB::beginTransaction();
        $this->user = factory(\App\Models\User::class)->create();
        $this->be($this->user);
    }

    public function tearDown()
    {
        DB::rollBack();
        parent::tearDown();
    }

    public function testAddFriend()
    {
        $friend1 = factory(\App\Models\User::class)->create();
        $friend2 = factory(\App\Models\User::class)->create();
        $friend3 = factory(\App\Models\User::class)->create();

        $this->visit(route('profile.friends', ['username' => $this->user->username]))
            ->seePageIs(route('profile.friends', ['username' => $this->user->username]));

        $this->assertViewHas('friends');

        // Test add friend from Friends page using username
        $this->click('addFriend')
            ->type($friend1->username, 'friendUsernameOrEmail')
            ->press('Send Friend Request')
            ->see('Friend request successfully sent to ' . $friend1->username . '.')
            ->seeInDatabase('friends', ['user_id' => $this->user->id, 'friend_id' => $friend1->id]);

        // Test add friend from Friends page using email
        $this->visit(route('profile.friends', ['username' => $this->user->username]))
            ->click('addFriend')
            ->type($friend2->email, 'friendUsernameOrEmail')
            ->press('Send Friend Request')
            ->see('Friend request successfully sent to ' . $friend2->username . '.')
            ->seeInDatabase('friends', ['user_id' => $this->user->id, 'friend_id' => $friend2->id]);

        // Test add friend from another user's profile
        $this->post(route('profile.friends.sendRequest', ['username' => $friend3->username]),
            ['username' => $friend3->username])
            ->seeInDatabase('friends', ['user_id' => $this->user->id, 'friend_id' => $friend3->id]);
    }

    public function testAcceptFriendRequest()
    {
        $friend = factory(\App\Models\User::class)->create();
        $this->username = $friend->username;
        Notifynder::category('friend.request')
            ->from($friend->id)
            ->to($this->user->id)
            ->url('')
            ->extra(compact('username'))
            ->send();
        $notification = $this->user->getLastNotification();

        $this->post(route('profile.friends.request.accept', [
                'username' => $this->user->username,
                'fromId' => $friend->id,
                'notificationId' => $notification->id
            ]
        ), [
            'username' => $this->user->username,
            'fromId' => $friend->id,
            'notificationId' => $notification->id
        ]);

        $this->seeInDatabase('friends', ['user_id' => $this->user->id, 'friend_id' => $friend->id]);
    }

    public function testDeclineFriendRequest()
    {
        $friend = factory(\App\Models\User::class)->create();
        $this->username = $friend->username;
        Notifynder::category('friend.decline')
            ->from($friend->id)
            ->to($this->user->id)
            ->url('')
            ->extra(compact('username'))
            ->send();
        $notification = $this->user->getLastNotification();

        $this->post(route('profile.friends.request.decline', [
                'username' => $this->user->username,
                'fromId' => $friend->id,
                'notificationId' => $notification->id
            ]
        ), [
            'username' => $this->user->username,
            'fromId' => $friend->id,
            'notificationId' => $notification->id
        ]);

        $this->missingFromDatabase('friends', ['user_id' => $friend->id, 'friend_id' => $this->user->id]);
    }

    public function testRemoveFriend()
    {
        $friend = factory(\App\Models\User::class)->create();
        Friend::create(['user_id' => $this->user->id, 'friend_id' => $friend->id]);
        Friend::create(['user_id' => $friend->id, 'friend_id' => $this->user->id]);

        $this->post(route('profile.friends.remove', ['username' => $friend->username]),
            ['username' => $friend->username])
            ->missingFromDatabase('friends', ['user_id' => $this->user->id, 'friend_id' => $friend->id])
            ->missingFromDatabase('friends', ['user_id' => $friend->id, 'friend_id' => $this->user->id]);
    }

    public function testAddFriendValidation()
    {
        $friend = factory(\App\Models\User::class)->create();
        Friend::create(['user_id' => $this->user->id, 'friend_id' => $friend->id]);
        Friend::create(['user_id' => $friend->id, 'friend_id' => $this->user->id]);

        // Test friend request validation to a friend by username
        $this->visit(route('profile.friends', ['username' => $this->user->username]))
            ->click('addFriend')
            ->type($friend->username, 'friendUsernameOrEmail')
            ->press('Send Friend Request')
            ->see('You are already friends with this user or there is a friend request pending.');

        // Test friend request validation to a friend by email
        $this->visit(route('profile.friends', ['username' => $this->user->username]))
            ->click('addFriend')
            ->type($friend->email, 'friendUsernameOrEmail')
            ->press('Send Friend Request')
            ->see('You are already friends with this user or there is a friend request pending.');

        // Test friend request validation to self by username
        $this->visit(route('profile.friends', ['username' => $this->user->username]))
            ->click('addFriend')
            ->type($this->user->username, 'friendUsernameOrEmail')
            ->press('Send Friend Request')
            ->see('You cannot send a friend request to yourself.');

        // Test friend request validation to self by email
        $this->visit(route('profile.friends', ['username' => $this->user->username]))
            ->click('addFriend')
            ->type($this->user->email, 'friendUsernameOrEmail')
            ->press('Send Friend Request')
            ->see('You cannot send a friend request to yourself.');

        // Test friend request validation to nonexistent user
        $this->visit(route('profile.friends', ['username' => $this->user->username]))
            ->click('addFriend')
            ->type('Not A Real User', 'friendUsernameOrEmail')
            ->press('Send Friend Request')
            ->see('No user has been found with that username or email address.');
    }
}
