<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Setup;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StarLord\Domain\Events\GameWasCreated;
use StarLord\Domain\Events\PlayerJoinedGame;
use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\Publisher;
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

    /**
     * @var Publisher|MockObject
     */
    private $publisher;

    public function setUp()
    {
        $this->phase = new PlayerSetup(
            $this->players = new PlayerCollection(),
            $this->publisher = $this->createMock(Publisher::class)
        );
    }

    public function test_it_should_create_a_player()
    {
        $this->assertCount(0, $this->players);

        $this->phase->onGameWasCreated(
            new GameWasCreated(
                [
                    new PlayerId(1),
                    new PlayerId(2),
                ]
            )
        );

        $this->assertCount(2, $this->players);
        $this->assertInstanceOf(TestPlayer::class, $this->players->getPlayerWithId(new PlayerId(2)));
    }

    public function test_it_should_throw_exception_when_player_with_id_already_exists()
    {
        $this->phase->onGameWasCreated(
            $event = new GameWasCreated(
                [
                    new PlayerId(1),
                ]
            )
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Player with id '1' already exists.");
        $this->phase->onGameWasCreated($event);
    }

    public function test_it_should_publish_event_on_game_created()
    {
        $this->publisher
            ->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf(PlayerJoinedGame::class));

        $this->phase->onGameWasCreated(
            $event = new GameWasCreated(
                [
                    new PlayerId(1),
                ]
            )
        );
    }
}
