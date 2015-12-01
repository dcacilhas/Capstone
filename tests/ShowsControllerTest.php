<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowsControllerTest extends TestCase
{
    public function testGetShows()
    {
        $this->visit(route('shows'));

        $this->seePageIs(route('shows'));
    }

    public function testStartsWithFilterLinks()
    {
        $letters = range('A', 'Z');
        $this->visit(route('shows'));

        $this->seeLink('#', url('/shows?filter=%23'))
            ->click('#')
            ->see('Shows Starting With: #');
        foreach ($letters as $letter) {
            $this->seeLink("$letter", url("/shows?filter=$letter"))
                ->click("$letter")
                ->see("Shows Starting With: $letter");
        }
    }

    public function testGenreFilterLinks()
    {
        $genres = DB::table('genres')->lists('genre');
        $this->visit(route('shows'));

        foreach ($genres as $genre) {
            $g = urlencode($genre);
            $this->seeLink("$genre", url("/shows?genre=$g"))
                ->click("$genre")
                ->see("Genre: $genre");
        }
    }
}
