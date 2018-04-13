<?php declare(strict_types=1);

namespace StarLord\Domain\Model\Commands;

use PHPUnit\Framework\TestCase;
use StarLord\Domain\Events\ShipWasMoved;
use StarLord\Domain\Model\Galaxy;
use StarLord\Domain\Model\PlanetId;
use StarLord\Domain\Model\PlayerId;
use StarLord\Domain\Model\ShipId;
use StarLord\Domain\Model\TestPlayer;
use StarLord\Infrastructure\Model\Stateful\TestShip;
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

    public function setUp()
    {
        $player = TestPlayer::fromInt(1);
        $player->startAction();

        $this->handler = new MoveShipHandler(
            $this->players = new PlayerCollection([1 => $player]),
            $this->armada = new ShipCollection([10 => $this->ship = new TestShip(new PlanetId(88))]),
            Galaxy::withPlanetCount(1)
        );
    }

    public function test_it_should_move_a_ship_between_planets()
    {
        $originalPlanet = new PlanetId(88);
        $newPlanet = new PlanetId(5);
        $this->assertTrue($this->ship->isDocked($originalPlanet));
        $this->assertFalse($this->ship->isDocked($newPlanet));

        $this->handler->__invoke(new MoveShip(new PlayerId(1), new ShipId(10), $newPlanet));

        $this->assertFalse($this->ship->isDocked($originalPlanet));
        $this->assertTrue($this->ship->isDocked($newPlanet));
    }

    public function test_it_should_trigger_a_combat_when_ships_of_another_player_present_at_destination()
    {
        $this->markTestIncomplete('todo');
        $this->handler->__invoke(new MoveShip(new PlayerId(1), new ShipId(10), new PlanetId(30)));
    }

    /**
     * @expectedException        \Star\Component\Identity\Exception\EntityNotFoundException
     * @expectedExceptionMessage Object of class 'StarLord\Domain\Model\ShipToken' with identity '99' could not be found.
     */
    public function test_it_should_throw_exception_when_ship_not_found()
    {
        $this->handler->__invoke(new MoveShip(new PlayerId(1), new ShipId(99), new PlanetId(30)));
    }

    public function test_it_should_publish_event_when_ship_was_moved()
    {
        $this->publisher
            ->expects($this->once())
            ->method('publish')
            ->with($this->isInstanceOf(ShipWasMoved::class));

        $this->player->startAction();
        $this->handler->__invoke(MoveShip::fromString(1, 'action'));
        $this->fail('todo');
    }
}
