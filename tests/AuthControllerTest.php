<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthControllerTest extends TestCase
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

    public function testGetLogin()
    {
        $this->visit(route('login'));

        $this->seePageIs(route('login'));
    }

    public function testGetRegister()
    {
        $this->visit(route('register'));

        $this->seePageIs(route('register'));
    }

    public function testRegisterValidationRequired()
    {
        $this->visit(route('register'))
            ->press('Register');

        $this->seePageIs(route('register'));
        $this->see('The username field is required');
        $this->see('The email field is required');
        $this->see('The password field is required');
    }

    public function testRegisterValidationEmailAndPassword()
    {
        $this->visit(route('register'))
            ->type('bademail', 'email')
            ->type('1234567', 'password')
            ->press('Register');

        $this->see('The email must be a valid email address');
        $this->see('The password confirmation does not match');
        $this->see('The password must be at least 8 characters');
    }

    public function testRegisterSuccess()
    {
        $username = 'test';
        $email = 'test@gmail.com';
        $password = 'password';

        $this->visit(route('register'))
            ->type($username, 'username')
            ->type($email, 'email')
            ->type($password, 'password')
            ->type($password, 'password_confirmation')
            ->press('Register');

        $this->seeInDatabase('users', ['email' => $email, 'username' => $username]);
        $this->seePageIs(route('profile', ['username' => $username]));
    }

    public function testLoginValidationRequired()
    {
        $this->visit(route('login'))
            ->press('Login');

        $this->seePageIs(route('login'));
        $this->see('The email field is required');
        $this->see('The password field is required');
    }

    public function testLoginSuccess()
    {
        $this->user = factory(\App\Models\User::class)->create([
            'password' => bcrypt($this->rawPassword)
        ]);
        $this->visit(route('login'))
            ->type($this->user->email, 'email')
            ->type($this->rawPassword, 'password')
            ->press('Login');

        $this->seePageIs(route('profile', ['username' => $this->user->username]));
    }

    public function testLoginCredentialsFail()
    {
        $this->user = factory(\App\Models\User::class)->create([
            'password' => bcrypt($this->rawPassword)
        ]);
        $wrongPassword = 'wrong password';
        $this->visit(route('login'))
            ->type($this->user->email, 'email')
            ->type($wrongPassword, 'password')
            ->press('Login');

        $this->seePageIs(route('login'));
        $this->see('These credentials do not match our records');
    }

    public function testLogout()
    {
        $this->user = factory(\App\Models\User::class)->create([
            'password' => bcrypt($this->rawPassword)
        ]);
        $this->be($this->user);
        $this->call('GET', route('logout'));

        $this->assertRedirectedTo('/');
    }
}
