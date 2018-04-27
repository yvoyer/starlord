<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StarLord\Domain\Events\ColonsWereUnloaded;
use StarLord\Domain\Model\Colons;
use StarLord\Domain\Model\ColoredPlanet;
use StarLord\Domain\Model\Galaxy;
use StarLord\Domain\Model\PlanetId;
use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\Publisher;
use StarLord\Domain\Model\TestPlayer;
use StarLord\Domain\Model\TestShip;
use StarLord\Infrastructure\Persistence\InMemory\ShipCollection;

final class UnloadColonsHandlerTest extends TestCase
{
    /**
     * @var UnloadColonsHandler
     */
    private $handler;

    /**
     * @var Galaxy
     */
    private $world;

    /**
     * @var ShipCollection
     */
    private $armada;

    /**
     * @var Publisher|MockObject
     */
    private $publisher;

    /**
     * @var ColoredPlanet
     */
    private $planet;

    /**
     * @var TestShip
     */
    private $ship;

    /**
     * @var TestPlayer
     */
    private $player;

    public function setUp()
    {
        $this->ship = TestShip::transport(1, 3);
        $this->ship->loadColons($this->ship->remainingCapacity());
        $this->world = new Galaxy([]);
        $this->world->savePlanet(new PlanetId(3), $this->planet = ColoredPlanet::yellow());

        $this->player = TestPlayer::playingPlayer(5);
        $this->handler = new UnloadColonsHandler(
            $this->world,
            $this->armada = new ShipCollection([$this->ship]),
            $this->publisher = $this->createMock(Publisher::class)
        );
    }

    public function test_it_should_unload_colons_to_destination_planet()
    {
        $this->assertSame(0, $this->planet->population()->toInt());

        $this->handler->__invoke(
            new UnloadColons(
                $this->player->getIdentity(),
                $this->ship->getIdentity(),
                1
            )
        );

        $this->assertSame(1, $this->planet->population()->toInt());
    }

    public function test_it_should_change_owner_of_planet_on_unload()
    {
        $this->planet->colonize($old = new PlayerId(1), new Colons(0));
        $this->assertEquals($old, $this->planet->ownerId());

        $this->handler->__invoke(
            new UnloadColons(
                $this->player->getIdentity(),
                $this->ship->getIdentity(),
                1
            )
        );

        $this->assertNotEquals($old, $this->planet->ownerId());
        $this->assertEquals($this->player->getIdentity(), $this->planet->ownerId());
    }

    public function test_it_should_unload_colons_from_ship()
    {
        $this->assertSame(0, $this->ship->remainingCapacity()->toInt());

        $this->handler->__invoke(
            new UnloadColons(
                $this->player->getIdentity(),
                $this->ship->getIdentity(),
                1
            )
        );

        $this->assertSame(1, $this->ship->remainingCapacity()->toInt());
    }

    public function test_it_should_trigger_event_when_unloading_colons()
    {
        $this->publisher
            ->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf(ColonsWereUnloaded::class));

        $this->handler->__invoke(
            new UnloadColons(
                $this->player->getIdentity(),
                $this->ship->getIdentity(),
                1
            )
        );
    }
}
