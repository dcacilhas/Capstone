<?php

class EpisodesControllerTest extends TestCase
{
    public function testEpisodeDetails()
    {
        $seriesId = 70327;
        $seriesName = 'Buffy the Vampire Slayer';
        $episodeName = 'Welcome to the Hellmouth (1)';
        $seasonNum = 1;
        $episodeNum = 1;

        $this->visit(route('shows.episode',
            [
                'seriesId' => $seriesId,
                'seasonNum' => $seasonNum,
                'episodeNum' => $episodeNum
            ]))
            ->see($seriesName)
            ->see($episodeName)
            ->see("Season $seasonNum Episode $episodeNum");

        $this->assertViewHas(['episode', 'show', 'season']);
    }
}
