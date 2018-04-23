<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StarLord\Domain\Events\GameWasStarted;
use StarLord\Domain\Model\Exception\NotCompletedActionException;
use StarLord\Domain\Model\Publisher;
use StarLord\Domain\Model\TestPlayer;
use StarLord\Infrastructure\Persistence\InMemory\PlayerCollection;

final class StartGameHandlerTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $publisher;

    /**
     * @var StartGameHandler
     */
    private $handler;

    /**
     * @var PlayerCollection
     */
    private $players;

    public function setUp()
    {
        $this->handler = new StartGameHandler(
            $this->players = new PlayerCollection(),
            $this->publisher = $this->createMock(Publisher::class)
        );
    }

    public function test_should_update_resources_of_players_on_turn_started()
    {
        $this->publisher
            ->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf(GameWasStarted::class));

        $this->handler->__invoke(new StartGame());
    }

    public function test_it_should_throw_exception_when_one_player_has_uncompleted_actions()
    {
        $player = TestPlayer::fromInt(1);
        $player->startAction(['action']);
        $this->players->savePlayer($player->getIdentity(), $player);
        $this->assertFalse($this->players->allPlayersOfGameHavePlayed());

        $this->expectException(NotCompletedActionException::class);
        $this->expectExceptionMessage('Game cannot be started when some players have not completed their actions.');
        $this->handler->__invoke(new StartGame());
    }
}
