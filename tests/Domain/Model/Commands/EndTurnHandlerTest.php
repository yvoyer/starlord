<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StarLord\Domain\Events\GameHasEnded;
use StarLord\Domain\Events\PlayerTurnHasEnded;
use StarLord\Domain\Events\TurnWasStarted;
use StarLord\Domain\Model\EndedGame;
use StarLord\Domain\Model\InProgressGame;
use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\Publisher;
use StarLord\Domain\Model\ReadOnlyPlayers;

final class EndTurnHandlerTest extends TestCase
{
    /**
     * @var MockObject|Publisher
     */
    private $publisher;

    /**
     * @var ReadOnlyPlayers|MockObject
     */
    private $players;

    public function setUp()
    {
        $this->players = $this->createMock(ReadOnlyPlayers::class);
        $this->publisher = $this->createMock(Publisher::class);
    }

    public function test_it_should_end_the_game_at_end_of_turn()
    {
        $this->publisher
            ->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf(GameHasEnded::class));

        $handler = new EndTurnHandler($this->players, new EndedGame(), $this->publisher);
        $handler(new EndTurn());
    }

    public function test_it_should_start_a_new_turn_at_end_of_turn()
    {
        $this->publisher
            ->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf(TurnWasStarted::class));

        $handler = new EndTurnHandler($this->players, new InProgressGame(), $this->publisher);
        $handler(new EndTurn());
    }

    public function test_it_should_end_turn_when_all_players_have_played()
    {
        $this->players
            ->method('allPlayersOfGameHavePlayed')
            ->willReturn(true);
        $this->publisher
            ->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf(TurnWasStarted::class));

        $handler = new EndTurnHandler($this->players, new InProgressGame(), $this->publisher);
        $handler->onPlayerTurnHasEnded(new PlayerTurnHasEnded(new PlayerId(1)));
    }

    public function test_it_should_not_end_turn_when_not_all_players_have_played()
    {
        $this->publisher
            ->expects($this->never())
            ->method('publish');
        $handler = new EndTurnHandler($this->players, new InProgressGame(), $this->publisher);
        $handler->onPlayerTurnHasEnded(new PlayerTurnHasEnded(new PlayerId(1)));
    }
}
