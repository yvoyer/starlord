<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Setup;

use PHPUnit\Framework\TestCase;
use StarLord\Domain\Events\PlayerJoinedGame;
use StarLord\Domain\Model\TestPlayer;
use StarLord\Infrastructure\Persistence\InMemory\PlayerCollection;

final class StartingSpaceshipsTest extends TestCase
{
    /**
     * @var PlayerCollection
     */
    private $players;

    /**
     * @var StartingSpaceships
     */
    private $handler;

    /**
     * @var TestPlayer
     */
    private $player;

    public function setUp()
    {
        $this->players = new PlayerCollection();
        $this->players->savePlayer(1, $this->player = TestPlayer::fromInt(1));
        $this->handler = new StartingSpaceships($this->players, 1, 2, 3);
    }

    public function test_it_should_assign_default_transports()
    {
        $this->assertSame(0, $this->player->getArmada()->transports());

        $this->handler->onPlayerJoinedGame(new PlayerJoinedGame(1));

        $this->assertSame(1, $this->player->getArmada()->transports());
    }

    public function test_it_should_assign_default_fighters()
    {
        $this->assertSame(0, $this->player->getArmada()->fighters());

        $this->handler->onPlayerJoinedGame(new PlayerJoinedGame(1));

        $this->assertSame(2, $this->player->getArmada()->fighters());
    }

    public function test_it_should_assign_default_cruisers()
    {
        $this->assertSame(0, $this->player->getArmada()->cruisers());

        $this->handler->onPlayerJoinedGame(new PlayerJoinedGame(1));

        $this->assertSame(3, $this->player->getArmada()->cruisers());
    }
}
