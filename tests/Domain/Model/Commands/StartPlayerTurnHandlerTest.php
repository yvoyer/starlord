<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use PHPUnit\Framework\TestCase;
use StarLord\Domain\Events\GameWasStarted;
use StarLord\Domain\Model\TestPlayer;
use StarLord\Infrastructure\Persistence\InMemory\PlayerCollection;

final class StartPlayerTurnHandlerTest extends TestCase
{
    /**
     * @var StartPlayerTurnHandler
     */
    private $handler;

    /**
     * @var TestPlayer
     */
    private $player;

    public function setUp()
    {
        $this->handler = new StartPlayerTurnHandler(
            new PlayerCollection([$this->player = TestPlayer::playingPlayer(1)])
        );
        $this->player->endTurn();
    }

    public function test_it_should_start_turn_when_invoked()
    {
        $this->assertFalse($this->player->isActive());

        $this->handler->__invoke(new StartPlayerTurn($this->player->getIdentity()));

        $this->assertTrue($this->player->isActive());
    }

    public function test_it_should_start_turn_when_game_started()
    {
        $this->assertFalse($this->player->isActive());

        $this->handler->onGameWasStarted(new GameWasStarted([$this->player->getIdentity()]));

        $this->assertTrue($this->player->isActive());
    }
}
