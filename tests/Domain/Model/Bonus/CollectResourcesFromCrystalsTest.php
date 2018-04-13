<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Bonus;

use PHPUnit\Framework\TestCase;
use StarLord\Domain\Events\TurnWasStarted;
use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\WriteOnlyPlayer;
use StarLord\Infrastructure\Persistence\InMemory\PlayerCollection;

final class CollectResourcesFromCrystalsTest extends TestCase
{
    /**
     * @var CollectResourcesFromCrystals
     */
    private $handler;

    /**
     * @var PlayerCollection
     */
    private $players;

    public function setUp()
    {
        $this->handler = new CollectResourcesFromCrystals(
            $this->players = new PlayerCollection()
        );
    }

    public function test_it_should_give_all_crystals_resources_generated_from_the_bought_crystals()
    {
        $this->markTestIncomplete('Inject hoard service instead of doing it on player');
        $player = $this->createMock(WriteOnlyPlayer::class);
        $player
            ->expects($this->once())
            ->method('collectResourcesFromCrystals');

        $this->players->savePlayer(new PlayerId(1), $player);
        $this->handler->onTurnWasStarted(new TurnWasStarted());
    }
}
