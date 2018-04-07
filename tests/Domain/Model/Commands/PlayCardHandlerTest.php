<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StarLord\Domain\Events\CardWasPlayed;
use StarLord\Domain\Model\Publisher;
use StarLord\Domain\Model\WriteOnlyPlayer;
use StarLord\Infrastructure\Persistence\InMemory\PlayerCollection;

final class PlayCardHandlerTest extends TestCase
{
    /**
     * @var PlayerCollection
     */
    private $players;

    /**
     * @var MockObject|Publisher
     */
    private $publisher;

    public function setUp()
    {
        $this->players = new PlayerCollection();
        $this->publisher = $this->createMock(Publisher::class);
    }

    public function test_it_should_increase_the_quantity_of_ship_in_play()
    {
        $playerId = 12;
        $player = $this->createMock(WriteOnlyPlayer::class);
        $player
            ->expects($this->once())
            ->method('playCard');
        $this->players->savePlayer($playerId, $player);

        $handler = new PlayCardHandler($this->players, $this->publisher);
        $handler(new PlayCard($playerId, 34));
    }

    public function test_it_should_publish_event()
    {
        $this->publisher
            ->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf(CardWasPlayed::class));

        $this->players->savePlayer(1, $this->createMock(WriteOnlyPlayer::class));
        $handler = new PlayCardHandler($this->players, $this->publisher);
        $handler(new PlayCard(1, 34));
    }
}
