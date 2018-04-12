<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Setup;

use PHPUnit\Framework\TestCase;
use StarLord\Domain\Events\PlayerJoinedGame;
use StarLord\Domain\Model\TestPlayer;
use StarLord\Infrastructure\Persistence\InMemory\PlayerCollection;

final class PlayerSetupTest extends TestCase
{
    /**
     * @var PlayerSetup
     */
    private $phase;

    /**
     * @var PlayerCollection
     */
    private $players;

    public function setUp()
    {
        $this->phase = new PlayerSetup(
            $this->players = new PlayerCollection()
        );
    }

    public function test_it_should_create_a_player()
    {
        $this->assertCount(0, $this->players);

        $this->phase->onPlayerJoinedGame(new PlayerJoinedGame(2));

        $this->assertCount(1, $this->players);
        $this->assertInstanceOf(TestPlayer::class, $this->players->getPlayerWithId(2));
    }

    public function test_it_should_throw_exception_when_player_with_id_already_exists()
    {
        $this->phase->onPlayerJoinedGame(new PlayerJoinedGame(2));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Player with id '2' already exists.");
        $this->phase->onPlayerJoinedGame(new PlayerJoinedGame(2));
    }
}
