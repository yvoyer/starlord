<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StarLord\Domain\Events\GameHasEnded;
use StarLord\Domain\Events\PlayerTurnHasEnded;
use StarLord\Domain\Events\TurnWasStarted;
use StarLord\Domain\Model\EndedGame;
use StarLord\Domain\Model\InProgressGame;
use StarLord\Domain\Model\Publisher;
use StarLord\Domain\Model\TestPlayer;
use StarLord\Infrastructure\Persistence\InMemory\PlayerCollection;

final class EndTurnHandlerTest extends TestCase
{
    /**
     * @var MockObject|Publisher
     */
    private $publisher;

    /**
     * @var PlayerCollection
     */
    private $players;

    /**
     * @var EndTurnHandler
     */
    private $handler;

    /**
     * @var TestPlayer
     */
    private $player;

    public function setUp()
    {
        $this->player = TestPlayer::playingPlayer(1);
        $this->players = new PlayerCollection([$this->player]);
        $this->publisher = $this->createMock(Publisher::class);
        $this->handler = new EndTurnHandler(
            $this->players,
            new InProgressGame(),
            $this->publisher
        );
    }

    public function test_it_should_end_the_game_at_end_of_turn()
    {
        $this->player->endTurn();
        $resolver = new EndedGame();
        $this->assertTrue($resolver->gameIsEnded());
        $this->assertTrue($this->player->turnIsDone());
        $this->publisher
            ->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf(GameHasEnded::class));

        $handler = new EndTurnHandler($this->players, $resolver, $this->publisher);
        $handler->onPlayerTurnHasEnded(new PlayerTurnHasEnded($this->player->getIdentity()));
    }

    public function test_it_should_start_a_new_turn_when_all_players_played_and_game_not_ended()
    {
        $this->player->endTurn();
        $resolver = new InProgressGame();
        $this->assertFalse($resolver->gameIsEnded());
        $this->assertTrue($this->player->turnIsDone());

        $this->publisher
            ->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf(TurnWasStarted::class));

        $handler = new EndTurnHandler($this->players, $resolver, $this->publisher);
        $handler->onPlayerTurnHasEnded(new PlayerTurnHasEnded($this->player->getIdentity()));
    }

    public function test_it_should_not_publish_event_when_not_all_players_have_played()
    {
        $this->assertFalse($this->player->turnIsDone());

        $this->publisher
            ->expects($this->never())
            ->method('publish');

        $this->handler->onPlayerTurnHasEnded(new PlayerTurnHasEnded($this->player->getIdentity()));
    }
}
