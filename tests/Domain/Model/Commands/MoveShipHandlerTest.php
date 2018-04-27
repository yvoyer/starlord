<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StarLord\Domain\Events\ShipWasMoved;
use StarLord\Domain\Model\Galaxy;
use StarLord\Domain\Model\PlanetId;
use StarLord\Domain\Model\Publisher;
use StarLord\Domain\Model\ShipId;
use StarLord\Domain\Model\TestPlayer;
use StarLord\Domain\Model\TestShip;
use StarLord\Infrastructure\Persistence\InMemory\PlayerCollection;
use StarLord\Infrastructure\Persistence\InMemory\ShipCollection;

final class MoveShipHandlerTest extends TestCase
{
    /**
     * @var MoveShipHandler
     */
    private $handler;

    /**
     * @var PlayerCollection
     */
    private $players;

    /**
     * @var ShipCollection
     */
    private $armada;

    /**
     * @var TestShip
     */
    private $ship;

    /**
     * @var TestPlayer
     */
    private $player;

    /**
     * @var MockObject
     */
    private $publisher;

    public function setUp()
    {
        $this->player = TestPlayer::playingPlayer(1);
        $this->player->startAction();

        $this->handler = new MoveShipHandler(
            $this->players = new PlayerCollection([$this->player]),
            $this->armada = new ShipCollection(
                [
                    $this->ship = TestShip::fromInt(1, 88),
                ]
            ),
            Galaxy::withPlanetCount(1),
            $this->publisher = $this->createMock(Publisher::class)
        );
    }

    public function test_it_should_move_a_ship_between_planets()
    {
        $originalPlanet = new PlanetId(88);
        $newPlanet = new PlanetId(5);
        $this->assertTrue($this->ship->isDocked($originalPlanet));
        $this->assertFalse($this->ship->isDocked($newPlanet));

        $this->handler->__invoke(new MoveShip($this->player->getIdentity(), $this->ship->getIdentity(), $newPlanet));

        $this->assertFalse($this->ship->isDocked($originalPlanet));
        $this->assertTrue($this->ship->isDocked($newPlanet));
    }

    public function test_it_should_trigger_a_combat_when_ships_of_another_player_present_at_destination()
    {
        $this->markTestIncomplete('todo');
        $this->handler->__invoke(
            new MoveShip($this->player->getIdentity(), $this->ship->getIdentity(), new PlanetId(30))
        );
    }

    /**
     * @expectedException        \Star\Component\Identity\Exception\EntityNotFoundException
     * @expectedExceptionMessage Object of class 'StarLord\Domain\Model\WriteOnlyShip' with identity '99' could not be found.
     */
    public function test_it_should_throw_exception_when_ship_not_found()
    {
        $this->handler->__invoke(new MoveShip($this->player->getIdentity(), new ShipId(99), new PlanetId(30)));
    }

    public function test_it_should_publish_event_when_ship_was_moved()
    {
        $this->publisher
            ->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf(ShipWasMoved::class));

        $this->handler->__invoke(
            new MoveShip(
                $this->player->getIdentity(),
                $this->ship->getIdentity(),
                new PlanetId(3)
            )
        );
    }
}
