<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Setup;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StarLord\Domain\Events\GameWasStarted;
use StarLord\Domain\Events\PlayerJoinedGame;
use StarLord\Domain\Model\Commands\StartGame;
use StarLord\Domain\Model\Publisher;

final class GameSetupTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $publisher;

    /**
     * @var GameSetup
     */
    private $phase;

    public function setUp()
    {
        $this->phase = new GameSetup(
            $this->publisher = $this->createMock(Publisher::class)
        );
    }

    public function test_should_update_resources_of_players_on_turn_started()
    {
        $this->publisher
            ->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf(GameWasStarted::class));

        $handler = $this->phase;
        $handler(new StartGame([]));
    }

    public function test_should_join_the_players_to_game()
    {
        $this->publisher
            ->expects($this->at(0))
            ->method('publish')
            ->with($this->isInstanceOf(PlayerJoinedGame::class));

        $handler = $this->phase;
        $handler(new StartGame([1]));
    }
}
