<?php

class ShowsDetailsControllerTest extends TestCase
{
    private $seriesId;
    private $seriesName;
    private $episodeName;
    private $seasonNum;
    private $episodeNum;

    public function setUp()
    {
        parent::setUp();

        $this->seriesId = 70327;
        $this->seriesName = 'Buffy the Vampire Slayer';
        $this->episodeName = 'Welcome to the Hellmouth (1)';
        $this->seasonNum = 1;
        $this->episodeNum = 1;
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testGetShowDetails()
    {
        $this->visit(route('shows.details', ['seriesId' => $this->seriesId]))
            ->see($this->seriesName);

        $this->assertViewHas(['show', 'seasons', 'episodes']);
    }

    public function testEpisodeLink()
    {
        $this->visit(route('shows.details', ['seriesId' => $this->seriesId]))
            ->seeLink($this->episodeName, route('shows.episode',
                [
                    'seriesId' => $this->seriesId,
                    'seasonNum' => $this->seasonNum,
                    'episodeNum' => $this->episodeNum
                ]))
            ->click($this->episodeName)
            ->see($this->episodeName)
            ->see("Season $this->seasonNum Episode $this->episodeNum");
    }
}
