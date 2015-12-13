<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class FavouritesControllerTest extends TestCase
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

    public function testFavouritesLoads()
    {
        $this->visit(route('profile.favourites', ['username' => $this->user->username]))
            ->seePageIs(route('profile.favourites', ['username' => $this->user->username]));

        $this->assertViewHas(['favourites', 'notFavourites']);
    }

    public function testAddFavourites()
    {
        $seriesId = 70327;
        $seriesIds = [78874, 81189, 153021, 110381];

        // Test add single favourite
        $this->post(route('profile.favourites.add', ['username' => $this->user->username]),
            ['favouritesToAdd' => $seriesId]);

        $this->seeInDatabase('favourites', ['user_id' => $this->user->id, 'series_id' => $seriesId, 'sort_order' => 1]);

        // Test add multiple favourites
        $this->post(route('profile.favourites.add', ['username' => $this->user->username]),
            ['favouritesToAdd' => $seriesIds]);

        $i = 2;
        foreach ($seriesIds as $series_id) {
            $this->seeInDatabase('favourites',
                ['user_id' => $this->user->id, 'series_id' => $series_id, 'sort_order' => $i++]);
        }
    }

    //Route::post('profile/{username}/favourites/remove', ['as' => 'profile.favourites.remove', 'uses' => 'FavouritesController@remove']);
    public function testRemoveFavourite()
    {
        // Add favourites to set up test
        $seriesIds = [70327, 78874, 81189, 153021, 110381];
        $i = 1;
        foreach ($seriesIds as $series_id) {
            factory(\App\Models\Favourite::class)->create([
                'user_id' => $this->user->id,
                'series_id' => $series_id,
                'sort_order' => $i++
            ]);
        }

        // Test remove single favourite
        $this->post(route('profile.favourites.remove', ['username' => $this->user->username]),
            ['series_id' => $seriesIds[2]]);

        $this->missingFromDatabase('favourites', ['user_id' => $this->user->id, 'series_id' => $seriesIds[2]]);

        // Check if sort order is correct for rest of favourites
        $i = 1;
        foreach ($seriesIds as $series_id) {
            // Skip the favourite that was removed
            if ($series_id == $seriesIds[2]) {
                continue;
            }
            $this->seeInDatabase('favourites',
                ['user_id' => $this->user->id, 'series_id' => $series_id, 'sort_order' => $i++]);
        }
    }

    public function testUpdateFavourite()
    {
        $seriesId = 70327;
        // Add favourite
        $this->post(route('profile.favourites.update',
            ['username' => $this->user->username, 'seriesId' => $seriesId]),
            ['username' => $this->user->username, 'seriesId' => $seriesId]);

        $this->seeInDatabase('favourites', ['user_id' => $this->user->id, 'series_id' => 70327]);

        // Remove favourite
        $this->post(route('profile.favourites.update',
            ['username' => $this->user->username, 'seriesId' => $seriesId]),
            ['username' => $this->user->username, 'seriesId' => $seriesId]);

        $this->missingFromDatabase('favourites', ['user_id' => $this->user->id, 'series_id' => 70327]);
    }

    public function testReorderFavourites()
    {
        // Add favourites to set up test
        $seriesIds = [70327, 78874, 81189, 153021, 110381];
        $i = 1;
        foreach ($seriesIds as $series_id) {
            factory(\App\Models\Favourite::class)->create([
                'user_id' => $this->user->id,
                'series_id' => $series_id,
                'sort_order' => $i++
            ]);
        }

        // Reorder the series IDs and hit the route
        shuffle($seriesIds);
        $this->post(route('profile.favourites.reorder', ['username' => $this->user->username]), ['item' => $seriesIds]);

        // Check if new sort order is correct
        $i = 1;
        foreach ($seriesIds as $series_id) {
            $this->seeInDatabase('favourites',
                ['user_id' => $this->user->id, 'series_id' => $series_id, 'sort_order' => $i++]);
        }
    }
}
