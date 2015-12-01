<?php

class PagesControllerTest extends TestCase
{
    public function testGetHome()
    {
        $this->visit(route('home'));

        $this->seePageIs(route('home'));
    }

    public function testGetHomeRedirect()
    {
        $this->call('GET', '/home');

        $this->assertRedirectedToRoute('home');
    }

    public function testGetAbout()
    {
        $this->visit(route('about'));

        $this->seePageIs(route('about'));
    }

    public function testNavigationShows()
    {
        $this->visit('/')
            ->click('TV Shows');

        $this->seePageIs(route('shows'));
    }

    public function testNavigationSearch()
    {
        $this->visit('/')
            ->click('Search');

        $this->seePageIs(route('search'));
    }

    public function testNavigationLogin()
    {
        $this->visit('/')
            ->click('Log In');

        $this->seePageIs(route('login'));
    }

    public function testNavigationRegister()
    {
        $this->visit('/')
            ->click('Register');

        $this->seePageIs(route('register'));
    }

    public function testFooterAbout()
    {
        $this->visit('/')
            ->click('About');

        $this->seePageIs(route('about'));
    }
}
