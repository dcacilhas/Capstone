<?php

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProfileControllerTest extends TestCase
{
    use DatabaseTransactions;

    private $user;
    private $rawPassword;

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
        $faker = Factory::create();
        $this->user = factory(\App\Models\User::class)->create([
            'password' => bcrypt($this->rawPassword),
            'gender' => $faker->randomElement($array = array('M', 'F')),
            'about' => $faker->paragraph($nbSentences = rand(1, 5)),
            'birthday' => Carbon::instance($faker->dateTimeThisCentury($max = 'now')),
            'location' => $faker->city,
        ]);

        $this->be($this->user);

        $this->visit(route('profile', ['username' => $this->user->username]))
            ->see($this->user->username)
            ->see($this->user->gender)
            ->see(Carbon::parse($this->user->birthday)->format('F j, Y'))
            ->see($this->user->location)
            ->see(Carbon::parse($this->user->created_at)->format('F j, Y'))
            ->see($this->user->about);

        $this->assertViewHas(['recentEpsWatched', 'favourites', 'statistics', 'genres']);
    }

    public function testEditProfile()
    {
        // Must disable ElasticSearch updateIndex to work (ProfileController line 192)
        $this->user = factory(\App\Models\User::class)->create([
            'password' => bcrypt($this->rawPassword),
            'birthday' => '1970-01-01',
            'gender' => 'M'
        ]);

        $newGender = 'F';
        $newBirthday = '1987-01-22';
        $newLocation = 'Test location';
        $newAbout = 'Test about me text.';
        $newNotificationEmail = '0';
        $newProfileVisibility = '0';
        $newListVisibility = '0';

        $this->be($this->user);

        $this->visit(route('profile.edit.profile', ['username' => $this->user->username]))
            ->seePageIs(route('profile.edit.profile', ['username' => $this->user->username]));

        $this->select($newGender, 'gender')
            ->type($newBirthday, 'birthday')
            ->type($newLocation, 'location')
            ->type($newAbout, 'about')
            ->select($newNotificationEmail, 'notification_email')
            ->select($newProfileVisibility, 'profile_visibility')
            ->select($newListVisibility, 'list_visibility')
            ->press('Save Profile');

        $this->see('Profile successfully updated!')
            ->seeInDatabase('users', [
                'username' => $this->user->username,
                'gender' => $newGender,
                'birthday' => $newBirthday,
                'location' => $newLocation,
                'about' => $newAbout,
                'notification_email' => $newNotificationEmail,
                'profile_visibility' => $newProfileVisibility,
                'list_visibility' => $newListVisibility
            ]);
    }

    public function testEditAccount()
    {
        // Must disable ElasticSearch updateIndex to work (ProfileController line 261)
        $this->user = factory(\App\Models\User::class)->create([
            'password' => bcrypt($this->rawPassword)
        ]);

        $newEmail = 'test@gmail.com';
        $newPassword = 'newpassword';

        $this->be($this->user);

        $this->visit(route('profile.edit.account', ['username' => $this->user->username]))
            ->seePageIs(route('profile.edit.account', ['username' => $this->user->username]));

        $this->type($newEmail, 'email')
            ->type($newEmail, 'email_confirmation')
            ->type($this->rawPassword, 'password')
            ->press('Change Email');

        $this->see('Email successfully updated!')
            ->seeInDatabase('users', [
                'username' => $this->user->username,
                'email' => $newEmail
            ]);

        $this->type($this->rawPassword, 'current_password')
            ->type($newPassword, 'new_password')
            ->type($newPassword, 'new_password_confirmation')
            ->press('Change Password');

        $this->see('Password successfully updated!');
        $this->assertTrue(Hash::check($newPassword, $this->user->password));
    }
}
