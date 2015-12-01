<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;

class SearchControllerTest extends TestCase
{
    use WithoutMiddleware;

    private $query;

    public function setUp()
    {
        parent::setUp();

        $this->query = 'test query';
    }

    public function testGetSearch()
    {
        $this->visit(route('search'));

        $this->seePageIs(route('search'));
    }

    public function testGetSearchShow()
    {
        $this->visit(route('search.show', ['query' => $this->query]));

        $this->see("Search TV Shows: Results for \"$this->query\"");
    }

    public function testPostSearchShow()
    {
        $this->makeRequest('POST', route('search.show', ['query' => $this->query]));

        $this->see("Search TV Shows: Results for \"$this->query\"");
    }

    public function testGetSearchUsers()
    {
        $this->visit(route('search.user', ['query' => $this->query]));

        $this->see("Search Users: Results for \"$this->query\"");
    }

    public function testPostSearchUsers()
    {
        $this->makeRequest('POST', route('search.user', ['query' => $this->query]));

        $this->see("Search Users: Results for \"$this->query\"");
    }
}
