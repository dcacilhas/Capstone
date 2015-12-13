<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class ListControllerTest extends TestCase
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

    public function testListLoads()
    {
        $this->visit(route('profile.list', ['username' => $this->user->username]))
            ->seePageIs(route('profile.list', ['username' => $this->user->username]));

        $this->assertViewHas('shows');
    }

    public function testHistory()
    {
        $this->visit(route('profile.list.history', ['username' => $this->user->username]))
            ->seePageIs(route('profile.list.history', ['username' => $this->user->username]));

        $this->assertViewHas(['shows', 'epsWatched']);
    }

    public function testHistoryFilter()
    {
        // Create a list with a series on it and add 2 episodes watched
        $userList = factory(\App\Models\Lists::class)->create(['user_id' => $this->user->id]);
        factory(\App\Models\ListEpisodesWatched::class)->create(['list_id' => $userList->id]);
        factory(\App\Models\ListEpisodesWatched::class)->create(['list_id' => $userList->id, 'episode_id' => 3]);

        $this->visit(route('profile.list.history.show',
            ['username' => $this->user->username, 'seriesId' => $userList->series_id]))
            ->seePageIs(route('profile.list.history.show',
                ['username' => $this->user->username, 'seriesId' => $userList->series_id]))
            ->see('2 episodes of Buffy the Vampire Slayer watched');

        $this->assertViewHas(['shows', 'epsWatched', 'seriesId']);
    }

    public function testAddShowToList()
    {
        $seriesId = 70327;
        $listStatus = 0;
        $this->post(route('profile.list.add', ['username' => $this->user->username]),
            ['series_id' => $seriesId, 'status' => $listStatus]);

        $this->seeInDatabase('list', [
            'series_id' => $seriesId,
            'user_id' => $this->user->id,
            'list_status' => $listStatus
        ]);
    }

    public function testRemoveShowFromList()
    {
        $userList = factory(\App\Models\Lists::class)->create(['user_id' => $this->user->id]);
        $this->post(route('profile.list.remove', ['username' => $this->user->username]),
            ['series_id' => $userList->series_id]);

        $this->missingFromDatabase('list', [
            'series_id' => $userList->series_id,
            'user_id' => $this->user->id,
            'list_status' => $userList->list_status
        ]);
    }

    public function testUpdateList()
    {
        $oldStatus = 0;
        $oldRating = 5;
        $newStatus = 3;
        $newRating = 9;
        $userList = factory(\App\Models\Lists::class)->create([
            'user_id' => $this->user->id,
            'list_status' => $oldStatus,
            'rating' => $oldRating
        ]);

        $this->post(route('profile.list.update', ['username' => $this->user->username]), [
            'series_id' => $userList->series_id,
            'status' => $newStatus,
            'rating' => $newRating
        ]);

        $this->seeInDatabase('list', [
            'series_id' => $userList->series_id,
            'user_id' => $this->user->id,
            'list_status' => $newStatus,
            'rating' => $newRating
        ]);
    }

    public function testUpdateEpisodesWatched()
    {
        $userList = factory(\App\Models\Lists::class)->create(['user_id' => $this->user->id]);

        // Test mark single episode as watched
        $this->post(route('list.episodes.update', ['seriesId' => $userList->series_id]), ['episodeIds' => 2]);

        $this->seeInDatabase('list_episodes_watched', [
            'episode_id' => 2,
            'list_id' => $userList->id,
        ]);

        // Test mark single episode as unwatched (dependent on previous test)
        $this->post(route('list.episodes.update', ['seriesId' => $userList->series_id]), ['episodeIds' => 2]);

        $this->missingFromDatabase('list_episodes_watched', [
            'episode_id' => 2,
            'list_id' => $userList->id,
        ]);

        // Test mark multiple episodes as watched
        $this->post(route('list.episodes.update', ['seriesId' => $userList->series_id]),
            ['episodeIds' => [2, 3, 4], 'action' => 'add']);

        $this->seeInDatabase('list_episodes_watched', ['episode_id' => 2, 'list_id' => $userList->id]);
        $this->seeInDatabase('list_episodes_watched', ['episode_id' => 3, 'list_id' => $userList->id]);
        $this->seeInDatabase('list_episodes_watched', ['episode_id' => 4, 'list_id' => $userList->id]);

        // Test mark multiple episodes as unwatched (dependent on previous test)
        $this->post(route('list.episodes.update', ['seriesId' => $userList->series_id]),
            ['episodeIds' => [2, 3, 4], 'action' => 'remove']);

        $this->missingFromDatabase('list_episodes_watched', ['episode_id' => 2, 'list_id' => $userList->id]);
        $this->missingFromDatabase('list_episodes_watched', ['episode_id' => 3, 'list_id' => $userList->id]);
        $this->missingFromDatabase('list_episodes_watched', ['episode_id' => 4, 'list_id' => $userList->id]);
    }
}
