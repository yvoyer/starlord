<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Bonus;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StarLord\Domain\Events\TurnWasStarted;
use StarLord\Domain\Model\WriteOnlyPlanet;
use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\World;
use StarLord\Domain\Model\WriteOnlyPlayer;
use StarLord\Infrastructure\Persistence\InMemory\PlayerCollection;

final class CollectResourcesFromPlanetsTest extends TestCase
{
    /**
     * @var CollectResourcesFromPlanets
     */
    private $phase;

    /**
     * @var MockObject
     */
    private $world;

    /**
     * @var PlayerCollection
     */
    private $players;

    public function setUp()
    {
        $this->phase = new CollectResourcesFromPlanets(
            $this->world = $this->createMock(World::class),
            $this->players = new PlayerCollection()
        );
    }

    public function test_it_should_not_collect_resource_when_player_has_no_colonized_planets()
    {
        $this->markTestIncomplete('Put in hoard service');
        $this->players->savePlayer(new PlayerId(1), $player = $this->createMock(WriteOnlyPlayer::class));
        $player
            ->expects($this->never())
            ->method('collectResourcesFromPlanet');
        $this->world
            ->expects($this->once())
            ->method('allColonizedPlanetsOfPlayer');

        $this->phase->onTurnWasStarted(new TurnWasStarted());
    }

    public function test_it_should_collect_resources_of_planets_owned_by_player()
    {
        $this->markTestIncomplete('Put in hoard service');
        $this->players->savePlayer(new PlayerId(1), $player = $this->createMock(WriteOnlyPlayer::class));
        $this->world
            ->expects($this->once())
            ->method('allColonizedPlanetsOfPlayer')
            ->with(1)
            ->willReturn([$planet = $this->createMock(WriteOnlyPlanet::class)]);
        $player
            ->expects($this->once())
            ->method('collectResourcesFromPlanet');

        $this->phase->onTurnWasStarted(new TurnWasStarted());
    }
}
