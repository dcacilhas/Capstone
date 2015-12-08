<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProfileControllerTest extends TestCase
{
    use DatabaseTransactions;

    private $user;
    private $rawPassword;

//Route::get('profile/{username}', ['as' => 'profile', 'uses' => 'ProfileController@index']);
//Route::get('profile/{username}/edit/profile', ['as' => 'profile.edit.profile', 'uses' => 'ProfileController@showEditProfile']);
//Route::get('profile/{username}/edit/account', ['as' => 'profile.edit.account', 'uses' => 'ProfileController@showEditAccount']);

    public function setUp()
    {
        parent::setUp();

        $this->rawPassword = 'password';
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testProfileHomeDetails()
    {
        $this->user = factory(\App\Models\User::class)->create([
            'password' => bcrypt($this->rawPassword),
            'gender' => 'M'
        ]);

        $this->be($this->user);

        $this->visit(route('profile', ['username' => $this->user->username]))
            ->see($this->user->username)
            ->see('Male')
            ->see(Carbon::parse($this->user->birthday)->format('F j, Y'))
            ->see($this->user->location)
            ->see(Carbon::parse($this->user->created_at)->format('F j, Y'))
            ->see($this->user->about);

        $this->assertViewHas(['recentEpsWatched', 'favourites', 'statistics', 'genres']);
    }

    public function testEditProfile()
    {
        $this->user = factory(\App\Models\User::class)->create([
            'password' => bcrypt($this->rawPassword),
            'gender' => 'M'
        ]);

        $this->be($this->user);

        $this->visit(route('profile.edit.profile', ['username' => $this->user->username]))
            ->seePageIs(route('profile.edit.profile', ['username' => $this->user->username]));

        $this->select('F', 'gender')
            ->type('22/01/1987', 'birthday')
            ->type('Test location', 'location')
            ->type('Test about me text.', 'about')
            ->select('0', 'notification_email')
            ->select('0', 'profile_visibility')
            ->select('0', 'list_visibility')
            ->press('Save Profile')
            ->see('Profile successfully updated!')
            ->seeInDatabase('users', []);

    }

    public function testEditAccount()
    {
        $this->user = factory(\App\Models\User::class)->create([
            'password' => bcrypt($this->rawPassword)
        ]);

        $this->be($this->user);

        $this->visit(route('profile.edit.account', ['username' => $this->user->username]))
            ->seePageIs(route('profile.edit.account', ['username' => $this->user->username]));;
    }
}
